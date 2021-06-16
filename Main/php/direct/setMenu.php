<?php
    //Connect to database
    require_once  $_SERVER['DOCUMENT_ROOT'] . '/dbconnect.php';

    //Get the response data
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $val = filter_var($_GET['val'], FILTER_SANITIZE_NUMBER_INT);

    echo $id;
    echo $val;

    //Update the parameters for an existing recipe
    $Query = "UPDATE recipe SET menu=? WHERE recipe_id=?";
    $stmt = $connection->prepare($Query);
    $stmt->bind_param("ii", $val, $id);
    $stmt->execute();

    //exit
    $stmt->close();
    db_disconnect($connection);
?>
