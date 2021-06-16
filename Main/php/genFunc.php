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
?>