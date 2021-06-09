<?php
    //Connect to database
    require_once '../dbconnect.php';

    //Get input
    $name1 = filter_var($_GET['name1'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $name2 = filter_var($_GET['name2'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    
    //Get all the name1 ingredients
    $Query1 = "SELECT DISTINCT name3 FROM pantry WHERE name1 = ? AND name2 = ? ORDER BY name3";
    $stmt = $connection->prepare($Query1);
    $stmt->bind_param("ss", $name1, $name2);
    $stmt->execute();
    $ResultSet1 = $stmt->get_result();

    //Print the ingredients for a datalist
    while ($row = $ResultSet1->fetch_row()) {
        echo "<option value=\"".$row[0]."\">".$row[0]."</option>";
    }

    //Exit
    $stmt->close();
    db_disconnect($connection);
?>
