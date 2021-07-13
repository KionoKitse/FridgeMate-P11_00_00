<?php
    //Connect to database
    require_once '../dbconnect.php';
    
    //Get all the name1 ingredients
    $Query1 = "SELECT DISTINCT name1 FROM pantry ORDER BY name1";
    $ResultSet1 = $connection->query($Query1);

    //Print the ingredients for a datalist
    while ($row = $ResultSet1->fetch_assoc()) {
        echo "<option value=\"".$row["name1"]."\">".$row["name1"]."</option>";
    }

    //Exit
    db_disconnect($connection);
?>
