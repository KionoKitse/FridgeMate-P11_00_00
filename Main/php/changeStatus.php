<?php
    //Includes
    include 'genFunc.php';
    require_once '../dbconnect.php';

    //Get the response data
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $val = filter_var($_GET['val'], FILTER_SANITIZE_NUMBER_INT);
    $calc = filter_var($_GET['calc'], FILTER_SANITIZE_NUMBER_INT);

    //Update the pantry information
    $Query = "UPDATE pantry SET status=".$val.", purchase=CURDATE() WHERE item_id=".$id;
    $connection->query($Query);

    //Request to recalculate buildability
    if($calc){
        //Update all of the sets status that use this item_id
        $Query1 = "UPDATE sets SET have=".$val." WHERE item_id=".$id;
        $connection->query($Query1);

        //Convert the value to a weight, if removing also remove from cart
        //$Weight = 1;
        if($val==0){
            //Set the new weight
            //$Weight = -1;

            //Update the cart
            $Query = "UPDATE pantry SET cart=0 WHERE item_id=".$id;
            $connection->query($Query);
        }

        //Update the buildability score for all recipes using this ingredient
        //UpdateScoreFromItem($id,$Weight,$connection);
    }
    //Moving item from cart to status so don't need to recalculate
    else{
        //Update the cart
        $Query = "UPDATE pantry SET cart=0 WHERE item_id=".$id;
        $connection->query($Query);
    }

    //exit
    db_disconnect($connection);
?>