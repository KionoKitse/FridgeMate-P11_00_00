<?php
    //Connect to database
    require_once './includes/dbconnect.php';
    
    //Get all the name1 ingredients
    $Query1 = "SELECT DISTINCT name3 FROM fridgemate_db.pantry WHERE name1 = '".$_GET['name1']."' AND name2 = '".$_GET['name2']."' ORDER BY name3";
    $ResultSet1 = $connection->query($Query1);

    //Print the ingredients for a datalist
    echo "<datalist id=\"Name1\">";
    while ($row = $ResultSet1->fetch_row()) {
        echo "<option value=\"".$row[0]."\">".$row[0]."</option>";
    }
    echo "</datalist>";

    //Exit
    db_disconnect($connection);
?>