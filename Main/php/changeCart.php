<?php
    //Includes
    require_once '../dbconnect.php';

    //Get the response data
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $val = filter_var($_GET['val'], FILTER_SANITIZE_NUMBER_INT);

    //Update the parameters for an existing recipe
    $Query = "UPDATE pantry SET cart=? WHERE item_id=?";
    $stmt = $connection->prepare($Query);
    $stmt->bind_param("ii", $val, $id);
    $stmt->execute();

    //exit
    $stmt->close();
    db_disconnect($connection);
?>