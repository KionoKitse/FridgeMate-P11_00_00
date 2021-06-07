</div>
<!DOCTYPE html>
<html>

<head>
    <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="js/mainstyle.js"></script>
</head>

<!--Style specific to this page-->
<style>
    .clickable {
        cursor: pointer;
        border-bottom: 2pt solid #F2CC8F;
    }
    .bttnTall {
        background-color: #F2CC8F;
        height:20vw;
        border-radius: 5px; 
        border: 2px solid #FFE6A9; 
    }
    .bttnWide {
        background-color: #81B29A;
        width:100%;
        border-radius: 5px; 
        border: 2px solid #9BCCB4; 
    }
</style>

<!-- Get the required information to render the page -->
<?php
    include 'php/genFunc.php';
    require_once 'dbconnect.php';

    //Get all the recipes that are on the menu
    $Query1 = "SELECT * FROM recipe WHERE menu = '1' ORDER BY percent DESC";
    $ResultSet1 = $connection->query($Query1);
    //Get all the recipes sorted by buildability then Rating
    $Query2 = "SELECT * FROM recipe ORDER BY percent DESC, rating DESC";
    $ResultSet2 = $connection->query($Query2);
    
    //echo str_replace("display:none;","display:table-row;",'A display:none; B);
    //$NewTile = str_replace("display:none;", "display:table-row;", $NewTile);

    db_disconnect($connection);
?>

<body>
    </div>
    <div class="center">
        <?php
            echo'<p style="text-align: center; font-size: 5vw; color: #81B29A; font-weight: bold;">Recipe Menu</p>';
            echo GetMenuItems($ResultSet1);
            echo'<p style="text-align: center; font-size: 5vw; color: #81B29A; font-weight: bold;">Buildability</p>';
            echo GetBuildItems($ResultSet2);
        ?>
    </div>
    <button onclick="RunThis()">Click me</button>
    <div><p>
        (section old produce -> buildability) - users can add a recipe to the menu and display 10 more
    </p></div>
</body>


<script>
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

    //Function to remove a tile
    function RemoveTile(TileId){
        var Tile = document.getElementById(TileId);
        Tile.remove();
    }
    function RunThis(){
        var Name = document.getElementById("ButtonMenu5").value;
        document.getElementById("ButtonMenu5").value = !Name;
    }
    function ChangeTile(TileId){
        var temp = document.getElementById("Button"+TileId);
        var OnMenu = document.getElementById("Button"+TileId).value;
        //Remove from menu display and remove from menu in database
        if(OnMenu == 'true'){
            RemoveTile(TileId);
        }
        //Add to menu display and add to menu in database
        else{
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

            //Add tile to menu table
            var MenuTable = document.getElementById('Menu');
            MenuTable.children[0].appendChild(Tile);
        }
    }
    function ShowMoreLessTiles(TableId){
        //Get the table and the number of entries displayed
        var Table = document.getElementById(TableId);
        var DispCt = Number(document.getElementById(TableId+'Disp').value);

        //Variables
        var i, NextCt;

        //Display more tiles
        if(DispCt<Table.rows.length-1){
            //Decide the next count to go to
            if(DispCt+2 > Table.rows.length-2){
                NextCt = Table.rows.length-1;
            } else {
                NextCt = DispCt+2;
            }

            //Set the visability
            for (i = DispCt; i < NextCt; i++) {
                Table.rows[i].style.display = 'table-row';
            }

            //Change button if no more to display
            if(i == Table.rows.length-1){
                var Bttn = document.getElementById(TableId+'Show');
                Bttn.style.backgroundColor = '#E07A5F';
                Bttn.style.borderColor = '#FA9479';
            }
        }
        //Display less tiles
        else{
            //Set the visability
            for (i = 2; i < Table.rows.length-1; i++) {
                Table.rows[i].style.display = 'none';
            }

            //Change button if no more to display
            var Bttn = document.getElementById(TableId+'Show');
            Bttn.style.backgroundColor = '#81B29A';
            Bttn.style.borderColor = '#9BCCB4';
            i=2;
        } 

        //Save displayed count
        document.getElementById(TableId+'Disp').value = i;
    }
</script>




<?php
    //Function to get the MySQL data
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
    function GetBuildItems($ResultSet2){
        //Create the build table 
        $BuildTable = '<table id="Build" style="width: 100%; border-collapse:collapse; border-spacing:0;">';
        while ($row = $ResultSet2->fetch_assoc()) {
            $NewTile = CreateTile($row,'Build');
            
            $BuildTable .= $NewTile;
        }

        //Set the first 5 tiles to active display
        for($i = 0; $i < 2; $i++){
            $pos = strpos($BuildTable, "display:none");
            if ($pos !== false) {
                $BuildTable = substr_replace($BuildTable, "display:table-row", $pos, strlen("display:none"));
            }
        }

        //Add the last row
        $BuildTable .= '<tr><td colspan="3"><button id=BuildShow class="bttnWide"  onclick="ShowMoreLessTiles(\'Build\')"><span style="color: #3D405B;"><i class="fas fa-chevron-down"></i> Show More <i class="fas fa-chevron-down"></i></span></button></td></tr>';
        $BuildTable .= '</table>';
        //Add a display counter
        $BuildTable .= '<input type="hidden" id="BuildDisp" value="'.$i.'">';
        return $BuildTable;
    }
    //Function to create a tile element
    function CreateTile($RecipeObj,$Type){
        $Id = $RecipeObj["RECIPE_ID"];
        $Image = $RecipeObj["IMAGE"];
        $Name = $RecipeObj["NAME"];
        $Rating = (int)$RecipeObj["RATING"];
        $Percent = (int)$RecipeObj["PERCENT"];
        $Active = (int)$RecipeObj["ACTIVE"];
        $Passive = (int)$RecipeObj["PASSIVE"];
        $People = (int)$RecipeObj["PEOPLE"];
        $Top1 = $RecipeObj["TOP1"];
        $Top2 = $RecipeObj["TOP2"];
        $Top3 = $RecipeObj["TOP3"];
        $Top4 = $RecipeObj["TOP4"];
        $Top5 = $RecipeObj["TOP5"];
        $Top6 = $RecipeObj["TOP6"];

        $Result = <<<EOT
            <!--New Item-->
            <tr id ="$Type$Id" style="display:none">
                <!--Image-->
                <td style="width: 30%;" class="clickable" data-href="recipe.php?id=$Id">
                    <img src="$Image" alt="recipe page" style="width: 100%">
                </td>
                <!--Text area-->
                <td class="clickable" data-href="recipe.php?id=$Id">
                    <table style="width: 100%;">
                        <!--Title and stats-->
                        <tr>
                            <td colspan="5" style="color: #81B29A; font-weight: bold;">$Name</td>
                        </tr>
                        <tr>
                            <td style="color: #E07A5F;">$Rating/5 <i style="color: #81B29A;" class="fa fa-star"></i></td>
                            <td style="color: #E07A5F;">$Percent% <i style="color: #81B29A;" class="fas fa-clipboard-check"></i></td>
                            <td style="color: #E07A5F;">$Active <i style="color: #81B29A;" class="far fa-clock"></i></td>
                            <td style="color: #E07A5F;">$Passive <i style="color: #81B29A;" class="fa fa-clock"></i></td>
                            <td style="color: #E07A5F;">$People <i style="color: #81B29A;" class="fas fa-user-astronaut"></i></td>
                            </tr>
                    </table>
                    <!--Ingredients-->
                    <table style="width: 100%;">
                        <tr>
                            <td>$Top1</td>
                            <td>$Top4</td>
                        </tr>
                        <tr>
                            <td>$Top2</td>
                            <td>$Top5</td>
                        </tr>
                        <tr>
                            <td>$Top3</td>
                            <td>$Top6</td>
                        </tr>
                    </table>
                </td>
                    
                <td class="clickable" style="vertical-align: middle;"><button id="Button$Type$Id" onclick="ChangeTile('$Type$Id')" value = 'false' class="bttnTall"><i style="color: #3D405B" class="fas fa-kiwi-bird fa-flip-horizontal"></i></button></td>

            </tr>
        EOT;
        //<td align="right" style="vertical-align: top;"><i id="Button$Type$Id" spellcheck='false' onclick="ChangeTile('$Type$Id')" style="color: #F2CC8F;" class="fas fa-kiwi-bird fa-flip-horizontal"></i></td>
        return $Result;
    }
?>
