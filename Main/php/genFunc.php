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
    //Function to do a full buildability score update on a recipe_id
    function CalcBuildability($RecipeId, $connection){
        $BuildabilityScore=0;

        //Get the data on each ingredient in a recipe
        $Query1 = "SELECT ingredient.item_id, ingredient.percent, ingredient.category, pantry.status, pantry.cart, pantry.recipe_id FROM ingredient
        INNER JOIN pantry ON ingredient.item_id=pantry.item_id Where ingredient.recipe_id = ?";
        $stmt = $connection->prepare($Query1);
        $stmt->bind_param("i", $RecipeId);
        $stmt->execute();
        $ResultSet1 = $stmt->get_result();

        //For each ingredient check if we have it or there is a substitute
        while ($row = $ResultSet1->fetch_assoc()) {
            $Weight = 1;
            //echo "item_id: ".$row["item_id"]."<br>";
            //echo "percent: ".$row["percent"]."<br>";
            //echo "category: ".$row["category"]."<br>";
            //echo "status: ".$row["status"]."<br>";
            //echo "cart: ".$row["cart"]."<br>";
            //echo "recipe_id: ".$row["recipe_id"]."<br>";
            
            //Ingredient is not availible or in the cart
            if(!$row["status"] && !$row["cart"]){
                //echo "Ingredient not availible <br>";
                $Weight = 0;
                //Check if it is buildable
                if($row["recipe_id"]){
                    //echo "Possible to build - ";
                    $Query1 = "SELECT percent FROM recipe WHERE recipe_id = ?";
                    $stmt = $connection->prepare($Query1);
                    $stmt->bind_param("i", $row["recipe_id"]);
                    $stmt->execute();
                    $Result = $stmt->get_result()->fetch_assoc();
                    //echo $Result["percent"]." - ";
                    //Ingredient is buildable
                    if ($Result["percent"]>90){
                        //echo "Use build <br>";
                        $Weight = 1;
                        goto CalcScore;
                    }
                    //echo "Build not high enough <br>";
                }
               
    
                //Find if there are any substitute ingredients
                //echo "Checking subs<br>";
                $Query1 = "SELECT group_id FROM sets WHERE item_id = ?";
                $Query2 = "SELECT DISTINCT item_id FROM sets WHERE group_id IN (" . $Query1 . ") AND item_id != ?";
                $Query3 = "SELECT item_id, status, cart FROM pantry WHERE item_id IN (" . $Query2 . ")";
                $stmt = $connection->prepare($Query3);
                $stmt->bind_param("ii", $row["item_id"], $row["item_id"]);
                $stmt->execute();
                $ResultSet2 = $stmt->get_result();
    
                //If there are substitute ingredients
                if($ResultSet2->num_rows > 0){
                //echo "Possible to sub <br>";
                //Check any of the items in the group are available
                    while ($row1 = $ResultSet2->fetch_assoc()) {
                        //echo ">>".$row1["item_id"]." ".$row1["status"]." ".$row1["cart"]."<br>";
                        if ($row1["status"] || $row1["cart"]){
                            //echo "Sub availible - exit<br>";
                            $Weight = 0.5;
                            goto CalcScore;
                        }
                    } 
                }
    
            }
            
            
            //Calculate the score
            CalcScore:
            //echo "Weight: ".$Weight."<br>";
    
            $Score = $row["percent"]*$Weight;
            $BuildabilityScore += $Score;
            //echo "Score: ".$Score."<br>";
            //echo "BuildabilityScore: ".$BuildabilityScore."<br>";
            //echo "<br>";

            $Query1 = "UPDATE recipe SET percent=? WHERE recipe_id=?";
            $stmt = $connection->prepare($Query1);
            $stmt->bind_param("si", $BuildabilityScore, $RecipeId);
            $stmt->execute();

            //exit
            $stmt->close();
        }

    }
?>