<!--Testing PHP generate page -->
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
    <script src="js/mainstyle.js"></script>
</head>
<?php
    //Variables
    $MainPercent = 75;
    $SupportPercent = 20;
    $SpicesPercent = 3;
    $GarnishPercent = 2;

    //Create some arrays
    $Main = array();
    $Support = array();
    $Spices = array();
    $Garnish = array();

    require_once 'dbconnect.php';
    $RecipeId = 1;

    $Query1 = "SELECT ingredient.item_id, ingredient.category, pantry.status, pantry.cart, pantry.recipe_id FROM ingredient
    INNER JOIN pantry ON ingredient.item_id=pantry.item_id Where ingredient.recipe_id = ?";
    $stmt = $connection->prepare($Query1);
    $stmt->bind_param("i", $RecipeId);
    $stmt->execute();
    $ResultSet1 = $stmt->get_result();

    //Count number of ingredients in each category
    /*
    $MainCt = 0;
    $SupportCt = 0;
    $SpicesCt = 0;
    $GarnishCt = 0;
    while ($row = $ResultSet1->fetch_assoc()) {
        switch ($row["category"]) {
            case 1:
                $MainCt++;
                break;
            case 2:
                $SupportCt++;
                break;
            case 3:
                $SpicesCt++;
                break;
            case 4:
                $GarnishCt++;
                break;    
        }
    }
    */

    //Check the status of each ingredient
    $HaveMain=0;
    $HaveSupport = 0;
    $HaveSpices = 0;
    $HaveGarnish = 0;
    while ($row = $ResultSet1->fetch_assoc()) {
        $Weight = 1;
        echo $row["item_id"]." ".$row["category"]." ".$row["status"]." ".$row["cart"]." ".$row["recipe_id"]." ";
        
        //Ingredient is not availible or in the cart
        if(!$row["status"] && !$row["cart"]){
            $Weight = 0;
            //Check if it is buildable
            if($row["recipe_id"]){
                $Query1 = "SELECT percent FROM recipe WHERE recipe_id = ?";
                $stmt = $connection->prepare($Query1);
                $stmt->bind_param("i", $row["recipe_id"]);
                $stmt->execute();
                $Result = $stmt->get_result()->fetch_assoc();
                //Ingredient is buildable
                if ($Result["percent"]>90){
                    $Weight = 1;
                    goto SendResult;
                }
            }
            //Find if there are any substitute ingredients
            $Query1 = "SELECT group_id FROM sets WHERE item_id = ?";
            $Query2 = "SELECT item_id FROM sets WHERE group_id IN (" . $Query1 . ") AND item_id != ?";
            $Query3 = "SELECT status, cart FROM pantry WHERE item_id IN (" . $Query2 . ")";
            $stmt = $connection->prepare($Query2);
            $stmt->bind_param("ii", $Ingredient["item_id"], $Ingredient["item_id"]);
            $stmt->execute();
            $ResultSet2 = $stmt->get_result();
            echo var_dump($ResultSet2);

            if($ResultSet2){
            //Check any of the items in the group are available
                while ($row1 = $ResultSet2->fetch_assoc()) {
                    echo ">>".$row["status"]." ".$row["cart"]." ";
                } 
            }

        }
        

        SendResult:
        echo "<br>";
    }






    $stmt->close();
    db_disconnect($connection);

    /*
    SELECT fridgemate_db.ingredient.item_id, fridgemate_db.ingredient.category, fridgemate_db.pantry.status, fridgemate_db.pantry.cart, fridgemate_db.pantry.recipe_id,
FROM fridgemate_db.ingredient
INNER JOIN fridgemate_db.pantry ON fridgemate_db.ingredient.item_id=fridgemate_db.pantry.item_id
Where fridgemate_db.ingredient.recipe_id = '1';
    */
?>