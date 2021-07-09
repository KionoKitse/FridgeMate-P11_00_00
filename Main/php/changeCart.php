<?php
    //Includes
    require_once '../dbconnect.php';

    //Get the response data
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $val = filter_var($_GET['val'], FILTER_SANITIZE_NUMBER_INT);
    $purchased = filter_var($_GET['purchased'], FILTER_SANITIZE_NUMBER_INT);

    //Update the pantry
    $Query = "UPDATE pantry SET cart=".$val." WHERE item_id=".$id;
    $connection->query($Query);

    //Don't update buildability if purchased
    if(!purchased){
        //Update all of the sets status that use this item_id
        $Query1 = "UPDATE sets SET have=".$val." WHERE item_id=".$id;
        $connection->query($Query1);

        //Convert the value to a weight
        $Weight = 1;
        if($val==0) $Weight = -1;

        //Update the buildability score for all recipes using this ingredient
        UpdateScoreFromItem($id,$Weight,$connection);
    }

    //exit
    db_disconnect($connection);
?>