<?php
    //Connect to database
    include 'genFunc.php';
    require_once '../dbconnect.php';
    
    //Get all the name1 ingredients
    $Query1 = "SELECT DISTINCT recipe_id FROM recipe";
    $ResultSet1 = $connection->query($Query1);

    //Print the ingredients for a datalist
    while ($row = $ResultSet1->fetch_assoc()) {
        RecipeBuildability($row["recipe_id"], $connection,false);
    }

    //Exit
    db_disconnect($connection);
?>
