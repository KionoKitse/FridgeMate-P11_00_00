<!DOCTYPE html>
<html>
<head>
    <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="js/mainstyle.js"></script>
    <script src="js/scripts.js"></script>

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
</head>


<!--Style specific to this page-->
<style>
    .clickable {
        cursor: pointer;
        border-bottom: 2pt solid #F2CC8F;
    }
    .bttnTallOrange{
        background-color: #E07A5F;
        height:20vw;
        border-radius: 5px; 
        border: 2px solid #FA9479; 
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
    .bttnGreen {
        background-color: #81B29A;
        width:100%;
        border-radius: 5px; 
        border: 2px solid #9BCCB4; 
		color: #3D405B;
        font-size: 3vw;
        cursor: pointer;
    }
</style>

<!-- Get the required information to render the page -->
<?php
    //Includes
    include 'php/genFunc.php';
    
    //Connect to database
    require_once 'dbconnect.php';

    //Get all the recipes that are on the menu
    $Query1 = "SELECT * FROM recipe WHERE menu = '1' ORDER BY percent DESC";
    $ResultSet1 = $connection->query($Query1);

    db_disconnect($connection);
?>

<body>
    <span id="error"></span>
    <div class="center">
        <?php
            echo'<p style="text-align: center; font-size: 5vw; color: #81B29A; font-weight: bold;">Recipe Menu</p>';
            echo GetMenuItems($ResultSet1);
        ?>
        <button class="bttnYellow" onclick="UpdateAllBuildability()">Update All Buildability</button>
        <button class="bttnGreen" onclick="location.href='./index.html'">Return Home</button>
    </div> 
</body>
</html>

<?php
    //Function to build the table of tiles for what is on the menu
    function GetMenuItems($ResultSet1){    
        //Create the menu table 
        $MenuTable = '<table id="Menu" style="width: 100%; border-collapse:collapse; border-spacing:0;">';
        while ($row = $ResultSet1->fetch_assoc()) {
            $NewTile = CreateTile($row,'Menu');
            $MenuTable .= $NewTile;
        }
        $MenuTable .= '</table>';

        //Indicate tiles are menu items and display all
        $MenuTable = str_replace("value = 'false'", "value = 'true'", $MenuTable);
        $MenuTable = str_replace("display:none", "display:table-row", $MenuTable);

        return $MenuTable;
    }
?>

<script>

    KeepItFresh();
    SetClickable();
    //Function to apply an event listener to all clickable elements
    function SetClickable(){
        //Script to set entire table row as clickable links
        //https://stackoverflow.com/questions/36113459/how-to-make-entire-tr-in-a-table-clickable-like-a-href
        var elements = document.getElementsByClassName('clickable');
        for (var i = 0; i < elements.length; i++) {
            var element = elements[i];
            element.addEventListener('click', function() {
                var href = this.dataset.href;
                if (href) {
                    window.location.assign(href);
                }
            })
        }
    }
    function RunThis(){
        var Name = document.getElementById("ButtonMenu5").value;
        document.getElementById("ButtonMenu5").value = !Name;
    }
    //Function to move a tile from one list to another
    function ChangeTile(TileId){
        var temp = document.getElementById("Button"+TileId);
        var OnMenu = document.getElementById("Button"+TileId).value;
        
        //Remove from menu display and remove from menu in database
        if(OnMenu == 'true'){
            //Change status in database
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);

                    //Remove the tile
                    RemoveTile(TileId);
                }
            };
            xmlhttp.open("GET","php/setMenu.php?id="+TileId+"&val=0",true);
            xmlhttp.send();
        }
        //Add to menu display and add to menu in database
        else{
            //Change status in database
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);

                    //Get the element
                    var Tile = document.getElementById(TileId).cloneNode(true); 
                    
                    //Extract number
                    var RecipeId = TileId.replace(/[^0-9]/g, ''); 
                    
                    //Convert tile to a menu tile
                    Tile.id = 'Menu'+RecipeId;
                    var Btn = Tile.children[2].children[0];
                    Btn.id = 'ButtonMenu'+RecipeId;
                    Btn.setAttribute('onclick','ChangeTile("Menu'+RecipeId+'")');
                    Btn.value = 'true';

                    //Add the event listener
                    Tile.children[0].addEventListener('click', function() {
                        var href = this.dataset.href;
                        if (href) {
                            window.location.assign(href);
                        }
                    })
                    Tile.children[1].addEventListener('click', function() {
                        var href = this.dataset.href;
                        if (href) {
                            window.location.assign(href);
                        }
                    })

                    //Add tile to menu table
                    var MenuTable = document.getElementById('Menu');
                    MenuTable.appendChild(Tile);
                }
            };
            xmlhttp.open("GET","php/setMenu.php?id="+TileId+"&val=1",true);
            xmlhttp.send();                
        }
    }
    //Function to call update all buildability scores
    function UpdateAllBuildability(){
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
        xmlhttp.open("GET", "php/recalculateBuildability.php", true);
        xmlhttp.send();
    }
    function KeepItFresh(){
        // JavaScript anonymous function
        (() => {
            if (window.localStorage) {
                if (!localStorage.getItem('reload')) {
                    localStorage['reload'] = true;
                    window.location.reload();
                } else {
  
                    localStorage.removeItem('reload');
                }
            }
        })(); 
    }
            
</script>

