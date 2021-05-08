<?php
    //Connect to database
    require_once '../dbconnect.php';

    //Get input
    $name1 = filter_var($_GET['name1'], FILTER_SANITIZE_STRING);
    $name2 = filter_var($_GET['name2'], FILTER_SANITIZE_STRING);
    
    //Get all the name1 ingredients
    $Query1 = "SELECT DISTINCT name3 FROM fridgemate_db.pantry WHERE name1 = '".$name1."' AND name2 = '".$name2."' ORDER BY name3";
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
