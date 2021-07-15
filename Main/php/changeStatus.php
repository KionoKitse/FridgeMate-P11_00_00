<?php
    //Includes
    //include 'genFunc.php';
    require_once '../dbconnect.php';

    //Get the response data
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $valA = filter_var($_GET['valA'], FILTER_SANITIZE_NUMBER_INT);
    $valB = filter_var($_GET['valB'], FILTER_SANITIZE_NUMBER_INT);

    //Adding to status
    if($valA){
        //Update the pantry information
        $Query = "UPDATE pantry SET status=1, purchase=CURDATE() WHERE item_id=".$id;
        $connection->query($Query);

        //Going from cart to status
        if($valB){
            $Query = "UPDATE pantry SET cart=0 WHERE item_id=".$id;
            $connection->query($Query);
            
            //Delete from shopping list
            $Query = "DELETE FROM shopping WHERE item_id=".$id;
            $connection->query($Query);
        }
    } 
    //Removing from status
    else {
        //Update the pantry information
        $Query = "UPDATE pantry SET status=0 WHERE item_id=".$id;
        $connection->query($Query);

        //Removing from status and cart
        if($valB){
            $Query = "UPDATE pantry SET cart=0 WHERE item_id=".$id;
            $connection->query($Query);
            //Delete from shopping list
            $Query = "DELETE FROM shopping WHERE item_id=".$id;
            $connection->query($Query);
        }
    }

    //exit
    db_disconnect($connection);
?>