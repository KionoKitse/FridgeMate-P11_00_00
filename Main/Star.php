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
        input {
            width: 90%;
            font-size: 3.2vw;
        }

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

<div id="Lists">
    <!-- Create units datalist -->
    <datalist id="Units">
        <option value="ts">
        <option value="Tb">
        <option value="C">
        <option value="g">
        <option value="Pc">
        <option value="pch">
        <option value="*">
    </datalist>
</div>

<!-- JavaScript functions for DOM manipulations -->
<script>
    //Global variables
    var MainId = 1;
    var SupportId = 0;
    var SpicesId = 0;
    var GarnishId = 0;
    var Name1Options = '';
    GetName1();
    
    //function to remove an ingredient row
    function RemoveIngredient(str){
        document.getElementById(str).remove();
    }
    //Function to  add an ingredient row
    function AddIngredient(str) {
        //Create a row id
        var RowId='';
        if(str == 'Main'){
            MainId++;
            RowId = 'Main'+MainId;
        }else if(str == 'Support'){
            SupportId++;
            RowId = 'Support'+SupportId;
        }else if(str == 'Spices'){
            SpicesId++;
            RowId = 'Spices'+SpicesId;
        }else{
            GarnishId++;
            RowId = 'Garnish'+GarnishId; 
        }
        //Get the table
        var table = document.getElementById(str);
        //Create a new row
        var row = table.insertRow(-1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        //Add items to cells
        cell1.innerHTML = '<input type="text" name="Quantity">';
        cell2.innerHTML = '<input type="text" id="'+RowId+'Units" list="Units"/>';
        cell3.innerHTML = '<input onchange="GetName2(\''+RowId+'\')" type="text" id="'+RowId+'Name1" list="Name1'+RowId+'"/>';
        cell4.innerHTML = '<input onchange="GetName3(\''+RowId+'\')" type="text" id="'+RowId+'Name2" list="Name2'+RowId+'"/>';
        cell5.innerHTML = '<input type="text" id="'+RowId+'Name3" list="Name3'+RowId+'"/>';
        cell6.innerHTML = '<i onclick="RemoveIngredient(\''+RowId+'\')" style="color: #F2CC8F; font-size: 6vw" class="fas fa-plus-square"></i>';
        //Give row an id
        row.id = RowId;
        //Style
        cell1.style.width = '10%';
        cell2.style.width = '10%';
        //Create list Name1 and add to list section
        var NewList = '<datalist id="Name1'+RowId+'">'+Name1Options+'</datalist>';
        var temp = document.getElementById("Lists").innerHTML;
        document.getElementById("Lists").innerHTML = temp + NewList;
    }
    //Function to add an ingredient line
    function AddIngredientLine(){

    }
    //Function to get the name 1 options
    function GetName1(){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var hello = this.responseText;
                Name1Options = hello;
            }
        };
        xmlhttp.open("GET","Name1.php",true);
        xmlhttp.send();
    }
    //Function to create a Name2 data list
    function GetName2(str){
        //Get the Name1 value
        var Name1 = document.getElementById(str+'Name1').value;

        //Run the request to get Name2
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Get the list section
                var temp = document.getElementById("Lists").innerHTML;
                //Get list of options
                var NewList = this.responseText;
                //Create a new list
                NewList = '<datalist id="Name2'+str+'">'+NewList+'</datalist>';
                //Replace or add
                var OldList = document.getElementById('Name2'+str)
                if(OldList == null){
                    //Add the new list to the lists section
                    document.getElementById("Lists").innerHTML = temp + NewList;
                }else{
                    //Replace the current list
                    OldList.innerHTML = NewList;
                }
            }
        };
        xmlhttp.open("GET","Name2.php?name1="+Name1,true);
        xmlhttp.send();
    }
    //Function to create a Name2 data list
    function GetName3(str){
        //Get the Name1 value
        var Name1 = document.getElementById(str+'Name1').value;
        var Name2 = document.getElementById(str+'Name2').value;

        //Run the request to get Name3
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Get the list section
                var temp = document.getElementById("Lists").innerHTML;
                //Get list of options
                var NewList = this.responseText;
                //Create a new list
                NewList = '<datalist id="Name3'+str+'">'+NewList+'</datalist>';
                //Replace or add
                var OldList = document.getElementById('Name3'+str)
                if(OldList == null){
                    //Add the new list to the lists section
                    document.getElementById("Lists").innerHTML = temp + NewList;
                }else{
                    //Replace the current list
                    OldList.innerHTML = NewList;
                }
            }
        };
        xmlhttp.open("GET","Name3.php?name1="+Name1+"&name2="+Name2,true);
        xmlhttp.send();
    }
</script>
<!-- Check if it's a modification or a new recipe -->
<?php
    include 'test1.php';
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
        //Get JSON data
        $JsonData = ReadJSON($_GET['id']);
        //Get MySQL
        $MySQL = GetMySQL($_GET['id']);
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
                    Main <i onclick="AddIngredient('Main')" style="color: #F2CC8F;" class="far fa-plus-square"></i>
                </th>
            </tr>
            <tr id="Main0">
                <td style="width: 10%;"><input type="text" name="Quantity"></td>
                <td style="width: 10%;"><input type="text" name="Units" list="Units"/></td>
                <td><input onchange="GetName2('Main0')" type="text" id="Main0Name1" list="Name1Main0"/></td>
                <td><input onchange="GetName3('Main0')" type="text" id="Main0Name2" list="Name2Main0"/></td>
                <td><input type="text" id="Main0Name3" list="Name3Main0"/></td>
                <td>
                    <i onclick="RemoveIngredient('Main0')" style="color: #F2CC8F; font-size: 6vw" class="fas fa-plus-square"></i>
                </td>
            </tr>
            
        </table>
        <div class="break" style="clear: both;"></div>
        <table id="Support"">
            <tr>
                <th colspan="4" style="text-align: left;">
                    Support <i onclick="AddIngredient('Support')" style="color: #F2CC8F;" class="far fa-plus-square"></i>
                </th>
            </tr>
        </table>
        <div class="break" style="clear: both;"></div>
        <table id="Spices">
            <tr>
                <th colspan="4" style="text-align: left;">
                    Spices <i onclick="AddIngredient('Spices')" style="color: #F2CC8F;" class="far fa-plus-square"></i>
                </th>
            </tr>
        </table>
        <div class="break" style="clear: both;"></div>

        <table id="Garnish">
            <tr>
                <th colspan="4" style="text-align: left;">
                    Garnish <i onclick="AddIngredient('Garnish')" style="color: #F2CC8F;" class="far fa-plus-square"></i>
                </th>
            </tr>
        </table>
        <div class="break" style="clear: both;"></div>

        <div id="Notes">
            <table>
                <tr>
                    <th colspan="4" style="text-align: left;">Notes</th>
                </tr>
            </table>
            <textarea style="width:98%;" rows = "5" name = "description"><?php echo $JsonData["Notes"];?></textarea><br>
        </div>

        <!-- Print Prep step -->
        <div class="break"></div> 
        <div id="Prep">
            <table style="text-align: left;">
                <tr>
                    <th colspan="3">Prep <i onclick="AddIngredientLine()" style="color: #F2CC8F;" class="far fa-plus-square"></i></th>
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

