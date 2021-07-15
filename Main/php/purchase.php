<?php
    //Connect to database
    require_once '../dbconnect.php';

    //update pantry
    $Query = "UPDATE pantry SET status=1, cart=0, purchase=CURDATE() WHERE item_id 
    IN (SELECT item_id FROM shopping WHERE cart = 1)";
    $connection->query($Query);

    //Delete from shopping list 
    $Query = "DELETE FROM shopping WHERE cart=1";
    $connection->query($Query);
    
    //Exit
    db_disconnect($connection);
?>
