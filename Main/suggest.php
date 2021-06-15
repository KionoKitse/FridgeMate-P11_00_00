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
    require_once 'dbconnect.php';

    //Get all the recipes that are on the menu
    $Query1 = "SELECT * FROM recipe WHERE menu = '1' ORDER BY percent DESC";
    $ResultSet1 = $connection->query($Query1);
    //Get all the recipes sorted by buildability then Rating
    $Query1 = "SELECT * FROM recipe ORDER BY percent DESC, rating DESC";
    $ResultSet2 = $connection->query($Query1);
    //Get all the ingredients that are on expiry notice
    $Query1 = "SELECT item_id FROM pantry where DATEDIFF(CURDATE(), purchase) > expires AND status = '1'";
    //Get all the recipes that use these ingredients
    $Query2 = "SELECT recipe_id FROM ingredient WHERE item_id IN (".$Query1.")";
    $ResultSet3 = $connection->query($Query2);
    //Get the recipe information
    $Query1 = "SELECT * FROM recipe where RECIPE_ID IN (".$Query2.")"; 
    $ResultSet4 = $connection->query($Query1);

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
            echo'<p style="text-align: center; font-size: 5vw; color: #81B29A; font-weight: bold;">Expiry Notice</p>';
            echo GetOlderItems($ResultSet4, $ResultSet3);
        ?>
    </div>
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
    function GetOlderItems($ResultSet4,$ResultSet3){
        //Count the number of older ingredients in each recipe
        $OlderCt = [];
        while ($row = $ResultSet3->fetch_assoc()) {
            //echo var_dump($row);
            array_push($OlderCt, $row["recipe_id"]);
        }
        $OlderCt = array_count_values($OlderCt);

        //Create the older table 
        $OlderTable = '<table id="Older" style="width: 100%; border-collapse:collapse; border-spacing:0;">';
        while ($row = $ResultSet4->fetch_assoc()) {
            $NewTile = CreateTile($row,'Older');

            //Replace percent value by older ingredients count
            $Id = $row["recipe_id"];
            $Percent = (int)$row["percent"];
            $Search = $Percent.'% <i style="color: #81B29A;" class="fas fa-clipboard-check"></i>';
            $Replace = $OlderCt[$Id].' <i style="color: #81B29A;" class="fas fa-clipboard-check"></i>';
            $NewTile = str_replace($Search, $Replace, $NewTile); //Search / Replace
            
            $OlderTable .= $NewTile;
        }

        //Set the first 5 tiles to active display
        for($i = 0; $i < 2; $i++){
            $pos = strpos($OlderTable, "display:none");
            if ($pos !== false) {
                $OlderTable = substr_replace($OlderTable, "display:table-row", $pos, strlen("display:none"));
            }
        }

        //Add the last row
        $OlderTable .= '<tr><td colspan="3"><button id=OlderShow class="bttnWide"  onclick="ShowMoreLessTiles(\'Older\')"><span style="color: #3D405B;"><i class="fas fa-chevron-down"></i> Show More <i class="fas fa-chevron-down"></i></span></button></td></tr>';
        $OlderTable .= '</table>';
        //Add a display counter
        $OlderTable .= '<input type="hidden" id="OlderDisp" value="'.$i.'">';
        return $OlderTable;

        
    }
    //Function to create a tile element
    function CreateTile($RecipeObj,$Type){
        $Id = $RecipeObj["recipe_id"];
        $Image = $RecipeObj["image"];
        $Name = $RecipeObj["name"];
        $Rating = (int)$RecipeObj["rating"];
        $Percent = (int)$RecipeObj["percent"];
        $Active = (int)$RecipeObj["active"];
        $Passive = (int)$RecipeObj["passive"];
        $People = (int)$RecipeObj["people"];
        $Top1 = $RecipeObj["top1"];
        $Top2 = $RecipeObj["top2"];
        $Top3 = $RecipeObj["top3"];
        $Top4 = $RecipeObj["top4"];
        $Top5 = $RecipeObj["top5"];
        $Top6 = $RecipeObj["top6"];

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
