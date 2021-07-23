<!DOCTYPE html>
<html>
<head>
    <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="js/mainstyle.js"></script>
    <script src="js/scripts.js"></script>
</head>

<!-- Style for this page -->
<style>
    .bttnGreen {
        background-color: #81B29A;
        width:100%;
        border-radius: 5px; 
        border: 2px solid #9BCCB4; 
        color: #3D405B;
        font-size: 3vw;
        cursor: pointer;
    }
    .bttnYellow {
        background-color: #F2CC8F;
        width:100%;
        border-radius: 5px; 
        border: 2px solid #FFE6A9; 
        color: #3D405B;
        font-size: 3vw;
        cursor: pointer;
    }
    .bttnOrange {
        background-color: #E07A5F;
        width:100%;
        border-radius: 5px; 
        border: 2px solid #FA9479; 
        color: #3D405B;
        font-size: 3vw;
        cursor: pointer;
    }
    .clickable {
        cursor: pointer;
        border-bottom: 2pt solid #F2CC8F;
    }
    .bttnTallYellow {
        background-color: #F2CC8F;
        width:100%;
        border-radius: 5px; 
        border: 2px solid #FFE6A9; 
        color: #3D405B;
        font-size: 3vw;
        cursor: pointer;
        height:20vw;
    }
    .bttnTallOrange{
        background-color: #E07A5F;
        width:100%;
        border-radius: 5px; 
        border: 2px solid #FA9479; 
        color: #3D405B;
        font-size: 3vw;
        cursor: pointer;
        height:20vw;
    }
</style>

<!-- Get the required information to render the page -->
<?php
    require_once 'dbconnect.php';

    //Get all Tags
    $Query1 = "SELECT DISTINCT tag FROM tags ORDER BY tag";
    $ResultSet1 = $connection->query($Query1);

    //Get all Ingredients
    $Query1 = "SELECT name1, name2, name3 FROM pantry ORDER by name1";
    $ResultSet2 = $connection->query($Query1);
    
    //Get all Names
    $Query1 = "SELECT name FROM recipe ORDER by name";
    $ResultSet3 = $connection->query($Query1);

    //Build lists
    $TagsList = '<datalist id="SearchList">';
    while ($row = $ResultSet1->fetch_assoc()) {
        $TagsList .= '<option value="'.$row["tag"].'"></option>';
    }
    $TagsList .= '</datalist>';

    $IngredientList = '<datalist id="SearchList">';
    while ($row = $ResultSet2->fetch_assoc()) {
        $IngredientList .= '<option value="'.$row["name1"].'-'.$row["name2"].'-'.$row["name3"].'"></option>';
    }
    $IngredientList .= '</datalist>';

    $NameList = '<datalist id="SearchList">';
    while ($row = $ResultSet3->fetch_assoc()) {
        $NameList .= '<option value="'.htmlspecialchars($row["name"], ENT_QUOTES).'"></option>';
    }
    $NameList .= '</datalist>';


?>

<body>
    <span id="error"></span>
    <!-- Lists used for the datallists -->
    <div id="Lists"></div>
    <div class="center">
        <p style="text-align: center; font-size: 5vw; color: #81B29A; font-weight: bold;">Recipes</p>
        <table style="width: 100%; border-collapse:collapse; border-spacing:0;">
            <tr>
                <td>
                    <button id="Stars" class="bttnGreen" onclick="SearchType('Stars')"><i class="fa fa-star"></i></button>
                </td>
                <td>
                    <button id="Time" class="bttnGreen" onclick="SearchType('Time')"><i class="far fa-clock"></i></button>
                </td>
                <td>
                    <button id="Tags" class="bttnGreen" onclick="SearchType('Tags')"><i class="fas fa-tags"></i></button>
                </td>
                <td>
                    <button id="Ingredient" class="bttnGreen" onclick="SearchType('Ingredient')"><i class="fas fa-carrot"></i></button>
                </td>
                <td>
                    <button id="Name" class="bttnGreen" onclick="SearchType('Name')"><i class="fas fa-id-card"></i></button>
                </td>
                <td>
                    <button id="Score" class="bttnGreen" onclick="SearchType('Score')"><i class="fas fa-hammer"></i></button>
                </td>
                <td>
                    <button id="Old" class="bttnGreen" onclick="SearchType('Old')"><i class="fas fa-blind"></i></button>
                </td>
                <td>
                    <button id="New" class="bttnGreen" onclick="SearchType('New')"><i class="fas fa-plus"></i></button>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <input style="width: 95%" type="text" id="SearchTerm" list="SearchList">
                </td>
                <td>
                    <button class="bttnOrange" onclick="SubmitSearch()"><i class="fas fa-search"></i></button>
                </td>
            </tr>
        </table>
        <div id="Result"></div>
        <button class="bttnGreen" onclick="location.href='./index.html'">Return Home</button>
    </div>
</body>
</html>
<script>
    //Default search type
    var LastType = 'Stars';
    SearchType(LastType);

    //Function to change buttons and search terms depending on search type
    function SearchType(Type){
        //Remove class from LastType
        document.getElementById(LastType).classList.remove('bttnYellow');
        document.getElementById(LastType).classList.add('bttnGreen');
        LastType = Type;

        //Change button class
        document.getElementById(Type).classList.remove('bttnGreen');
        document.getElementById(Type).classList.add('bttnYellow');

        var list;
        switch (Type) {
            case 'Stars':
                list = `
                    <datalist id="SearchList">
                        <option value="5 Stars"></option>
                        <option value="4 Stars"></option>
                        <option value="3 Stars"></option>
                        <option value="2 Stars"></option>
                        <option value="1 Stars"></option>
                    </datalist>`;
               break;
            case 'Time':
                list = `
                    <datalist id="SearchList">
                        <option value="0-15"></option>
                        <option value="15-30"></option>
                        <option value="30-45"></option>
                        <option value="45-60"></option>
                        <option value="60-90"></option>
                        <option value="90-120"></option>
                    </datalist>`;
                break;
            case 'Tags':
                list = '<?php echo $TagsList; ?>';
                break;
            case 'Ingredient':
                list = '<?php echo $IngredientList; ?>';
                break;
            case 'Name':
                list = '<?php echo $NameList; ?>';
                break;
            case 'Score':
                list = `
                    <datalist id="SearchList">
                        <option value="Search for all based on buildability"></option>
                    </datalist>`;
                break;
            case 'Old':
                list = `
                    <datalist id="SearchList">
                        <option value="Search recipies using expiring"></option>
                    </datalist>`;
                break;
            case 'New':
                list = `
                    <datalist id="SearchList">
                        <option value="Search for new"></option>
                    </datalist>`;
                break;
        }
        //Apply the new list
        document.getElementById("Lists").innerHTML = list;
    }
    //Function to execute a search
    function SubmitSearch(){
        //Get the input from the search bar
        var SearchTerm = document.getElementById("SearchTerm").value;

        //Submit the data to server for processing
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Get result
                var Result = this.responseText;
                //Check for errors
                if(Result.includes('Submit Search Failed:')){ 
                    alert(Result);
                }
                //Display results
                else{
                    document.getElementById("Result").innerHTML=Result;
                    //Add event listeners to tiles
                    var ClickList = document.getElementsByClassName("clickable");
                    for (var i = 0; i < ClickList.length; i++) {
                        ClickList[i].addEventListener('click', function() {
                            window.open(this.getAttribute('data-href'), this.getAttribute('data-target'));
                        })
                    }
                }
            }
        };
        xmlhttp.open("GET","php/searchRecipes.php?type="+LastType+"&val="+SearchTerm,true);
        xmlhttp.send();    
    }
    //Function to add or remove recipe from menu
    function ChangeTile(TileId){
        var temp = document.getElementById("Button"+TileId);
        var MenuButton = document.getElementById("Button"+TileId);
        
        //Remove recipe from menu
        if(MenuButton.value == 'true'){
            //Change status in database
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);

                    //Change button class and status
                    MenuButton.classList.remove('bttnTallOrange');
                    MenuButton.classList.add('bttnTallYellow');
                    MenuButton.value = false;
                }
            };
            xmlhttp.open("GET","php/setMenu.php?id="+TileId+"&val=0",true);
            xmlhttp.send();
        }
        //Add recipe to menu
        else{
            //Change status in database
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);

                    //Change button class and status
                    MenuButton.classList.remove('bttnTallYellow');
                    MenuButton.classList.add('bttnTallOrange');
                    MenuButton.value = true;
                }
            };
            xmlhttp.open("GET","php/setMenu.php?id="+TileId+"&val=1",true);
            xmlhttp.send();                
        }
    }

</script>
