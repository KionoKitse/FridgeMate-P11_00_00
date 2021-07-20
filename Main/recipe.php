<!--Testing PHP generate page -->
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
    <script src="js/mainstyle.js"></script>
</head>

<style>
    .bttnYellow {
        background-color: #F2CC8F;
        width:100%;
        border-radius: 5px; 
        border: 2px solid #FFE6A9; 
    }
</style>

<!-- Get the required information to render the page -->
<?php
    
    //Include recipe functions
    include 'php/genFunc.php';

    //Get the requested id
    $RecipeId="";
    if (ctype_digit($_GET['id'])) {
        $RecipeId = $_GET['id'];
    }    

    //Read the JSON data
    $JsonData = ReadJSON($RecipeId);

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
    $stmt->bind_param("i", $RecipeId);
    $stmt->execute();
    $ResultSet1 = $stmt->get_result();

    //Get the ingredient information
    $Query1 = "SELECT item_id FROM ingredient WHERE recipe_id = ?";
    $Query2 = "SELECT item_id, name1, name2, name3, recipe_id, status, cart FROM pantry WHERE item_id IN (" . $Query1 . ")";
    $stmt = $connection->prepare($Query2);
    $stmt->bind_param("i", $RecipeId);
    $stmt->execute();
    $ResultSet2 = $stmt->get_result();

    //Get the percent information
    $Query1 = "SELECT percent FROM recipe WHERE recipe_id = ?";
    $stmt = $connection->prepare($Query1);
    $stmt->bind_param("i", $RecipeId);
    $stmt->execute();
    $Percent = $stmt->get_result()->fetch_assoc()["percent"];
    $Percent = floatval($Percent);

    //Get the recipe tags
    $Query1 = "SELECT tag FROM tags WHERE recipe_id = ?";
    $stmt = $connection->prepare($Query1);
    $stmt->bind_param("i", $RecipeId);
    $stmt->execute();
    $ResultSet4 = $stmt->get_result();
    while ($row = $ResultSet4->fetch_assoc()) {
        array_push($RecipeTags,$row["tag"]);
    }

    //Build lists of ingredients
    while ($row = $ResultSet1->fetch_assoc()) {
        //Get the ingredient object
        $Ingredient = GetIngredient($row["item_id"], $ResultSet2);
        
        /*
        * Status guide (in priority)
        * 1 = Have (MySQL)
        * 2 = Shopping list (MySQL)
        * 3 = Buildable
        * 4 = Substitute
        * 0 = Don't have (MySQL)
        */

        //Status: Have
        if ($Ingredient["status"]) {
            goto StatusSet;
        }
        //Status: Cart
        if ($Ingredient["cart"]){
            $Ingredient["status"] = 2;
            goto StatusSet;
        }
        //Status: Buildable
        if ($Ingredient["recipe_id"]>0){
            $Query1 = "SELECT percent FROM recipe WHERE recipe_id = ".$Ingredient["recipe_id"];
            $PercentTemp = $connection->query($Query1)->fetch_assoc()["percent"];
            if($PercentTemp>90){
                $Ingredient["status"] = 3;
                goto StatusSet;
            }
        }
        //Status: Substitute
        $Query1 = "SELECT group_id FROM sets WHERE item_id=".$Ingredient["item_id"];
        $Query2 = "SELECT DISTINCT item_id FROM sets WHERE group_id IN (" . $Query1 . ") AND item_id !=".$Ingredient["item_id"];
        $Query3 = "SELECT item_id, name1, name2, name3, recipe_id, status, cart FROM pantry WHERE item_id IN (" . $Query2 . ")";
        $ResultSet3 = $connection->query($Query3);

        //If there are substitute ingredients
        if($ResultSet3->num_rows > 0){
            //Check any of the items in the group are available
            while ($row1 = $ResultSet3->fetch_assoc()) {
                if ($row1["status"] || $row1["cart"]){
                    $Ingredient["status"] = 4;
                    $Ingredient["name1"] = $row1["name1"];
                    $Ingredient["name2"] = $row1["name2"];
                    $Ingredient["name3"] = $row1["name3"];
                    goto StatusSet;
                }
            } 
        }

        StatusSet:

        //Create new ingredient object
        $Object = (object) [
            'Id' => $Ingredient["item_id"],
            'Quantity' => $row["quantity"],
            'Unit' => $row["unit"],
            'Name1' => $Ingredient["name1"],
            'Name2' => $Ingredient["name2"],
            'Name3' => $Ingredient["name3"],
            'Status' => $Ingredient["status"],          //How to display the ingredient
            'AltRecipe' => $Ingredient["recipe_id"],    //Which recipe to call when looking at that ingredient
            'Step' => $row["step"]                      //Which step it is called in
        ];

        //Add Main, Support, Spices or Garnish
        if ($row["category"] == 1) {
            array_push($Main, $Object);
        } elseif ($row["category"] == 2) {
            array_push($Support, $Object);
        } elseif ($row["category"] == 3) {
            array_push($Spices, $Object);
        } else {
            array_push($Garnish, $Object);
        }

        //Add ingredients to the prep list
        if ($row["prep"] == 1) {
            array_push($Prep, $Object);
        }
    }

    //Get the number of steps
    $Query1 = "SELECT MAX(step) FROM ingredient WHERE recipe_id=?";
    $stmt = $connection->prepare($Query1);
    $stmt->bind_param("i", $RecipeId);
    $stmt->execute();
    $StepCt = $stmt->get_result()->fetch_assoc()["MAX(step)"];

    //Create array of ingredients in each step
    for ($Step = 1; $Step <= $StepCt; $Step++) {
        $temp = array();
        foreach ($Main as $row) {
            if($row->Step == $Step) array_push($temp, $row);
        }
        foreach ($Support as $row) {
            if($row->Step == $Step) array_push($temp, $row);
        }
        foreach ($Spices as $row) {
            if($row->Step == $Step) array_push($temp, $row);
        }
        foreach ($Garnish as $row) {
            if($row->Step == $Step) array_push($temp, $row);
        }
        array_push($Steps, $temp);
    }
    
    $stmt->close();
    db_disconnect($connection);
?>

<body>
    <span id="error"></span>
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
                    echo '<th style="color: #E07A5F;">'.$Percent.'% <i onclick="UpdateBuildability(\''.$RecipeId.'\')" style="color: #81B29A;" class="fas fa-clipboard-check"></i></th>';
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

        <!-- Edit recipe button -->
        <button class="bttnYellow" onclick="location.href='editrecipe.php?id=<?php echo $RecipeId?>'">Edit Recipe</button>
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
<script>
    function UpdateBuildability(id){
        //Run the request
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var Result = this.responseText;
                //Refresh page if no errors
                if (Result.length < 5){
                    location.reload(true);
                }else{
                    document.getElementById("error").innerHTML = Result;
                }
            }
        };
        xmlhttp.open("GET", "php/updateBuildability.php?id=" + id, true);
        xmlhttp.send();
        }
</script>
