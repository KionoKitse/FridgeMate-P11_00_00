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
                echo '<td><a href="ingredient.php?id='.$row->Id.'">'.$Name.'</a></td>';
                echo'</tr>';
            //Create table row for ingredient is in shopping list
            }elseif($row->Status == 2){
                echo '<tr>';
                echo '<td style="font-weight: bold; width:15%;">'.$row->Quantity.'</td>';
                echo '<td style="font-weight: bold; width:15%;">'.$row->Unit.'</td>';
                echo '<td style="font-weight: bold;"><a style="font-weight: bold;" href="ingredient.php?id='.$row->Id.'">'.$Name.'</a></td>';
                echo'</tr>';
            //Create table row for ingredient is buildable
            }elseif($row->Status == 3){
                echo '<tr>';
                echo '<td style="color: #F2CC8F; font-weight: bold; width:15%;">'.$row->Quantity.'</td>';
                echo '<td style="color: #F2CC8F; font-weight: bold; width:15%;">'.$row->Unit.'</td>';
                echo '<td style="color: #F2CC8F; font-weight: bold;"><a style="color: #F2CC8F; text-decoration: underline;" href="recipe.php?id='.$row->AltRecipe.'">'.$Name.'</a></td>';
                echo'</tr>';
            //Create table row for ingredient is a substitute
            }elseif($row->Status == 4){
                echo '<tr>';
                echo '<td style="color: #81B29A; width:15%;">'.$row->Quantity.'</td>';
                echo '<td style="color: #81B29A; width:15%;">'.$row->Unit.'</td>';
                echo '<td style="color: #81B29A;"><a style="color: #81B29A;" href="ingredient.php?id='.$row->Id.'">'.$Name.'*</a></td>';
                echo'</tr>';
            //Create table row for don't have ingredient
            }else{
                echo '<tr>';
                echo '<td style="color: #E07A5F; width:15%;">'.$row->Quantity.'</td>';
                echo '<td style="color: #E07A5F; width:15%;">'.$row->Unit.'</td>';
                echo '<td style="color: #E07A5F;"><a style="color: #E07A5F;" href="ingredient.php?id='.$row->Id.'">'.$Name.'</a></td>';
                echo'</tr>';
            }
        }
    }
    //Function to do a full buildability score update on a recipe_id
    function RecipeBuildability($RecipeId, $connection, $debug){
        //Sanitize input
        $RecipeId = filter_var($RecipeId, FILTER_SANITIZE_NUMBER_INT);
        if($debug) echo $RecipeId."<br>";

        $BuildabilityScore=0;

        //Get the data on each ingredient in a recipe
        $Query1 = "SELECT ingredient.item_id, ingredient.percent, ingredient.category, pantry.status, pantry.cart, pantry.recipe_id FROM ingredient
        INNER JOIN pantry ON ingredient.item_id=pantry.item_id Where ingredient.recipe_id=".$RecipeId;
        $ResultSet1 = $connection->query($Query1);

        //For each ingredient check if we have it or there is a substitute
        while ($row = $ResultSet1->fetch_assoc()) {
            if($debug) echo ">>Item:".$row["item_id"]." Status:".$row["status"]." Cart:".$row["cart"]." Percent:".$row["percent"]."<br>";
            $Weight = 1;
            
            //Ingredient is not availible or in the cart
            if(!$row["status"] && !$row["cart"]){
                if($debug) echo ">>Ingredient not availible <br>";

                $Weight = 0;
                //Check if it is buildable
                if($row["recipe_id"]){
                    $Query1 = "SELECT percent FROM recipe WHERE recipe_id=".$row["recipe_id"];
                    $Result = $connection->query($Query1)->fetch_assoc();
                    if($debug) echo ">> >>Build recipe: ".$row["recipe_id"]." Percent:".$Result["percent"]." ->";
                    if ($Result["percent"]>90){
                        if($debug) echo "Yes<br>";
                        $Weight = 1;
                        goto CalcScore;
                    }
                    if($debug) echo "No<br>";
                }
               
                //Find if there are any substitute ingredients
                if($debug) echo ">>Checking subs<br>";
                $Query1 = "SELECT group_id FROM sets WHERE item_id=".$row["item_id"];
                $Query2 = "SELECT DISTINCT item_id FROM sets WHERE group_id IN (" . $Query1 . ") AND item_id !=".$row["item_id"];
                $Query3 = "SELECT item_id, status, cart FROM pantry WHERE item_id IN (" . $Query2 . ")";
                $ResultSet2 = $connection->query($Query3);

                //If there are substitute ingredients
                if($ResultSet2->num_rows > 0){
                    //Check any of the items in the group are available
                    while ($row1 = $ResultSet2->fetch_assoc()) {
                        if($debug) echo ">> >>Item:".$row1["item_id"]." Status:".$row1["status"]." row1:".$row["cart"]." ->";
                        if ($row1["status"] || $row1["cart"]){
                            if($debug) echo "Yes<br>";
                            $Weight = 0.5;
                            goto CalcScore;
                        }
                        if($debug) echo "No<br>";
                    } 
                }
    
            }
            
            //Calculate the score
            CalcScore:
            $Score = $row["percent"]*$Weight;
            $BuildabilityScore += $Score;
            if($debug) echo ">>".$row["percent"]."X".$Weight."=".$Score."<br>";
            if($debug) echo ">>Sum: ".$BuildabilityScore."<br><br>";
        }
        //Update
        $Query1 = "UPDATE recipe SET percent=".$BuildabilityScore." WHERE recipe_id=".$RecipeId;
        $connection->query($Query1);
        if($debug) echo ">>Final Buildability: ".$BuildabilityScore."<br><br>";

    }
    //Function to set ingredient weights when recipes change
    function IngredientWeight($connection){
        //Get all item_id
        $Query1 = "SELECT item_id FROM pantry";
        $ResultSet1 = $connection->query($Query1);
        while ($row = $ResultSet1->fetch_assoc()){
            //Get the total contribution weight from each item
            $Query2 = "SELECT SUM(percent) FROM ingredient WHERE item_id=".$row["item_id"];
            $Weight = $connection->query($Query2)->fetch_assoc();

            //Update the weight
            $Query1 = "UPDATE pantry SET weight=".$Weight." WHERE item_id=".$row["item_id"];
            $connection->query($Query1);
        }
    }

?>