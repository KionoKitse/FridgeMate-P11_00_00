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
    
    $RecipeId = 1;
    echo CalcBuildability($RecipeId,$connection);
    $RecipeId = 2;
    echo CalcBuildability($RecipeId,$connection);
    $RecipeId = 3;
    echo CalcBuildability($RecipeId,$connection);
    $RecipeId = 4;
    echo CalcBuildability($RecipeId,$connection);

    

    $Query1 = "SELECT ingredient.item_id, ingredient.percent, ingredient.category, pantry.status, pantry.cart, pantry.recipe_id FROM ingredient
    INNER JOIN pantry ON ingredient.item_id=pantry.item_id Where ingredient.recipe_id = ?";
    $stmt = $connection->prepare($Query1);
    $stmt->bind_param("i", $RecipeId);
    $stmt->execute();
    $ResultSet1 = $stmt->get_result();

    //Check the status of each ingredient
    $BuildabilityScore=0;
    while ($row = $ResultSet1->fetch_assoc()) {
        $Weight = 1;
        echo "item_id: ".$row["item_id"]."<br>";
        echo "percent: ".$row["percent"]."<br>";
        echo "category: ".$row["category"]."<br>";
        echo "status: ".$row["status"]."<br>";
        echo "cart: ".$row["cart"]."<br>";
        echo "recipe_id: ".$row["recipe_id"]."<br>";
        
        //Ingredient is not availible or in the cart
        if(!$row["status"] && !$row["cart"]){
            echo "Ingredient not availible <br>";
            $Weight = 0;
            //Check if it is buildable
            if($row["recipe_id"]){
                echo "Possible to build - ";
                $Query1 = "SELECT percent FROM recipe WHERE recipe_id = ?";
                $stmt = $connection->prepare($Query1);
                $stmt->bind_param("i", $row["recipe_id"]);
                $stmt->execute();
                $Result = $stmt->get_result()->fetch_assoc();
                echo $Result["percent"]." - ";
                //Ingredient is buildable
                if ($Result["percent"]>90){
                    echo "Use build <br>";
                    $Weight = 1;
                    goto CalcScore;
                }
                echo "Build not high enough <br>";
            }
           

            //Find if there are any substitute ingredients
            echo "Checking subs<br>";
            $Query1 = "SELECT group_id FROM sets WHERE item_id = ?";
            $Query2 = "SELECT DISTINCT item_id FROM sets WHERE group_id IN (" . $Query1 . ") AND item_id != ?";
            $Query3 = "SELECT item_id, status, cart FROM pantry WHERE item_id IN (" . $Query2 . ")";
            $stmt = $connection->prepare($Query3);
            $stmt->bind_param("ii", $row["item_id"], $row["item_id"]);
            $stmt->execute();
            $ResultSet2 = $stmt->get_result();

            
            if($ResultSet2->num_rows > 0){
            echo "Possible to sub <br>";
            //Check any of the items in the group are available
                while ($row1 = $ResultSet2->fetch_assoc()) {
                    echo ">>".$row1["item_id"]." ".$row1["status"]." ".$row1["cart"]."<br>";
                    if ($row1["status"] || $row1["cart"]){
                        echo "Sub availible - exit<br>";
                        $Weight = 0.5;
                        goto CalcScore;
                    }
                } 
            }

        }
        
        
        //Calculate the score
        CalcScore:
        echo "Weight: ".$Weight."<br>";

        $Score = $row["percent"]*$Weight;
        $BuildabilityScore += $Score;
        echo "Score: ".$Score."<br>";
        echo "BuildabilityScore: ".$BuildabilityScore."<br>";
        echo "<br>";
    }
    //Update the buildability score






    $stmt->close();
    db_disconnect($connection);


    /*
    SELECT fridgemate_db.ingredient.item_id, fridgemate_db.ingredient.category, fridgemate_db.pantry.status, fridgemate_db.pantry.cart, fridgemate_db.pantry.recipe_id,
FROM fridgemate_db.ingredient
INNER JOIN fridgemate_db.pantry ON fridgemate_db.ingredient.item_id=fridgemate_db.pantry.item_id
Where fridgemate_db.ingredient.recipe_id = '1';
    */
?>