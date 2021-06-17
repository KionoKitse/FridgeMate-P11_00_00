<?php
    //Connect to database
    require_once  '../dbconnect.php';

    //Get the response data
    $str_json = file_get_contents('php://input');
    $response = json_decode($str_json, true);
    $Item_id = $response['Item_id']; 
    $Status = $response['Status'];
    $Cart = $response['Cart'];
    $Name1 = $response['Name1'];
    $Name2 = $response['Name2'];
    $Name3 = $response['Name3'];
    $Category = $response['Category'];
    $Purchase = $response['Purchase'];
    $Expires = $response['Expires'];
    $Recipe_id = $response['Recipe_id'];
    $ChangeList = $response['ChangeList'];
    $GroupCt = $response['GroupCt'];


    //Update the parameters for an existing recipe
    $Query = "UPDATE pantry SET name1=?, name2=?, name3=?, status=?, recipe_id=?, expires=?, purchase=?, category=?, cart=? WHERE item_id=?";
    $stmt = $connection->prepare($Query);
    $stmt->bind_param("sssiiissii", $Name1, $Name2, $Name3, $Status, $Recipe_id, $Expires, $Purchase, $Category, $Cart, $Item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    //Go through the ChangeList and update the groups
    foreach ($ChangeList as $row) {
        //Add to group
        if($row[1]){
            //It's a new group
            if(!$row[0]){
                $row[0] = $GroupCt+1;
            }

            //Add the item to the group
            $Query = "INSERT INTO fridgemate_db.group (group_id, item_id) VALUES (?,?)";
            $stmt = $connection->prepare($Query);
            $stmt->bind_param("ii", $row[0], $row[1]);
            $stmt->execute();
        }
        //Remove from group
        else{
            echo "Remove";
            $Query = "SELECT pk FROM fridgemate_db.group WHERE group_id=? AND item_id=?";
            $stmt = $connection->prepare($Query);
            $stmt->bind_param("ii", $row[0], $Item_id);
            $stmt->execute();
            $key = $stmt->get_result()->fetch_assoc();

            $Query = "DELETE FROM fridgemate_db.group WHERE pk=?";
            $stmt = $connection->prepare($Query);
            $stmt->bind_param("i", $key["pk"]);
            $stmt->execute();
        }
    }

    //exit
    $stmt->close();
    db_disconnect($connection);

    echo "Ingredient Updated";
?>