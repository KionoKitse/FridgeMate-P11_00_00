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
        li{
            font-size: 3vw;
            font-weight: normal;
        }
        html * {
            max-height: 999999px !important;
            color: #3E87BC;
        }


    </style>
    <!-- Read the JSON data -->
    <?php
        $JsonPath = file_get_contents('./RecipeFiles/'.$_GET['id'].'.json');
        $JsonData = json_decode($JsonPath, true);
        //echo $JsonData->Name;
        //var_dump($JsonData);

        foreach($JsonData["Steps"] as $Step){
            echo $Step.'<br>';
        }
        
        function ParseTime($Time){
            if($Time < 60){
                return $Time.'min';
            }
            else{
                $Hrs = floor($Time/60);
                $Min = $Time - 60 * $Hrs;
                return $Hrs.'h '.$Min.'min';
            }
        }
        $Active = ParseTime($JsonData["ActiveTime"]);
        $Passive = ParseTime($JsonData["PassiveTime"]);
    ?> 
    <!-- MYSQL query -->
    <?php
        require_once('./includes/dbconnect.php');
        //Get the ingredients values needed for the recipe
        $Query1 = "SELECT * FROM ingredient WHERE recipe_id = '5'";
        $ResultSet1 = $connection->query($Query1);
        //SELECT * FROM fridgemate_db.ingredient WHERE recipe_id = '5'
        
        //Get the ingredient information
        $Query1 = "SELECT item_id FROM ingredient WHERE recipe_id = '5'";
        $Query2 = "SELECT * FROM pantry WHERE item_id IN (".$Query1.")";
        $ResultSet2 = $connection->query($Query2);
        //SELECT * FROM fridgemate_db.pantry WHERE item_id IN (SELECT item_id FROM fridgemate_db.ingredient WHERE recipe_id = '5')

        function GetIngredient($Id,$Result){
            while($row = mysqli_fetch_assoc($Result)){
                if($row["item_id"] == $Id)
                {
                    //Set the pointer back to the beginning and send results
                    mysqli_data_seek($Result, 0);
                    return $row;
                }
            }
        }
        //var_dump($row);

        //Build lists of ingredients
        $Main = array();
        $Support = array();
        $Spices = array();
        $Garnish = array();
        $Steps = array();

        //Get the max number of steps
        $StepCt = 0;
        while ($row = $ResultSet1 -> fetch_row()) {
            if ($row[5] > $StepCt){
                $StepCt = $row[5];
            }
        }
        var_dump($StepCt);

        //echo $Hello;
        


        while ($row = $ResultSet1 -> fetch_row()) {
            //Get the ingredient object
            $Ingredient = GetIngredient($row[1],$ResultSet2);
            //Create new ingredient object
            $Object = (object) [
                'Quantity'=>$row[3], 
                'Unit'=>$row[4], 
                'Name1'=>$Ingredient["name1"],
                'Name2'=>$Ingredient["name2"],
                'Name3'=>$Ingredient["name3"],
                'Status'=>$Ingredient["status"],
                'AltRecipe'=>$Ingredient["recipe_id"]
            ];
            
            //Add Main, Support, Spices or Garnish
            if($row[2] == 1){
                array_push($Main, $Object);
            }elseif($row[2] == 2){
                array_push($Support, $Object);
            }elseif($row[2] == 3){
                array_push($Spices, $Object);
            }else{
                array_push($Garnish, $Object);
            }
/*
            //Check if ingredient is a prep ingredient
            if($row[6] == 1){
                //Add to steps
                if ($Steps[0]==null){
                    echo '<br>hi<br>';
                }

            }
*/
        }

        var_dump($ResultSet1[5]);


        

        /*
        while($row = mysqli_fetch_assoc($ResultSet2)){
            printf ("%s %s <br>", $row[0], $row[1]);
        }
           mysqli_data_seek($ResultSet2, 0);
           echo "<br>next<br>";
           while ($row = $ResultSet2 -> fetch_row()) {
            printf ("%s %s <br>", $row[0], $row[1]);
        }
        */


        /*
        if ($ResultSet2) {
            while ($row = $ResultSet2 -> fetch_row()) {
                printf ("%s %s <br>", $row[0], $row[1]);
            }
            //$ResultSet2 -> free_result();
        }
        echo "<br>next<br>";
        if ($ResultSet2) {
            while ($row = $ResultSet2 -> fetch_row()) {
                printf ("%s %s <br>", $row[0], $row[1]);
            }
            $ResultSet2 -> free_result();
        }
        */
        db_disconnect($connection);
    ?>
</head>

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
                    //Need MYSQL here
                    echo '<th>10% <i class="fas fa-clipboard-check"></i></th>';
                    echo '<th>'.$Active.' <i class="far fa-clock"></i></i></th>';
                    echo '<th>'.$Passive.' <i class="fa fa-clock"></i></th>';
                    echo '<th>'.$JsonData["People"].' <i class="fas fa-user-astronaut"></i></th>';
                ?>
            </tr>
        </table>
        <br>

        <table id="Main" style="float: left; text-align: left;">
            <tr>
                <th colspan="4">Main</th>
            </tr>
            <tr>
                <th>2</th>
                <th>P</th>
                <th>Bread</th>
            </tr>
            <tr>
                <th>3</th>
                <th>oz</th>
                <th>Cheese</th>
            </tr>
        </table>

        <table id="Support" style="float: right; width: 50%; text-align: left;">
            <tr>
                <th colspan="4">Support</th>
            </tr>
            <tr>
                <th>1</th>
                <th>oz</th>
                <th>Spinach</th>
            </tr>
            <tr>
                <th>2</th>
                <th>Tb</th>
                <th>Butter</th>
            </tr>
        </table>

        <div style="clear: both;"><br></div>

        <table id="Spices" style="float: left; text-align: left;">
            <tr>
                <th colspan="4">Spices</th>
            </tr>
            <tr>
                <th>0.25</th>
                <th>Tsp</th>
                <th>Garlic powder</th>
            </tr>
            <tr>
                <th>0.5</th>
                <th>Tsp</th>
                <th>Oregano</th>
            </tr>
        </table>

        <table id="Garnish" style="float: right; width: 50%; text-align: left;">
            <tr>
                <th colspan="3">Garnish</th>
            </tr>
            <tr>
                <th>*</th>
                <th>*</th>
                <th>Fresh parsley</th>
            </tr>

        </table>

        <div class="break" style="clear: both;"></div>

        <div id="Notes">
            <p>Sandwich is good but could use some more cheese.</p>
        </div>

        <div class="break"></div>

        <div id="Prep">
            <table style="text-align: left;">
                <tr>
                    <th colspan="3">Prep</th>
                </tr>
                <tr>
                    <th>*</th>
                    <th>*</th>
                    <th>Fresh parsley</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>oz</th>
                    <th>Spinach</th>
                </tr>
            </table>
            <p>
                Group: 1<br>
                * Chop fresh parsley<br>
                <br>
                Group: 2<br>
                * Add galic<br>
                * Add oregano<br>
                <br>
                Group: 3<br>
                * Wash and cut spinach
            </p>
        </div>
        <div class="break" style="clear: both;"></div>

        <div id="Step1">
            <table style="text-align: left;">
                <tr>
                    <th colspan="3">Step 1</th>
                </tr>
                <tr>
                    <th>2</th>
                    <th>P</th>
                    <th>Bread</th>
                </tr>
                <tr>
                    <th>2</th>
                    <th>Tb</th>
                    <th>Butter</th>
                </tr>
            </table>
            <p>
                Heat skillet to medium high.
                Add butter and sliced bread.
                Toast on one side.
            </p>
        </div>
        <div class="break" style="clear: both;"></div>

        <div id="Step2">
            <table style="text-align: left;">
                <tr>
                    <th colspan="3">Step 2</th>
                </tr>
                <tr>
                    <th>3</th>
                    <th>oz</th>
                    <th>Cheese</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>oz</th>
                    <th>Spinach</th>
                </tr>
                <tr>
                    <th>0.25</th>
                    <th>Tsp</th>
                    <th>Garlic powder</th>
                </tr>
                <tr>
                    <th>0.5</th>
                    <th>Tsp</th>
                    <th>Oregano</th>
                </tr>
                <tr>
                    <th>*</th>
                    <th>*</th>
                    <th>Fresh parsley</th>
                </tr>
            </table>
            <p>
                Once the bread is toasted.
                Remove from skillet and add a little more butter then put untoasted side down.
                Put cheese ans skices attop the toasted bread.
                Close sandwich and grill until finished.
            </p>
        </div>
    </div>

 <?php
    var_dump($JsonData);
 ?>


</body>

<!--Load content into page-->
<!--
<script>
    //Update information on page
    UpdateTitleContent()
    UpdateStats()

    //Update the title, link and image
    function UpdateTitleContent() {
        var TitleString = '<p style="text-align: center; font-size: 5vw;"><a href="' + data.Link + '">' + data.Name + '</a></p>'
        document.getElementById('Title').innerHTML = TitleString
        document.getElementById("Image").src = data.Image
    }
    //Update basic stats content and notes
    function UpdateStats() {
        var StatsString =
            '<tr>' +
            '<th>' + data.Rating + '/5 <i class="fa fa-star"></i></th>' +
            '<th>100% <i class="fas fa-clipboard-check"></i></th>' +
            '<th>' + Min2Time(data.ActiveTime) + ' <i class="far fa-clock"></i></i></th>' +
            '<th>' + Min2Time(data.PassiveTime) + ' <i class="fa fa-clock"></i></th>' +
            '<th>' + data.People + ' <i class="fas fa-user-astronaut"></i></th>' +
            '</tr>'
        document.getElementById('Stats').innerHTML = StatsString
        document.getElementById('Notes').innerHTML = '<p>' + data.Notes + '</p>'
    }
    //Function to convert min to h/min string
    function Min2Time(Mins) {
        if (Mins < 60) {
            return Mins.toString()
        }
        else {
            var Hrs = Math.floor(Mins / 60)
            var min = Mins - 60 * Hrs
            return Hrs + 'h' + min + 'min'
        }
    }


</script>
-->
</html>
