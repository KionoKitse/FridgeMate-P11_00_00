<?php
    //Includes
    //include 'genFunc.php';
    require_once '../dbconnect.php';

    //Get the response data
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $val = filter_var($_GET['val'], FILTER_SANITIZE_NUMBER_INT);

    //Update the pantry
    $Query = "UPDATE pantry SET cart=".$val." WHERE item_id=".$id;
    $connection->query($Query);

    //Add/remove from shopping list
    if($val){
        //Add to shopping list
        $Query = "UPDATE shopping SET cart=".$val." WHERE item_id=".$id;
        $connection->query($Query);
    } else {
        //Delete from shopping list
        $Query = "DELETE FROM shopping WHERE item_id=".$id;
        $connection->query($Query);
    }

    //exit
    db_disconnect($connection);
?>