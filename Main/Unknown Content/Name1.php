<?php
    //Connect to database
    require_once './includes/dbconnect.php';
    
    //Get all the name1 ingredients
    $Query1 = "SELECT DISTINCT name1 FROM fridgemate_db.pantry ORDER BY name1";
    $ResultSet1 = $connection->query($Query1);

    //Print the ingredients for a datalist
    while ($row = $ResultSet1->fetch_row()) {
        echo "<option value=\"".$row[0]."\">".$row[0]."</option>";
    }

    //Exit
    db_disconnect($connection);
?>
