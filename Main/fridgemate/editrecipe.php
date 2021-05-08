<!--Testing PHP generate page -->
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
</head>

<!-- JavaScript functions for DOM manipulations -->
<script src="js/editrecipe.js"></script>

<!-- Check if it's a modification or a new recipe -->
<?php
    //Include recipe functions
    include 'php/recipefunc.php';
    include 'php/editfunc.php';

    //Create variables for recipe
    $Main = array();
    $Support = array();
    $Spices = array();
    $Garnish = array();
    $Prep = array();
    $Percent = 0;
    $Steps = array();
    $JsonData["Recipe"] = NULL;
    $JsonData["Name"] = "Recipe Name";
    $JsonData["Link"] = "Link";
    $JsonData["Image"] = "Image";
    $JsonData["People"] = 0;
    $JsonData["ActiveTime"] = 0;
    $JsonData["PassiveTime"] = 0;
    $JsonData["Rating"] = 0;
    $JsonData["ActiveTime"] = 0;
    $JsonData["Notes"] = ":#";
    $JsonData["Steps"] = array();

    //Add data to variables if editing a recipe
    if(isset($_GET['id']) && !empty($_GET['id'])){      
        //Get the requested id
        $ReqId="";
        if (ctype_digit($_GET['id'])) {
            $ReqId = $_GET['id'];
        }
    
        //Get JSON data
        $JsonData = ReadJSON($ReqId);
        //Get MySQL
        $MySQL = GetEditMySQL($ReqId);
    
        //Parse data into variables
        $Main = $MySQL["Main"];
        $Support = $MySQL["Support"];
        $Spices = $MySQL["Spices"];
        $Garnish = $MySQL["Garnish"];
        $Prep = $MySQL["Prep"];
        $Percent = $MySQL["Percent"];
        $Steps = $MySQL["Steps"];
    }
?>

<body>
    <!-- Lists used for the datalatalists -->
    <div id="Lists">
        <!-- Create Ids for each category -->
        <?php
        
            //Create ids for the four categories
            echo '<input type="hidden" id="MainId" value="'.count($Main).'">';
            echo '<input type="hidden" id="SupportId" value="'.count($Support).'">';
            echo '<input type="hidden" id="SpicesId" value="'.count($Spices).'">';
            echo '<input type="hidden" id="GarnishId" value="'.count($Garnish).'">';
            //Merge the categories
            $AllIngredinets = $Main;
            $AllIngredinets = array_merge($AllIngredinets,$Support);
            $AllIngredinets = array_merge($AllIngredinets,$Spices);
            $AllIngredinets = array_merge($AllIngredinets,$Garnish);
            //Get max number of steps
            $MaxCt = 0;
            foreach($AllIngredinets as $row){
                if($row->Step > $MaxCt){
                    $MaxCt = $row->Step;
                }
            }
            //Create array to hold the id count for each step
            $StepIds = array_fill(0, $MaxCt+1, 0);
            foreach($AllIngredinets as $row){
                $StepIds[$row->Step]++;
                if($row->Prep){
                    $StepIds[0]++;
                }
            }
            //Create JSON object to be used by JavaScript
            $temp = json_encode($StepIds);
            echo '<input type="hidden" id="StepIds" value=\'{"Ids":'.$temp.'}\'>';      
        ?>

        <!-- Create units datalist -->
        <datalist id="Unit">
            <option value="ts">
            <option value="Tb">
            <option value="C">
            <option value="g">
            <option value="Pc">
            <option value="pch">
            <option value="*">
        </datalist>
    </div>
    <div class="center">
        <div id="Title">
            <p style="text-align: center; font-size: 5vw;">
                <?php
                    echo '<input type="text"  id="Name" value="'.$JsonData["Name"].'"><br>';
                    echo '<input type="text" id="Link" value="'.$JsonData["Link"].'"><br>';
                    echo '<input type="text" id="Image" value="'.$JsonData["Image"].'">';
                ?>
            </p>
        </div>

        <table id="Stats" style="width:100%">
            <tr>
                <?php
                    echo '<th style="color: #E07A5F;">';
                    echo '<input type="text" size="1" id="Rating" value="'.$JsonData["Rating"].'">';
                    echo ' <i style="color: #81B29A;" class="fa fa-star"></i></th>';
                    
                    echo '<th style="color: #E07A5F;">';
                    echo '<input type="text" size="1" id="ActiveTime" value="'.$JsonData["ActiveTime"].'">';
                    echo ' <i style="color: #81B29A;" class="far fa-clock"></i></i></th>';

                    echo '<th style="color: #E07A5F;">';
                    echo '<input type="text" size="1" id="PassiveTime" value="'.$JsonData["PassiveTime"].'">';
                    echo ' <i style="color: #81B29A;" class="fa fa-clock"></i></th>';

                    echo '<th style="color: #E07A5F;">';
                    echo '<input type="text" size="1" id="People" value="'.$JsonData["People"].'">';
                    echo ' <i style="color: #81B29A;" class="fas fa-user-astronaut"></i></th>';
                ?>
            </tr>
        </table>
        <br>

        <table id="Main">
            <tr>
                <th colspan="4" style="text-align: left;">
                    Main 
                    <i onclick="AddIngredient('Main')" style="color: #F2CC8F;" class="far fa-plus-square"></i>
                </th>
            </tr>
            <!-- Fill with data if availible --> 
            <?php
                FillRow($Main,"Main");
            ?>
            <!--
            <tr id="Main0">
                <td style="width: 10%;"><input type="text" name="Quantity"></td>
                <td style="width: 15%;"><input type="text" name="Unit" list="Unit"/></td>
                <td><input onchange="GetName2('Main0')" type="text" id="Main0Name1" list="Name1All"/></td>
                <td><input onchange="GetName3('Main0')" type="text" id="Main0Name2" list="Name2Main0"/></td>
                <td><input type="text" id="Main0Name3" list="Name3Main0"/></td>
                <td>
                    <i onclick="RemoveElement('Main0')" style="color: #F2CC8F; font-size: 5vw" class="fas fa-plus-square"></i>
                </td>
            </tr>
            -->
        </table>
        <div class="break" style="clear: both;"></div>
        <table id="Support"">
            <tr>
                <th colspan="4" style="text-align: left;">
                    Support <i onclick="AddIngredient('Support')" style="color: #F2CC8F;" class="far fa-plus-square"></i>
                </th>
            </tr>
            <!-- Fill with data if availible --> 
            <?php
                FillRow($Support,"Support");
            ?>
        </table>
        <div class="break" style="clear: both;"></div>
        <table id="Spices">
            <tr>
                <th colspan="4" style="text-align: left;">
                    Spices <i onclick="AddIngredient('Spices')" style="color: #F2CC8F;" class="far fa-plus-square"></i>
                </th>
            </tr>
            <!-- Fill with data if availible --> 
            <?php
                FillRow($Spices,"Spices");
            ?>
        </table>
        <div class="break" style="clear: both;"></div>
        <table id="Garnish">
            <tr>
                <th colspan="4" style="text-align: left;">
                    Garnish <i onclick="AddIngredient('Garnish')" style="color: #F2CC8F;" class="far fa-plus-square"></i>
                </th>
            </tr>
            <!-- Fill with data if availible --> 
            <?php
                FillRow($Garnish,"Garnish");
            ?>
        </table>
        <div class="break" style="clear: both;"></div>
        <table class="fa-4x" id="SaveIngredients">
                <th colspan="4" style="text-align: left;">
                    Save Ingredients: <i onclick="BuildIngredientOptions()" style="color: #E07A5F;" class="fas fa-kiwi-bird fa-flip-horizontal"></i>
                </th>
        </table>
        <div class="break" style="clear: both;"></div>
        <div id="Notes">
            <table>
                <tr>
                    <th colspan="4" style="text-align: left;">Notes</th>
                </tr>
            </table>
            <textarea style="width:98%;" rows="5" id="NotesBox"><?php echo $JsonData["Notes"];?></textarea><br>
        </div>

        
        
        <!-- Print Prep step -->
        <div class="break"></div> 
        <div id="DivStep0">
            <table style="text-align: left; width: 75%;" id="Step0">
                <tr>
                    <th>Prep <i onclick="AddIngredientLine(0)" style="color: #F2CC8F;" class="far fa-plus-square"></i></th>
                </tr>
                <!-- List of prep ingredients -->
                <?php
                    //Create a list of Ids that match a given name
                    $OptionList = IdAndName($Main,"Main");
                    $OptionList = array_merge($OptionList,IdAndName($Support,"Support"));
                    $OptionList = array_merge($OptionList,IdAndName($Spices,"Spices"));
                    $OptionList = array_merge($OptionList,IdAndName($Garnish,"Garnish"));
                    PreSelectedDropDown($AllIngredinets,'0',$OptionList)
                ?>
            </table>
            <textarea style="width:98%;" rows="10" id="NotesBox"><?php 
                if(count($JsonData["Steps"])>0){
                    echo $JsonData["Steps"][0];
                }?>
            </textarea>
        </div>
        <div class="break"></div>
        <table id="AddNewStep">
            <th colspan="4" style="text-align: left;">
                Add/Remove Step: <i onclick="AddStep()"style="color: #F2CC8F; font-size: 4vw;" class="far fa-plus-square"></i>
                <i onclick="RemoveStep()" style="color: #F2CC8F; font-size: 4vw;" class="far fa-minus-square"></i>
            </th>
        </table>
        <div class="break"></div>

        <!-- Steps section -->
        <div id="Steps">
            <!-- Print the other steps -->
            <?php
                if(count($JsonData["Steps"])>1){
                    for ($Id = 1; $Id < count($JsonData["Steps"]); $Id++){
                        echo '<div id="DivStep'.$Id.'">';
                            echo '<table style="text-align: left; width: 75%;" id="Step'.$Id.'">';
                                echo '<tr>';
                                    echo '<th>Step '.$Id.' <i onclick="AddIngredientLine('.$Id.')" style="color: #F2CC8F;" class="far fa-plus-square"></i></th>';
                                echo '</tr>';   
                                PreSelectedDropDown($AllIngredinets,$Id,$OptionList);
                            echo '</table>';
                            echo '<textarea style="width:98%;" rows="10" id="Step'.$Id.'Box">';
                                echo $JsonData["Steps"][$Id];
                            echo '</textarea>';
                        echo '</div>';
                        echo '<div class="break" id="BreakStep'.$Id.'"></div> ';
                    }
                }
            ?>
        </div>
        <div id="Buttons">
            <tr>
                <td><button onclick="window.location.reload();">Reset</button></td>
                <td><button style="float: right;"onclick="Submit()">Save</button></td>
            </tr>
        </div>
    </div>
    
</body>
</html>
