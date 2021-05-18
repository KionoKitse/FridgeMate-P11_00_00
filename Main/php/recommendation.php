<?php

    //Function that returns an ingredient based on the requested ID
    function GetIngredient($Id, $Result){
        while ($row = mysqli_fetch_assoc($Result)) {
            if ($row["item_id"] == $Id) {
                //Set the pointer back to the beginning and send results
                mysqli_data_seek($Result, 0);
                return $row;
            }
        }
    }
    //Function that reads the JSON data
    function ReadJSON($Id){
        $JsonPath = file_get_contents('json/'.$Id.'.json');
        $JsonData = json_decode($JsonPath, true);
        return $JsonData;
    }
    //Function that gets the MySQL data
    function GetMySQL($Id){
        //Output variables
        $Main = array();
        $Support = array();
        $Spices = array();
        $Garnish = array();
        $Prep = array();
        $Percent = 0;
        $Steps = array();

        require_once 'dbconnect.php';
        //Get the ingredients values needed for the recipe
        $Query1 = "SELECT * FROM ingredient WHERE recipe_id = '".$Id."'";
        $ResultSet1 = $connection->query($Query1);

        //Get the ingredient information
        $Query1 = "SELECT item_id FROM ingredient WHERE recipe_id = '".$Id."'";
        $Query2 = "SELECT * FROM pantry WHERE item_id IN (" . $Query1 . ")";
        $ResultSet2 = $connection->query($Query2);

        //Get the ingredient information
        $Query1 = "SELECT * FROM recipe WHERE recipe_id = '".$Id."'";
        $ResultSet3 = $connection->query($Query1);

        //Get the recipe percent
        while ($row = $ResultSet3->fetch_row()) {
            $Percent = $row[6];
        }
        $Percent = floatval($Percent);

        //Build lists of ingredients
        while ($row = $ResultSet1->fetch_row()) {
            //Get the ingredient object
            $Ingredient = GetIngredient($row[1], $ResultSet2);
            
            /*
            * Status guide
            * 0 = Don't have (MySQL)
            * 1 = Have (MySQL)
            * 2 = Shopping list (MySQL)
            * 3 = Buildable
            * 4 = Substitute
            */
            //Check the ingredient is not available
            if ($Ingredient["status"] == 0){
                //Check if the ingredient is buildable
                if($Ingredient["recipe_id"]>0){
                    //Check the buildability score for that ingredient
                    $Query1 = "SELECT percent FROM fridgemate_db.recipe WHERE recipe_id = '".$Ingredient["recipe_id"]."'";
                    $ResultSet4 = $connection->query($Query1);
                    while ($row1 = $ResultSet4->fetch_row()) {
                        if($row1[0] > 90){
                            $Ingredient["status"] = 3;
                        }
                    }
                    
                }
                //Check if ingredient has a substitute
                if ($Ingredient["status"] == 0){
                    //Find if there are any substitute ingredients
                    $Query1 = "SELECT group_id FROM fridgemate_db.group WHERE item_id = '".$Ingredient["item_id"]."'";
                    $Query2 = "SELECT item_id FROM fridgemate_db.group WHERE group_id IN (" . $Query1 . ") AND item_id != '".$Ingredient["item_id"]."'";
                    $ResultSet5 = $connection->query($Query2);
                    //SELECT ITEM_ID FROM fridgemate_db.group where GROUP_ID in (SELECT GROUP_ID FROM fridgemate_db.group where ITEM_ID = '19') and ITEM_ID != '19';
                    //Check any of the items in the group are available
                    while ($row1 = $ResultSet5->fetch_row()) {
                        $Item = $row1[0];
                        $Query1 = "SELECT * FROM pantry WHERE item_id = '".$row1[0]."'";
                        $ResultSet6 = $connection->query($Query1);
                        //Check if ingredient is available
                        while ($row2 = $ResultSet6->fetch_row()) {
                            if($row2[4] == 1){
                                //Replace ingredient with substitute and mark as a sub
                                $Ingredient["item_id"] = $row2[0];
                                $Ingredient["name1"] = $row2[1];
                                $Ingredient["name2"] = $row2[2];
                                $Ingredient["name3"] = $row2[3];
                                $Ingredient["status"] = 4;
                            }
                        }
                    }
                }
            }

            //Create new ingredient object
            $Object = (object) [
                'Quantity' => $row[3],
                'Unit' => $row[4],
                'Name1' => $Ingredient["name1"],
                'Name2' => $Ingredient["name2"],
                'Name3' => $Ingredient["name3"],
                'Status' => $Ingredient["status"],          //How to display the ingredient
                'AltRecipe' => $Ingredient["recipe_id"],    //Which recipe to call when looking at that ingredient
                'Step' => $row[5]                           //Which step it is called in
            ];

            //Add Main, Support, Spices or Garnish
            if ($row[2] == 1) {
                array_push($Main, $Object);
            } elseif ($row[2] == 2) {
                array_push($Support, $Object);
            } elseif ($row[2] == 3) {
                array_push($Spices, $Object);
            } else {
                array_push($Garnish, $Object);
            }

            //Add ingredients to the prep list
            if ($row[6] == 1) {
                array_push($Prep, $Object);
            }
        }

        //Get the ingredients per step
        $Index = 1;
        $Done = false;
        while (!$Done) {
            $temp = array();
            $Add = false;
            foreach ($Main as $row) {
                if($row->Step == $Index){
                    array_push($temp, $row);
                    $Add = true;
                }
            }
            foreach ($Support as $row) {
                if($row->Step == $Index){
                    array_push($temp, $row);
                    $Add = true;
                }
            }
            foreach ($Spices as $row) {
                if($row->Step == $Index){
                    array_push($temp, $row);
                    $Add = true;
                }
            }
            foreach ($Garnish as $row) {
                if($row->Step == $Index){
                    array_push($temp, $row);
                    $Add = true;
                }
            }
            if ($Add) {
                array_push($Steps, $temp);
                //mysqli_data_seek($ResultSet1, 0);
                $Index = $Index + 1;
            } else {
                $Done = true;
            }
        }

        db_disconnect($connection);

        //Create results object
        $Result["Main"] = $Main;
        $Result["Support"] = $Support;
        $Result["Spices"] = $Spices;
        $Result["Garnish"] = $Garnish;
        $Result["Prep"] = $Prep;
        $Result["Percent"] = $Percent;
        $Result["Steps"] = $Steps;

        return $Result;
    }
    function EditIngredient($Object){
        foreach($Object as $row){
            echo '<tr>';
            echo '<td style="width:15%;"><input type="text" name="Quantity" value="'.$row->Quantity.'"></td>';
            echo '<td style="width:15%;"><input type="text" name="Unit" value="'.$row->Unit.'"></td>';
            echo '<td style="width:15%;">'.$row->Unit.'</td>';
            echo '<td >'.$row->Name1.'</td>';
            echo'</tr>';
        }
    }
    //Function that prints an ingredient as a table row
    function IngredientRow($Object){
        foreach ($Object as $row) {
            //Create name
            $Name = $row->Name1;
            if (!empty($row->Name2)) {
                $Name = $Name.' '.$row->Name2;
            }
            if (!empty($row->Name3)) {
                $Name = $Name.' '.$row->Name3;
            }
            //Style guide
            /*
            * 0 = Don't have (MySQL)       orange   E07A5F
            * 1 = Have (MySQL)             blue     3D405B
            * 2 = Shopping list (MySQL)    bold     Bold
            * 3 = Buildable                tan      F2CC8F
            * 4 = Substitute               green    81B29A
            */
            //Create table row for have ingredient
            if($row->Status == 1){          
                echo '<tr>';
                echo '<td style="width:15%;">'.$row->Quantity.'</td>';
                echo '<td style="width:15%;">'.$row->Unit.'</td>';
                echo '<td>'.$Name.'</td>';
                echo'</tr>';
            //Create table row for ingredient is in shopping list
            }elseif($row->Status == 2){
                echo '<tr>';
                echo '<td style="font-weight: bold; width:15%;">'.$row->Quantity.'</td>';
                echo '<td style="font-weight: bold; width:15%;">'.$row->Unit.'</td>';
                echo '<td style="font-weight: bold;">'.$Name.'</td>';
                echo'</tr>';
            //Create table row for ingredient is buildable
            }elseif($row->Status == 3){
                
                echo '<tr>';
                echo '<td style="color: #F2CC8F; font-weight: bold; width:15%;">'.$row->Quantity.'</td>';
                echo '<td style="color: #F2CC8F; font-weight: bold; width:15%;">'.$row->Unit.'</td>';
                echo '<td style="color: #F2CC8F; font-weight: bold;"><a style="color: #F2CC8F;" href="recipe.php?id='.$row->AltRecipe.'">'.$Name.'</a></td>';
                echo'</tr>';
            //Create table row for ingredient is a substitute
            }elseif($row->Status == 4){
                echo '<tr>';
                echo '<td style="color: #81B29A; width:15%;">'.$row->Quantity.'</td>';
                echo '<td style="color: #81B29A; width:15%;">'.$row->Unit.'</td>';
                echo '<td style="color: #81B29A;">'.$Name.'*</td>';
                echo'</tr>';
            //Create table row for don't have ingredient
            }else{
                echo '<tr>';
                echo '<td style="color: #E07A5F; width:15%;">'.$row->Quantity.'</td>';
                echo '<td style="color: #E07A5F; width:15%;">'.$row->Unit.'</td>';
                echo '<td style="color: #E07A5F;">'.$Name.'</td>';
                echo'</tr>';
            }
        }
    }
?>









