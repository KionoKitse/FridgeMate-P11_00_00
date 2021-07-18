<!--Testing PHP generate page -->
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
    <script src="js/mainstyle.js"></script>
    <script src="js/scripts.js"></script>
</head>

<style>
    .bttnYellow {
        background-color: #F2CC8F;
        width:100%;
        border-radius: 5px; 
        border: 2px solid #FFE6A9; 
    }
    .bttnOrange {
        background-color: #E07A5F;
        width:100%;
        border-radius: 5px; 
        border: 2px solid #FA9479; 
    }
    .bttnGreen {
        background-color: #81B29A;
        width:100%;
        border-radius: 5px; 
        border: 2px solid #9BCCB4; 
    }
</style>

<?php
    include 'php/genFunc.php';

    //Create variables for recipe
    $RecipeId = 0;
    $Main = array();
    $Support = array();
    $Spices = array();
    $Garnish = array();
    $Prep = array();
    $Percent = 0;
    $Steps = array();
    $RecipeTags = array();
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

    //Establish connection
    require_once 'dbconnect.php';

    //Get all the tags
    $AllTags = GetAllTags($connection);

    //Get the variables if an id has been provided
    if(isset($_GET['id']) && !empty($_GET['id']) && ctype_digit($_GET['id'])){  
        $RecipeId = $_GET['id'];

        //Get JSON data
        $JsonData = ReadJSON($RecipeId);

        //Get the ingredients values needed for the recipe
        $Query1 = "SELECT * FROM ingredient WHERE recipe_id = ?";
        $stmt = $connection->prepare($Query1);
        $stmt->bind_param("i", $RecipeId);
        $stmt->execute();
        $ResultSet1 = $stmt->get_result();

        //Get the ingredient information
        $Query1 = "SELECT item_id FROM ingredient WHERE recipe_id = ?";
        $Query2 = "SELECT * FROM pantry WHERE item_id IN (" . $Query1 . ")";
        $stmt = $connection->prepare($Query2);
        $stmt->bind_param("i", $RecipeId);
        $stmt->execute();
        $ResultSet2 = $stmt->get_result();

        //Get the recipe information
        $Query1 = "SELECT * FROM recipe WHERE recipe_id = ?";
        $stmt = $connection->prepare($Query1);
        $stmt->bind_param("i", $RecipeId);
        $stmt->execute();
        $ResultSet3 = $stmt->get_result();

        //Get the recipe tags
        $Query1 = "SELECT tag FROM tags WHERE recipe_id = ?";
        $stmt = $connection->prepare($Query1);
        $stmt->bind_param("i", $RecipeId);
        $stmt->execute();
        $ResultSet4 = $stmt->get_result();
        while ($row = $ResultSet4->fetch_row()) {
            array_push($RecipeTags,$row[0]);
        }

        //close the stmt
        $stmt->close();

        //Get the recipe percent
        while ($row = $ResultSet3->fetch_row()) {
            $Percent = $row[6];
        }
        $Percent = floatval($Percent);

        //Build lists of ingredients
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
                'Step' => $row[5],
                'Prep' => $row[6] 
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
    }
    db_disconnect($connection);
?>

<body>
    <!-- Lists used for the datallists -->
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
        
        <!-- Create Tags datalist -->
        <datalist id="Tags">
            <?php
                foreach($AllTags as $val) {
                    echo "<option value=\"$val\">";
                }
            ?>
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
        </table>
        <div class="break" style="clear: both;"></div>
        <table id="Support">
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
        
        <div class="break"></div> 
        
        <!-- Tags section -->
        <div id="Tags">
            <table style="text-align: left; width: 100%;" id="TagTable">
                <tr>
                    <th>Tags 
                        <i onclick="AddTags()" style="color: #F2CC8F;" class="far fa-plus-square"></i>
                        <i onclick="RemoveTags()" style="color: #F2CC8F;" class="far fa-minus-square"></i>
                    </th>
                </tr>
                <?php
                    //Print the recipe tags
                    for($i=0; $i<count($RecipeTags)-1; $i+=2){
                        echo '<tr>';
                            echo '<td>';
                                echo '<input type="text" value="'.$RecipeTags[$i].'" list="Tags">';
                            echo '</td>';
                            echo '<td>';
                                echo '<input type="text" value="'.$RecipeTags[$i+1].'" list="Tags">';
                            echo '</td>';
                        echo '</tr>';
                    }
                    //Print the last recipe tag if needed
                    if(($i+1)==count($RecipeTags)){
                        echo '<tr>';
                            echo '<td>';
                                echo '<input type="text" value="'.$RecipeTags[$i].'" list="Tags">';
                            echo '</td>';
                            echo '<td>';
                                echo '<input type="text" list="Tags">';
                            echo '</td>';
                        echo '</tr>';
                    }
                ?>
            </table>
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
            <textarea style="width:98%;" rows="10" id="Step0Box"><?php if(count($JsonData["Steps"])>0) echo $JsonData["Steps"][0];?></textarea>
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
                            echo '<textarea style="width:98%;" rows="10" id="Step'.$Id.'Box" onchange="UpdateStepText('.$Id.')">';
                                echo $JsonData["Steps"][$Id];
                            echo '</textarea>';
                        echo '</div>';
                        echo '<div class="break" id="BreakStep'.$Id.'"></div> ';
                    }
                }
            ?>
        </div>

        <!-- Buttons section -->
        <div id="Buttons">
            <table style="Width:100%;">
                <tr>
                    <td style="Width:33%;"><button class="bttnOrange" onclick="window.location.reload();">Reset</button></td>
                    <td style="Width:33%;"><button class="bttnGreen" onclick="location.href='recipe.php?id=<?php echo $RecipeId?>'">View</button></td>
                    <td><button class="bttnYellow" onclick="Submit()">Save</button></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

<script>
    //Globals
    var MainId = 0;
    var SupportId = 0;
    var SpicesId = 0;
    var GarnishId = 0;
    var Steps;
    var OptionList;
    var UnselectedOptions;

    //Class
    class Ingredient {
        constructor(Quantity,Unit,Name1,Name2,Name3,Prep) {
        this.Quantity = Quantity;
        this.Unit = Unit;
        this.Name1 = Name1;
        this.Name2 = Name2;
        this.Name3 = Name3;
        this.Prep = Prep;
        }
    }
    class PantryObj {
        constructor(ItemId,Name1,Name2,Name3) {
        this.ItemId = ItemId;
        this.Name1 = Name1;
        this.Name2 = Name2;
        this.Name3 = Name3;
        this.Item_Id = null;
        }
    }


    //Fill global variables
    document.addEventListener("DOMContentLoaded", GetIdCounts);
    document.addEventListener("DOMContentLoaded", BuildIngredientOptions);
    document.addEventListener("DOMContentLoaded", GetName1);


    //function to remove an ingredient row
    function RemoveElement(str){
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
        cell1.innerHTML = '<input type="text" id="'+RowId+'Quantity">';
        cell2.innerHTML = '<input type="text" id="'+RowId+'Unit" list="Unit"/>';
        cell3.innerHTML = '<input onchange="GetName2(\''+RowId+'\')" type="text" id="'+RowId+'Name1" list="Name1All"/>';
        cell4.innerHTML = '<input onchange="GetName3(\''+RowId+'\')" type="text" id="'+RowId+'Name2" list="Name2'+RowId+'"/>';
        cell5.innerHTML = '<input type="text" id="'+RowId+'Name3" list="Name3'+RowId+'"/>';
        cell6.innerHTML = '<i onclick="RemoveElement(\''+RowId+'\')" style="color: #F2CC8F; font-size: 5vw" class="far fa-minus-square"></i>';
        //Give row an id
        row.id = RowId;
        //Style
        cell1.style.width = '10%';
        cell2.style.width = '15%';
    }
    function BuildIngredientOptions(){
        var TempOptionList=[];
        UnselectedOptions = '<option value=""></option>';
        for (var Id = 1; Id <= MainId; Id++) {
            var RowId = "Main"+Id;
            try {
                var Quantity = document.getElementById("Main"+Id+"Quantity").value;
                var Unit = document.getElementById("Main"+Id+"Unit").value;
                var Name1 = document.getElementById("Main"+Id+"Name1").value;
                var Name2 = document.getElementById("Main"+Id+"Name2").value;
                var Name3 = document.getElementById("Main"+Id+"Name3").value;
                var RowName = Quantity+" "+Unit+" "+Name1+" "+Name2+" "+Name3;
                var Row = [RowId,RowName];
                TempOptionList.push(Row);
                UnselectedOptions += '<option value="'+RowId+'">'+RowName+'</option>';
            }
            catch(err) {}
        }
        for (var Id = 1; Id <= SupportId; Id++) {
            var RowId = "Support"+Id;
            try {
                var Quantity = document.getElementById("Support"+Id+"Quantity").value;
                var Unit = document.getElementById("Support"+Id+"Unit").value;
                var Name1 = document.getElementById("Support"+Id+"Name1").value;
                var Name2 = document.getElementById("Support"+Id+"Name2").value;
                var Name3 = document.getElementById("Support"+Id+"Name3").value;
                var RowName = Quantity+" "+Unit+" "+Name1+" "+Name2+" "+Name3;
                var Row = [RowId,RowName];
                TempOptionList.push(Row);
                UnselectedOptions += '<option value="'+RowId+'">'+RowName+'</option>';
            }
            catch(err) {}
        }
        for (var Id = 1; Id <= SpicesId; Id++) {
            var RowId = "Spices"+Id;
            try {
                var Quantity = document.getElementById("Spices"+Id+"Quantity").value;
                var Unit = document.getElementById("Spices"+Id+"Unit").value;
                var Name1 = document.getElementById("Spices"+Id+"Name1").value;
                var Name2 = document.getElementById("Spices"+Id+"Name2").value;
                var Name3 = document.getElementById("Spices"+Id+"Name3").value;
                var RowName = Quantity+" "+Unit+" "+Name1+" "+Name2+" "+Name3;
                var Row = [RowId,RowName];
                TempOptionList.push(Row);
                UnselectedOptions += '<option value="'+RowId+'">'+RowName+'</option>';
            }
            catch(err) {}
        }
        for (var Id = 1; Id <= GarnishId; Id++) {
            var RowId = "Garnish"+Id;
            try {
                var Quantity = document.getElementById("Garnish"+Id+"Quantity").value;
                var Unit = document.getElementById("Garnish"+Id+"Unit").value;
                var Name1 = document.getElementById("Garnish"+Id+"Name1").value;
                var Name2 = document.getElementById("Garnish"+Id+"Name2").value;
                var Name3 = document.getElementById("Garnish"+Id+"Name3").value;
                var RowName = Quantity+" "+Unit+" "+Name1+" "+Name2+" "+Name3;
                var Row = [RowId,RowName];
                TempOptionList.push(Row);
                UnselectedOptions += '<option value="'+RowId+'">'+RowName+'</option>';
            }
            catch(err) {}
        }
        OptionList = TempOptionList;
    }
    function GetIdCounts(){
        MainId = document.getElementById("MainId").value;
        SupportId = document.getElementById("SupportId").value;
        SpicesId = document.getElementById("SpicesId").value;
        GarnishId = document.getElementById("GarnishId").value;
        var temp = document.getElementById("StepIds").value;
        Steps = JSON.parse(temp);
        
        //PrepId = document.getElementById("PrepId").value;
    }
    //Function to add an ingredient line
    function AddIngredientLine(id){
        //Create a unique id
        Steps.Ids[id]++;
        var RowId='Step'+id+'Item'+Steps.Ids[id];
        //Get the table
        var table = document.getElementById('Step'+id);
        //Create a new row
        var row = table.insertRow(-1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        //Add items to cells
        cell1.innerHTML = '<select id="Select'+RowId+'" onchange="UpdateSelectOption(\'Select'+RowId+'\')">'+UnselectedOptions+'</select>';
        cell2.innerHTML = '<i onclick="RemoveElement(\''+RowId+'\')" style="color: #F2CC8F; font-size: 5vw;" class="far fa-minus-square"></i>';
        //Give row an id
        row.id = RowId;
        var hello = Steps.Ids[id];
    }
    //Function to add a step
    function AddStep(){
        //Create a new id count for the new step
        Steps.Ids.push(0);
        //Get the step id
        var StepId = Steps.Ids.length-1;
        //Create the html for the new step
        var NewStep = '<div id="DivStep'+StepId+'">';
        NewStep += '<table style="text-align: left; width: 75%;" id="Step'+StepId+'">'
        NewStep += '<tr>';
        NewStep += '<th>Step '+StepId+' <i onclick="AddIngredientLine('+StepId+')" style="color: #F2CC8F;" class="far fa-plus-square"></i></th>';
        NewStep += '</tr>'; 
        //NewStep += PreSelectedDropDown($AllIngredinets,$Id,$OptionList);
        NewStep += '</table>';
        NewStep += '<textarea style="width:98%;" rows="10" id="Step'+StepId+'Box" onchange="UpdateStepText('+StepId+')"></textarea>';
        NewStep += '</div>';
        NewStep += '<div class="break" id="BreakStep'+StepId+'"></div>'

        //Add new html to old html 
        var OldSteps = document.getElementById("Steps").innerHTML;
        document.getElementById("Steps").innerHTML = OldSteps + NewStep;
    }
    //Function to remove a step
    function RemoveStep(){
        //Get the number of steps
        if(Steps.Ids.length > 1){
            //Get confirmation
            var temp = Steps.Ids.length-1;
            temp = "Are you sure you want to remove Step "+temp;
            if (confirm(temp) == true) {
                //Remove the last step from the Steps Id array
                Steps.Ids.pop();
                var StepId = Steps.Ids.length;
                //Remove the DivStep and the break element
                RemoveElement('DivStep'+StepId);
                RemoveElement('BreakStep'+StepId);
            }  
        }
    }
    //Function to transfer the textarea value to it's innerHTML
    function UpdateStepText(Step){
        document.getElementById("Step"+Step+"Box").innerHTML = document.getElementById("Step"+Step+"Box").value;
    }
    //Function to add a row of tags
    function AddTags(){
        //Get the table
        var table = document.getElementById("TagTable");
        //Add a row an two cells
        var row = table.insertRow(-1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        //Create input element
        var inputElement1 = document.createElement("INPUT");
        inputElement1.setAttribute("type", "text");
        inputElement1.setAttribute("list", "Tags");
        var inputElement2 = inputElement1.cloneNode(true);
        //Add children
        cell1.appendChild(inputElement1); 
        cell2.appendChild(inputElement2);
    }
    //Function to remove a row of tags
    function RemoveTags(){
        //Get the table
        var table = document.getElementById("TagTable");
        //Remove the last row
        if(table.rows.length > 1) table.deleteRow(-1);
    }
    //Function to submit the data
    function Submit(){
        try{
            //Get the name, link, and image source
            var Name = document.getElementById("Name").value;
            var Link = document.getElementById("Link").value;
            var Image = document.getElementById("Image").value;
            
            //Get stats stuff
            var Rating = document.getElementById("Rating").value;
            var ActiveTime = document.getElementById("ActiveTime").value;
            var PassiveTime = document.getElementById("PassiveTime").value;
            var People = document.getElementById("People").value;

            //Get main, support, spices, garnish, prep
            var Main = document.getElementById("Main");
            var Support = document.getElementById("Support");
            var Spices = document.getElementById("Spices");
            var Garnish = document.getElementById("Garnish");
            var Prep = document.getElementById("Step0");

            //Create the pantry table (name1, name2, name3)
            var AllIngredient = [];
            for (var i = 1; i < Main.rows.length; i++) {
                var Quantity = Main.rows[i].cells[0].children[0].value;
                var Unit = Main.rows[i].cells[1].children[0].value;
                var Name1 = Main.rows[i].cells[2].children[0].value;
                var Name2 = Main.rows[i].cells[3].children[0].value;
                var Name3 = Main.rows[i].cells[4].children[0].value;
                AllIngredient.push(new Ingredient(Quantity,Unit,Name1,Name2,Name3,0));
            }
            for (var i = 1; i < Support.rows.length; i++) {
                var Quantity = Support.rows[i].cells[0].children[0].value;
                var Unit = Support.rows[i].cells[1].children[0].value;
                var Name1 = Support.rows[i].cells[2].children[0].value;
                var Name2 = Support.rows[i].cells[3].children[0].value;
                var Name3 = Support.rows[i].cells[4].children[0].value;
                AllIngredient.push(new Ingredient(Quantity,Unit,Name1,Name2,Name3,0));
            }
            for (var i = 1; i < Spices.rows.length; i++) {
                var Quantity = Spices.rows[i].cells[0].children[0].value;
                var Unit = Spices.rows[i].cells[1].children[0].value;
                var Name1 = Spices.rows[i].cells[2].children[0].value;
                var Name2 = Spices.rows[i].cells[3].children[0].value;
                var Name3 = Spices.rows[i].cells[4].children[0].value;
                AllIngredient.push(new Ingredient(Quantity,Unit,Name1,Name2,Name3,0));
            }
            for (var i = 1; i < Garnish.rows.length; i++) {
                var Quantity = Garnish.rows[i].cells[0].children[0].value;
                var Unit = Garnish.rows[i].cells[1].children[0].value;
                var Name1 = Garnish.rows[i].cells[2].children[0].value;
                var Name2 = Garnish.rows[i].cells[3].children[0].value;
                var Name3 = Garnish.rows[i].cells[4].children[0].value;
                AllIngredient.push(new Ingredient(Quantity,Unit,Name1,Name2,Name3,0));
            }
            //Mark prep ingredient
            for (var i = 1; i < Prep.rows.length; i++) {
                var hello = Prep.rows[0];
            }


            //////////////////////////////////////////////////

            //Get the data for the Pantry table
            var PantryTable = [];
            //Get the main ingredients
            for (var Id = 1; Id <= MainId; Id++) {
                var ItemId = "Main"+Id;
                try {
                    var Name1 = document.getElementById("Main"+Id+"Name1").value;
                    var Name2 = document.getElementById("Main"+Id+"Name2").value;
                    var Name3 = document.getElementById("Main"+Id+"Name3").value;                
                    PantryTable.push(new PantryObj(ItemId,Name1,Name2,Name3));
                }
                catch(err) {}
            }
            //Get the support ingredients
            for (var Id = 1; Id <= SupportId; Id++) {
                var ItemId = "Support"+Id;
                try {
                    var Name1 = document.getElementById("Support"+Id+"Name1").value;
                    var Name2 = document.getElementById("Support"+Id+"Name2").value;
                    var Name3 = document.getElementById("Support"+Id+"Name3").value;
                    PantryTable.push(new PantryObj(ItemId,Name1,Name2,Name3));
                }
                catch(err) {}
            }
            //Get the spices ingredients
            for (var Id = 1; Id <= SpicesId; Id++) {
                var ItemId = "Spices"+Id;
                try {
                    var Name1 = document.getElementById("Spices"+Id+"Name1").value;
                    var Name2 = document.getElementById("Spices"+Id+"Name2").value;
                    var Name3 = document.getElementById("Spices"+Id+"Name3").value;
                    PantryTable.push(new PantryObj(ItemId,Name1,Name2,Name3));
                }
                catch(err) {}
            }
            //Get the garnish ingredients
            for (var Id = 1; Id <= GarnishId; Id++) {
                var ItemId = "Garnish"+Id;
                try {
                    var Name1 = document.getElementById("Garnish"+Id+"Name1").value;
                    var Name2 = document.getElementById("Garnish"+Id+"Name2").value;
                    var Name3 = document.getElementById("Garnish"+Id+"Name3").value;
                    PantryTable.push(new PantryObj(ItemId,Name1,Name2,Name3));
                }
                catch(err) {}
            }

            //Get the data for the Ingredient table
            var IngredientTable = [];
            var MainCt = 0;
            var SupportCt = 0;
            var SpicesCt = 0;
            var GarnishCt = 0;
            //Get the ingredient information in each step
            for (var Step = 1; Step<Steps.Ids.length; Step++){
                //For each ingredient in a step
                for (var Id = 1; Id <= Steps.Ids[Step]; Id++) {
                    //Parse the ingredient
                    var StepItemId = "SelectStep"+Step+"Item"+Id;
                    try {
                        //Variables
                        var ItemId, Category, Quantity, Unit;
                        //Get the ItemId information
                        ItemId = document.getElementById(StepItemId).value; //Suport1

                        if(ItemId.substring(0, 4) === 'Main'){
                            Category = 1;
                            MainCt++;
                        } else if(ItemId.substring(0, 6) === 'Spices'){
                            Category = 3;
                            SpicesCt++;
                        } else if(ItemId.substring(0, 7) === 'Support'){
                            Category = 2;
                            SupportCt++;
                        } else {
                            Category = 4;
                            GarnishCt++;
                        }
                        Quantity = document.getElementById(ItemId+"Quantity").value;
                        Unit = document.getElementById(ItemId+"Unit").value;

                        var ParsedIngredient = new Object;
                        ParsedIngredient.ItemId = ItemId;
                        ParsedIngredient.Category = Category;
                        ParsedIngredient.Quantity = Quantity;
                        ParsedIngredient.Unit = Unit;
                        ParsedIngredient.Step = Step;
                        ParsedIngredient.Prep = 0;
                        ParsedIngredient.Percent = 0;
                        IngredientTable.push(ParsedIngredient);
                    }
                    catch(err) {}
                }
            }

            //Calculate the contribution percentage
            var MainPercent = 75;
            var SupportPercent = 20;
            var SpicesPercent = 3;
            var GarnishPercent = 2;
            //Check for a missing category
            var TotalPoints = MainPercent*(MainCt>0)+SupportPercent*(SupportCt>0)+SpicesPercent*(SpicesCt>0)+GarnishPercent*(GarnishCt>0);
            //Adjust the percentages if one category is not present
            if(TotalPoints<100){
                var ExtraPoints = 100-TotalPoints;
                var ExtraPercent = ExtraPoints/TotalPoints;

                if(MainCt>0) MainPercent = (MainPercent*ExtraPercent+MainPercent)/MainCt;
                if(SupportCt>0) SupportPercent = (SupportPercent*ExtraPercent+SupportPercent)/SupportCt;
                if(SpicesCt>0) SpicesPercent = (SpicesPercent*ExtraPercent+SpicesPercent)/SpicesCt;
                if(GarnishCt>0) GarnishPercent = (GarnishPercent*ExtraPercent+GarnishPercent)/GarnishCt;
            }
            else{
                MainPercent = MainPercent/MainCt;
                SupportPercent = SupportPercent/SupportCt;
                SpicesPercent = SpicesPercent/SpicesCt;
                GarnishPercent = GarnishPercent/GarnishCt;
            }
            
            //Update the percentage for each item
            for (var i=0; i<IngredientTable.length; i++){
                switch (IngredientTable[i].Category) {
                    case 1:
                        IngredientTable[i].Percent = MainPercent;
                        break;
                    case 2:
                        IngredientTable[i].Percent = SupportPercent;
                        break;
                    case 3:
                        IngredientTable[i].Percent = SpicesPercent;
                        break;
                    case 4:
                        IngredientTable[i].Percent = GarnishPercent;
                        break;    
                }
            }


            //Mark the prep ingredients and update the percentage
            var PrepTable = document.getElementById("Step0");
            var NumRows = PrepTable.rows.length;
            for (var i=1; i<NumRows; i++){
                //Get the item id for this prep ingredient
                var ItemId = PrepTable.rows[i].cells[0].children[0].value;
                for (var row = 0; row<IngredientTable.length; row++) {
                    if(IngredientTable[row].ItemId === ItemId){
                        IngredientTable[row].Prep = 1;
                        break;
                    }
                }
            }

            /*
            //Mark the prep ingredients
            for (var Id = 1; Id <= Steps.Ids[0]; Id++) {
                var StepItemId = "SelectStep0Item"+Id;
                var ItemId = document.getElementById(StepItemId).value;
                for (var row = 0; row<IngredientTable.length; row++) {
                    if(IngredientTable[row].ItemId === ItemId){
                        IngredientTable[row].Prep = 1;
                        break;
                    }
                }
            }
            */

            //Get the data for the Tags table
            var TagsTable = [];
            var table = document.getElementById("TagTable");
            for (var Id = 1; Id<table.rows.length; Id++){
                var ValueA = table.rows[Id].cells[0].children[0].value;
                var ValueB = table.rows[Id].cells[1].children[0].value;
                if(ValueA != '') TagsTable.push(ValueA);
                if(ValueB != '') TagsTable.push(ValueB);
            }
            
            //Get the data for the JSON file
            var Notes = document.getElementById("NotesBox").value;
            var StepDirections = [];
            for (var Step = 0; Step<Steps.Ids.length; Step++){
                try{
                    StepDirections.push(document.getElementById("Step"+Step+"Box").value);
                }
                catch(err){}
            }

            //Get the top 6 ingredients
            var TopList = new Array(6);
            for (var i = 0; (i<6) && (i<PantryTable.length); i++){
                TopList[i] = PantryTable[i].Name1+" "+PantryTable[i].Name2+" "+PantryTable[i].Name3;
            }

            //Get the id if there is one
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            var Id = urlParams.get('id');

            //Input validation
            if(!(Rating >= 0 && Rating <= 5)) throw "Submit Validation Failed: Rating is not a number between 0-5";
            if(!(ActiveTime >= 0 && ActiveTime <= 240)) throw "Submit Validation Failed: ActiveTime is not a number between 0-240";
            if(!(PassiveTime >= 0 && PassiveTime <= 10080)) throw "Submit Validation Failed: PassiveTime is not a number between 0-10080";
            if(!(People >= 0 && People <= 10)) throw "Submit Validation Failed: People is not a number between 0-10";

            //Parse the data into a JSON so it may be passed to PHP
            var EditResults = new Object();
            EditResults.Id = Id;
            EditResults.Name = Name;
            EditResults.Link = Link;
            EditResults.Image = Image
            EditResults.Rating = Rating;
            EditResults.ActiveTime = ActiveTime;
            EditResults.PassiveTime = PassiveTime;
            EditResults.People = People;
            EditResults.PantryTable = PantryTable;
            EditResults.IngredientTable = IngredientTable;
            EditResults.TagsTable = TagsTable;
            EditResults.Notes = Notes;
            EditResults.StepDirections = StepDirections;
            EditResults.TopList = TopList;

            var jsonEditResults= JSON.stringify(EditResults);

            //Submit the data to server for processing
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    alert(this.responseText);
                    //var Hello = this.responseText;
                    //console.log(Hello);
                    //Error handling
                }
            };
            xmlhttp.open("POST","php/submitEdit.php",true);
            xmlhttp.send(jsonEditResults);


            


        }catch(err){
            console.log(err);
            //alert("Hello! I am an alert box!");
        }

        //console.log(product);
    }
</script>

<?php
    //Function to get all the tags from the dtabase
    function GetAllTags($connection){
        $Result = array();
        $Query1 = "SELECT DISTINCT tag FROM tags ORDER BY tag"; 
        $ResultSet1 = $connection->query($Query1);
        while ($row = $ResultSet1->fetch_row()) {
            array_push($Result,$row[0]);
        }
        return $Result;
    }
    //Function to create a row for an ingredient with quantity, unit, and names
    function FillRow($Object,$Type){
        $Id = 1;

        foreach($Object as $row){
            //Create unique id
            $RowId = $Type.$Id;

            //Create rows
            echo '<tr id="'.$RowId.'">';
                echo '<td style="width: 10%;"><input type="text" value="'.$row->Quantity.'" id="'.$RowId.'Quantity"></td>';
                echo '<td style="width: 15%;"><input type="text" value="'.$row->Unit.'" id="'.$RowId.'Unit" list="Unit"/></td>';
                echo '<td><input onchange="GetName2(\''.$RowId.'\')" type="text" value="'.$row->Name1.'" id="'.$RowId.'Name1" list="Name1All"/></td>';
                echo '<td><input onchange="GetName3(\''.$RowId.'\')" type="text" value="'.$row->Name2.'" id="'.$RowId.'Name2" list="Name2'.$RowId.'"/></td>';
                echo '<td><input type="text" value="'.$row->Name3.'" id="'.$RowId.'Name3" list="Name3'.$RowId.'"/></td>';
                echo '<td>';
                    echo '<i onclick="RemoveElement(\''.$RowId.'\')" style="color: #F2CC8F; font-size: 5vw" class="far fa-minus-square"></i>';
                echo '</td>';
            echo '</tr>';
            $Id++;
        }
    }
    //Function to create an array with an specific id and name1-name3 combined
    function IdAndName($Object,$Type){
        $Result=array();
        $Id = 1;
        foreach($Object as $row){
            //Create unique id
            $RowId = $Type.$Id;
            $RowName = $row->Quantity.' '.$row->Unit.' '.$row->Name1.' '.$row->Name2.' '.$row->Name3;
            $Row = array($RowId, $RowName);
            array_push($Result,$Row);
            $Id++;
        }
        return $Result;
    }
    //Function to create a preselected dropdown item with an ingredient already seleceted
    function PreSelectedDropDown($Object,$Step,$Options){
        $Id = 1;
        foreach($Object as $row){
            if (($row->Step == $Step) || (($Step == '0')&&($row->Prep == '1')))
            {
                //Create unique id
                $RowId = "Step".$Step."Item".$Id;
                //Create row name
                $RowName = $row->Quantity.' '.$row->Unit.' '.$row->Name1.' '.$row->Name2.' '.$row->Name3;
                //Create a row
                echo '<tr id="'.$RowId.'">';
                    echo '<td>';
                        echo '<select id="Select'.$RowId.'" onchange="UpdateSelectOption(\'Select'.$RowId.'\')">';
                            echo '<option value=""></option>';
                            foreach($Options as $row2){
                                if($RowName == $row2[1]){
                                    echo '<option selected="selected" value="'.$row2[0].'">'.$row2[1].'</option>';
                                }else{
                                    echo '<option value="'.$row2[0].'">'.$row2[1].'</option>';
                                }
                            }
                        echo '</select>';
                    echo '</td>';
                    echo '<td>';
                        echo '<i onclick="RemoveElement(\''.$RowId.'\')" style="color: #F2CC8F; font-size: 5vw;" class="far fa-minus-square"></i>';
                    echo '</td>';
                echo '</tr>';
                $Id++;
            }
        }
    }

?>
