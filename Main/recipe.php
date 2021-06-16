<!--Testing PHP generate page -->
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
    <script src="js/mainstyle.js"></script>
</head>

<!-- Get the required information to render the page -->
<?php
    
    //Include recipe functions
    include 'php/genFunc.php';

    //Get the requested id
    $Id="";
    if (ctype_digit($_GET['id'])) {
        $Id = $_GET['id'];
    }    

    //Read the JSON data
    $JsonData = ReadJSON($Id);

    //Parse time
    $Active = ParseTime($JsonData["ActiveTime"]);
    $Passive = ParseTime($JsonData["PassiveTime"]);
    
    //Get MySQL and parse
    $Main = array();
    $Support = array();
    $Spices = array();
    $Garnish = array();
    $Prep = array();
    $Percent = 0;
    $Steps = array();
    $RecipeTags = array();

    require_once 'dbconnect.php';
    //Get the ingredients values needed for the recipe
    $Query1 = "SELECT * FROM ingredient WHERE recipe_id = ?";
    $stmt = $connection->prepare($Query1);
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $ResultSet1 = $stmt->get_result();

    //Get the ingredient information
    $Query1 = "SELECT item_id FROM ingredient WHERE recipe_id = ?";
    $Query2 = "SELECT * FROM pantry WHERE item_id IN (" . $Query1 . ")";
    $stmt = $connection->prepare($Query2);
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $ResultSet2 = $stmt->get_result();

    //Get the ingredient information
    $Query1 = "SELECT * FROM recipe WHERE recipe_id = ?";
    $stmt = $connection->prepare($Query1);
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $ResultSet3 = $stmt->get_result();

    //Get the recipe tags
    $Query1 = "SELECT tag FROM tags WHERE recipe_id = ?";
    $stmt = $connection->prepare($Query1);
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $ResultSet4 = $stmt->get_result();
    while ($row = $ResultSet4->fetch_row()) {
        array_push($RecipeTags,$row[0]);
    }

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
    
    $stmt->close();
    db_disconnect($connection);
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
            <table style="text-align: left;">
                <tr>
                    <th>Notes</th>
                </tr>
                <tr>
                    <td>
                        <?php
                            echo $JsonData["Notes"];
                        ?>
                    </td>
                </tr>
            </table>
        </div>

        <div class="break"></div> 
        <div id="Tags">
            <table style="text-align: left; width: 100%;" id="TagTable">
                <tr>
                    <th colspan="2">Tags</th>
                </tr>
                <?php
                    //Print the recipe tags     
                    for($i=0; $i<count($RecipeTags)-1; $i+=2){
                        echo '<tr>';
                            echo '<td>'.$RecipeTags[$i].'</td>';
                            echo '<td>'.$RecipeTags[$i+1].'</td>';
                        echo '</tr>';
                    }
                    //Print the last recipe tag if needed
                    if(($i+1)==count($RecipeTags)){
                        echo '<tr>';
                            echo '<td>'.$RecipeTags[$i].'</td>';
                            echo '<td></td>';
                        echo '</tr>';
                    }
                ?>
            </table>
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
?>
