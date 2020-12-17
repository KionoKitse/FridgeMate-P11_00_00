<!--Testing PHP generate page -->
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <!-- Import font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu:wght@500&display=swap');
    </style>
    <!-- Colors: https://coolors.co/f4f1de-e07a5f-3d405b-81b29a-f2cc8f 
        Style guide
        0 = Don't have (MySQL)       orange   E07A5F
        1 = Have (MySQL)             blue     3D405B
        2 = Shopping list (MySQL)    bold     Bold
        3 = Buildable                tan      F2CC8F
        4 = Substitute               green    81B29A
    -->
    <style>
        .center {
            margin: auto;
            border: 3px solid #81B29A;
            padding: 10px;
            background: #F4F1DE;
        }

        .break {
            background: #F2CC8F;
            height: 2px;
            margin: 5px 0 10px 0;
            width: 100%;
        }

        p {
            font-size: 3.7vw;
            color: #3D405B;
        }
        th{
            font-size: 3.7vw;
            font-weight: bold;
            padding: 0.5vw;
            color: #81B29A;
        }
        td{
            font-size: 3.2vw;
            color: #3D405B;
            padding: 0.5vw;
        }
        html * {
            max-height: 999999px !important;
            font-family: 'Ubuntu', sans-serif;
        }
    </style>
</head>

<!-- Read the JSON data -->
<?php
    $JsonPath = file_get_contents('./RecipeFiles/' . $_GET['id'] . '.json');
    $JsonData = json_decode($JsonPath, true);
    //echo $JsonData->Name;
    //var_dump($JsonData);
    $Active = ParseTime($JsonData["ActiveTime"]);
    $Passive = ParseTime($JsonData["PassiveTime"]);
?>

<!-- Get MySQL information -->
<?php
    require_once './includes/dbconnect.php';
    //Get the ingredients values needed for the recipe
    $Query1 = "SELECT * FROM ingredient WHERE recipe_id = '".$_GET['id']."'";
    $ResultSet1 = $connection->query($Query1);
    //SELECT * FROM fridgemate_db.ingredient WHERE recipe_id = '5'

    //Get the ingredient information
    $Query1 = "SELECT item_id FROM ingredient WHERE recipe_id = '".$_GET['id']."'";
    $Query2 = "SELECT * FROM pantry WHERE item_id IN (" . $Query1 . ")";
    $ResultSet2 = $connection->query($Query2);
    //SELECT * FROM fridgemate_db.pantry WHERE item_id IN (SELECT item_id FROM fridgemate_db.ingredient WHERE recipe_id = '5')

    //Get the ingredient information
    $Query1 = "SELECT * FROM recipe WHERE recipe_id = '".$_GET['id']."'";
    $ResultSet3 = $connection->query($Query1);
    //SELECT * FROM fridgemate_db.recipe where RECIPE_ID = '5';

    //Get the recipe percent
    $Percent = 0;
    while ($row = $ResultSet3->fetch_row()) {
        $Percent = $row[6];
    }
    $Percent = floatval($Percent);

    //Build lists of ingredients
    $Main = array();
    $Support = array();
    $Spices = array();
    $Garnish = array();
    $Prep = array();
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
                //SELECT PERCENT FROM fridgemate_db.recipe where RECIPE_ID = '4';
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
    $Steps = array();
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
?>

<!-- Functions -->
<?php
    //Function that parses minutes to min/hr
    function ParseTime($Time){
        if ($Time < 60) {
            return $Time . 'min';
        } else {
            $Hrs = floor($Time / 60);
            $Min = $Time - 60 * $Hrs;
            return $Hrs . 'h ' . $Min . 'min';
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
?>
<?php
    //var_dump($Main);
?>

<body>
    <div class="center">
        <div id="Title">
            <p style="text-align: center; font-size: 5vw;">
                <?php
                    echo '<a style="color: #81B29A; font-weight: bold;" href="'.$JsonData["Link"].'">'.$JsonData["Name"].'</a>';
                ?>
            </p>

        </div>

        <?php
            echo '<img id="Image" src="'.$JsonData["Image"].'" style="width:100%">';
        ?>

        <table id="Stats" style="width:100%">
            <tr>
                <?php
                    echo '<th style="color: #E07A5F;">'.$JsonData["Rating"].'/5 <i style="color: #81B29A;" class="fa fa-star"></i></th>';
                    echo '<th style="color: #E07A5F;">'.$Percent.'% <i style="color: #81B29A;" class="fas fa-clipboard-check"></i></th>';
                    echo '<th style="color: #E07A5F;">'.$Active.' <i style="color: #81B29A;" class="far fa-clock"></i></i></th>';
                    echo '<th style="color: #E07A5F;">'.$Passive.' <i style="color: #81B29A;" class="fa fa-clock"></i></th>';
                    echo '<th style="color: #E07A5F;">'.$JsonData["People"].' <i style="color: #81B29A;" class="fas fa-user-astronaut"></i></th>';
                ?>
            </tr>
        </table>
        <br>

        <table id="Main" style="float: left; width: 50%; text-align: left;">
            <tr>
                <th colspan="4">Main</th>
            </tr>
            <?php
                IngredientRow($Main);
            ?>
        </table>
        <table id="Support" style="float: right; width: 50%; text-align: left;">
            <tr>
                <th colspan="4">Support</th>
            </tr>
            <?php
                IngredientRow($Support);
            ?>

        </table>

        <div style="clear: both;"><br></div>

        <table id="Spices" style="float: left; width: 50%; text-align: left;">
            <tr>
                <th colspan="4">Spices</th>
            </tr>
            <?php
                IngredientRow($Spices);
            ?>
        </table>

        <table id="Garnish" style="float: right; width: 50%; text-align: left;">
            <tr>
                <th colspan="3">Garnish</th>
            </tr>
            <?php
                IngredientRow($Garnish);
            ?>

        </table>

        <div class="break" style="clear: both;"></div>

        <div id="Notes">
            <p>
                <?php
                    echo $JsonData["Notes"];
                ?>
            </p>
        </div>

        <!-- Print Prep step -->
        <div class="break"></div> 
        <div id="Prep">
            <table style="text-align: left;">
                <tr>
                    <th colspan="3">Prep</th>
                </tr>
                <?php
                    IngredientRow($Prep);
                ?>
            </table>
            <p>
                <?php
                    echo $JsonData["Steps"][0];
                ?>
            </p>
        </div>
        <div class="break" style="clear: both;"></div>

        <!-- Print the other steps -->
        <?php
            for ($i = 1; $i < count($JsonData["Steps"]); $i++)  {
                
                echo '<div id="Step'.$i.'">';
                    echo '<table style="text-align: left;">';
                    echo '<tr><th colspan="3">Step '.$i.'</th></tr>';
                    IngredientRow($Steps[$i-1]);
                    echo '</table>';
                    echo '<p>';
                        echo $JsonData["Steps"][$i];
                    echo '</p>';
                echo '</div>';
                echo '<div class="break" style="clear: both;"></div>';
            }
        ?>
    </div>
</body>
</html>
