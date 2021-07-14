<?php
    //Connect to database
    include 'genFunc.php';
    require_once '../dbconnect.php';

    //Get the response data
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    
    //Calculate buildability
    RecipeBuildability($id, $connection,false);

    //Exit
    db_disconnect($connection);
?>
