<!--Testing PHP generate page -->
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
    <script src="js/mainstyle.js"></script>
</head>
<?php
    include 'php/genFunc.php';
    


    require_once 'dbconnect.php';

    //inputs
    $ItemId = 7;
    $Weight = 1;

    //UpdateScoreFromItem(7,1,$connection);
    $time_start = microtime(true);

    UpdateScoreFromItem(7,-1,$connection);

    $time_end = microtime(true);
    $execution_time = ($time_end - $time_start);
    echo '<b>Total Execution Time:</b> '.$execution_time;
   





    //$stmt->close();
    db_disconnect($connection);


    /*
    SELECT fridgemate_db.ingredient.item_id, fridgemate_db.ingredient.category, fridgemate_db.pantry.status, fridgemate_db.pantry.cart, fridgemate_db.pantry.recipe_id,
FROM fridgemate_db.ingredient
INNER JOIN fridgemate_db.pantry ON fridgemate_db.ingredient.item_id=fridgemate_db.pantry.item_id
Where fridgemate_db.ingredient.recipe_id = '1';
    */
?>