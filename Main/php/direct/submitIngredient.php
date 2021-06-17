<?php
    //Connect to database
    require_once  '../../dbconnect.php';

    //Get the response data
    $str_json = file_get_contents('php://input');
    $response = json_decode($str_json, true);
    $Item_id = $response['Item_id']; 
    $Status = $response['Status'];
    $Shopping = $response['Shopping'];
    $Name1 = $response['Name1'];
    $Name2 = $response['Name2'];
    $Name3 = $response['Name3'];
    $Category = $response['Category'];
    $Purchase = $response['Purchase'];
    $Expires = $response['Expires'];
    $Recipe_id = $response['Recipe_id'];
    $ChangeList = $response['ChangeList'];


    //Update the parameters for an existing recipe
    $Query = "UPDATE pantry SET name1=?, name2=?, name3=?, status=?, recipe_id=?, expires=?, purchase=?, category=?, shopping=? WHERE item_id=?";
    $stmt = $connection->prepare($Query);
    $stmt->bind_param("sssiiissii", $Name1, $Name2, $Name3, $Status, $Recipe_id, $Expires, $Purchase, $Category, $shopping, $Item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    //Go through the ChangeList and update the groups
    foreach ($ChangeList as $row) {
        echo "Current value of \$a: $v.\n";
    }

    //exit
    $stmt->close();
    db_disconnect($connection);
?>