<?php
    //Function that gets the MySQL data
    function GetEditMySQL($Id){
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

            //Create new ingredient object
            $Object = (object) [
                'Quantity' => $row[3],
                'Unit' => $row[4],
                'Name1' => $Ingredient["name1"],
                'Name2' => $Ingredient["name2"],
                'Name3' => $Ingredient["name3"],
                'Step' => $row[5],
                'Prep' => $row[6] 
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
    function FillRow($Object,$Type){
        $Id = 1;

        foreach($Object as $row){
            //Create unique id
            $RowId = $Type.$Id;

            //Create rows
            echo '<tr id="'.$RowId.'">';
                echo '<td style="width: 10%;"><input type="text" value="'.$row->Quantity.'" id="'.$RowId.'Quantity"></td>';
                echo '<td style="width: 15%;"><input type="text" value="'.$row->Unit.'" id="'.$RowId.'Unit" list="Unit"/></td>';
                echo '<td><input onchange="GetName2(\''.$RowId.'\')" type="text" value="'.$row->Name1.'" id="'.$RowId.'Name1" list="Name1All"/></td>';
                echo '<td><input onchange="GetName3(\''.$RowId.'\')" type="text" value="'.$row->Name2.'" id="'.$RowId.'Name2" list="Name2'.$RowId.'"/></td>';
                echo '<td><input type="text" value="'.$row->Name3.'" id="'.$RowId.'Name3" list="Name3'.$RowId.'"/></td>';
                echo '<td>';
                    echo '<i onclick="RemoveElement(\''.$RowId.'\')" style="color: #F2CC8F; font-size: 5vw" class="far fa-minus-square"></i>';
                echo '</td>';
            echo '</tr>';
            $Id++;
        }
    }
    function IdAndName($Object,$Type){
        $Result=array();
        $Id = 1;
        foreach($Object as $row){
            //Create unique id
            $RowId = $Type.$Id;
            $RowName = $row->Quantity.' '.$row->Unit.' '.$row->Name1.' '.$row->Name2.' '.$row->Name3;
            $Row = array($RowId, $RowName);
            array_push($Result,$Row);
            $Id++;
        }
        return $Result;
    }
    /*
    function PreSelectedDropDown($Object,$Type,$Options){
        $Id = 1;
        foreach($Object as $row){
            //Create unique id
            $RowId = $Type.$Id;
            //Create row name
            $RowName = $row->Quantity.' '.$row->Unit.' '.$row->Name1.' '.$row->Name2.' '.$row->Name3;
            //Create a row
            echo '<tr id="'.$RowId.'">';
                echo '<td>';
                    echo '<select style="width: 100%;" id="Select'.$RowId.'">';
                        echo '<option value=""></option>';
                        foreach($Options as $row2){
                            if($RowName == $row2[1]){
                                echo '<option selected="selected" value="'.$row2[0].'">'.$row2[1].'</option>';
                            }else{
                                echo '<option value="'.$row2[0].'">'.$row2[1].'</option>';
                            }
                        }
                    echo '</select>';
                echo '</td>';
                echo '<td>';
                    echo '<i onclick="RemoveElement(\''.$RowId.'\')" style="color: #F2CC8F; font-size: 5vw;" class="far fa-minus-square"></i>';
                echo '</td>';
            echo '</tr>';
            $Id++;
        }
    }
    */
    function PreSelectedDropDown($Object,$Step,$Options){
        $Id = 1;
        foreach($Object as $row){
            if (($row->Step == $Step) || (($Step == '0')&&($row->Prep == '1')))
            {
                //Create unique id
                $RowId = "Step".$Step."Item".$Id;
                //Create row name
                $RowName = $row->Quantity.' '.$row->Unit.' '.$row->Name1.' '.$row->Name2.' '.$row->Name3;
                //Create a row
                echo '<tr id="'.$RowId.'">';
                    echo '<td>';
                        echo '<select id="Select'.$RowId.'">';
                            echo '<option value=""></option>';
                            foreach($Options as $row2){
                                if($RowName == $row2[1]){
                                    echo '<option selected="selected" value="'.$row2[0].'">'.$row2[1].'</option>';
                                }else{
                                    echo '<option value="'.$row2[0].'">'.$row2[1].'</option>';
                                }
                            }
                        echo '</select>';
                    echo '</td>';
                    echo '<td>';
                        echo '<i onclick="RemoveElement(\''.$RowId.'\')" style="color: #F2CC8F; font-size: 5vw;" class="far fa-minus-square"></i>';
                    echo '</td>';
                echo '</tr>';
                $Id++;
            }
        }
    }
?>
