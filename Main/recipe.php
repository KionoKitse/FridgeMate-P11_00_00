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
    include 'php/recipefunc.php';

    //Get the requested id
    $ReqId="";
    if (ctype_digit($_GET['id'])) {
        $ReqId = $_GET['id'];
    }

    //Read the JSON data
    $JsonData = ReadJSON($ReqId);

    //Parse time
    $Active = ParseTime($JsonData["ActiveTime"]);
    $Passive = ParseTime($JsonData["PassiveTime"]);
    
    //Get MySQL and parse
    $MySQL = GetMySQL($ReqId);
    $Main = $MySQL["Main"];
    $Support = $MySQL["Support"];
    $Spices = $MySQL["Spices"];
    $Garnish = $MySQL["Garnish"];
    $Prep = $MySQL["Prep"];
    $Percent = $MySQL["Percent"];
    $Steps = $MySQL["Steps"];
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
    <script>
        //mainstyle.js - Set the center element width based on window size
        SetCenterWidth();
    </script>
</body>
</html>
