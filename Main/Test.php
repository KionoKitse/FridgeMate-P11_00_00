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
    $IngredientId = 1;

    //Get the recipe_id and percents to update
    $Query1 = "SELECT ingredient.recipe_id, ingredient.percent, recipe.percent FROM ingredient
        INNER JOIN recipe ON ingredient.recipe_id=recipe.recipe_id 
        Where ingredient.item_id = ?";
    $stmt = $connection->prepare($Query1);
    $stmt->bind_param("i", $IngredientId);
    $stmt->execute();
    $ResultSet1 = $stmt->get_result();  

    //Update the recipes
    $ResultArr = array();
    while($row = $ResultSet1->fetch_row()){
        $NewPercent = $row[1]+$row[2];
        $Query1 = "UPDATE recipe SET percent=? WHERE recipe_id=?";
        $stmt = $connection->prepare($Query1);
        $stmt->bind_param("si", $NewPercent, $row[0]);
        $stmt->execute();
    }



    //Calculate the new percentages
    //$NewPercentages = array_column($ResultArr, 1)+array_column($ResultArr, 2);
    //echo var_dump($NewPercentages); 

   





    $stmt->close();
    db_disconnect($connection);


    /*
    SELECT fridgemate_db.ingredient.item_id, fridgemate_db.ingredient.category, fridgemate_db.pantry.status, fridgemate_db.pantry.cart, fridgemate_db.pantry.recipe_id,
FROM fridgemate_db.ingredient
INNER JOIN fridgemate_db.pantry ON fridgemate_db.ingredient.item_id=fridgemate_db.pantry.item_id
Where fridgemate_db.ingredient.recipe_id = '1';
    */
?>