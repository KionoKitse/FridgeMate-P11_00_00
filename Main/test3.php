<!--Testing PHP generate page -->
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .center {
            margin: auto;
            border: 3px solid #73AD21;
            padding: 10px;
        }

        .break {
            background: #3E87BC;
            height: 2px;
            margin: 5px 0 10px 0;
            width: 100%;
        }

        p {
            font-size: 3vw;
            font-weight: normal;
        }
        th{
            font-size: 3vw;
            font-weight: normal;
            padding: 0.5vw;
        }
        td{
            font-size: 3vw;
            font-weight: normal;
            padding: 0.5vw;
        }
        li{
            font-size: 3vw;
            font-weight: normal;
        }
        html * {
            max-height: 999999px !important;
            color: #3E87BC;
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
    $Query1 = "SELECT * FROM ingredient WHERE recipe_id = '5'";
    $ResultSet1 = $connection->query($Query1);
    //SELECT * FROM fridgemate_db.ingredient WHERE recipe_id = '5'

    //Get the ingredient information
    $Query1 = "SELECT item_id FROM ingredient WHERE recipe_id = '5'";
    $Query2 = "SELECT * FROM pantry WHERE item_id IN (" . $Query1 . ")";
    $ResultSet2 = $connection->query($Query2);
    //SELECT * FROM fridgemate_db.pantry WHERE item_id IN (SELECT item_id FROM fridgemate_db.ingredient WHERE recipe_id = '5')

    //Get the ingredient information
    $Query1 = "SELECT * FROM recipe WHERE recipe_id = '5'";
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
        //Create new ingredient object
        $Object = (object) [
            'Quantity' => $row[3],
            'Unit' => $row[4],
            'Name1' => $Ingredient["name1"],
            'Name2' => $Ingredient["name2"],
            'Name3' => $Ingredient["name3"],
            'Status' => $Ingredient["status"],
            'AltRecipe' => $Ingredient["recipe_id"],
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

    //Reset the pointer
    mysqli_data_seek($ResultSet1, 0);

    //Get the ingredients per step
    $Steps = array();
    $Index = 1;
    $Done = false;
    while (!$Done) {
        $temp = array();
        $Add = false;
        while ($row = $ResultSet1->fetch_row()) {
            if ($row[5] == $Index) {
                $Ingredient = GetIngredient($row[1], $ResultSet2);
                $Object = (object) [
                    'Quantity' => $row[3],
                    'Unit' => $row[4],
                    'Name1' => $Ingredient["name1"],
                    'Name2' => $Ingredient["name2"],
                    'Name3' => $Ingredient["name3"],
                    'Status' => $Ingredient["status"],
                    'AltRecipe' => $Ingredient["recipe_id"],
                ];
                array_push($temp, $Object);
                $Add = true;
            }
        }
        if ($Add) {
            array_push($Steps, $temp);
            mysqli_data_seek($ResultSet1, 0);
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
    function ParseTime($Time)
    {
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
            //Create table row style="float: left; text-align: left;"
            echo '<tr><td style="width:15%;">'.$row->Quantity.'</td><td style="width:15%;">'.$row->Unit.'</td><td>'.$Name.'</td></tr>';
        
            //echo '<tr><td>'.$row->Quantity.'</td><td>'.$row->Unit.'</td><td>'.$Name.'</td></tr>';
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


<body>
    <div class="center">
        <div id="Title">
            <p style="text-align: center; font-size: 5vw;">
                <?php
                    echo '<a href="'.$JsonData["Link"].'">'.$JsonData["Name"].'</a>';
                ?>
            </p>

        </div>

        <?php
            echo '<img id="Image" src="'.$JsonData["Image"].'" style="width:100%">';
        ?>

        <table id="Stats" style="width:100%">
            <tr>
                <?php
                    echo '<th>'.$JsonData["Rating"].'/5 <i class="fa fa-star"></i></th>';
                    echo '<th>'.$Percent.'% <i class="fas fa-clipboard-check"></i></th>';
                    echo '<th>'.$Active.' <i class="far fa-clock"></i></i></th>';
                    echo '<th>'.$Passive.' <i class="fa fa-clock"></i></th>';
                    echo '<th>'.$JsonData["People"].' <i class="fas fa-user-astronaut"></i></th>';
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
