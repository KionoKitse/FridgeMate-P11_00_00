<?php
    //Includes
    require_once '../dbconnect.php';

    //Get the response data
    $str_json = file_get_contents('php://input');
    $response = json_decode($str_json, true);
    $RecipeId = $response['Id']; 
    $Name = $response['Name'];
    $Link = $response['Link'];
    $Image = $response['Image'];
    $Rating = $response['Rating'];
    $ActiveTime = $response['ActiveTime'];
    $PassiveTime = $response['PassiveTime'];
    $People = $response['People'];
    $PantryTable = $response['PantryTable'];
    $IngredientTable = $response['IngredientTable'];
    $TagsTable = $response['TagsTable'];
    $Notes = $response['Notes'];
    $StepDirections = $response['StepDirections'];

    //Variables
    $Error = false;

    //Check if it's a new recipe and give it an id
    try {
        if(!$RecipeId){
            //Check if there is a recipe with the same name
            $Query = "SELECT recipe_id FROM recipe WHERE name = ?";
            $stmt = $connection->prepare($Query);
            $stmt->bind_param("s", $Name);
            $stmt->execute();
            $ResultSet = $stmt->get_result();
            while ($row = $ResultSet->fetch_assoc()) {
                //If there is a recipe with the same name check if the link is the same
                $JsonPath = file_get_contents('../json/'.$row['recipe_id'].'.json');
                $JsonData = json_decode($JsonPath, true);
                //If the name is the same and the link is the same don't add go to exit
                if($Link == $JsonData["Link"]){
                    $Error = "Submit Edit Failed: Recipe already exists same name and link RecipeId=".$row['recipe_id'];
                    goto EditExit;
                }
            }
            //New recipe, so add to database
            $Query = "INSERT INTO recipe (name,people,active,passive,rating) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($Query);
            $stmt->bind_param("siddi", $Name, $People, $ActiveTime, $PassiveTime, $Rating);
            $stmt->execute();
            
            //Get the last id number inserted in database
            $RecipeId = $connection->insert_id;
        } else {
            //Update the parameters for an existing recipe
            $Query = "UPDATE recipe SET name=?, people=?, active=?, passive=?, rating=? WHERE recipe_id=?";
            $stmt = $connection->prepare($Query);
            $stmt->bind_param("siddii", $Name, $People, $ActiveTime, $PassiveTime, $Rating, $RecipeId);
            $stmt->execute();
            $result = $stmt->get_result();
            //$done = $stmt->affected_rows; //Not useful because if nothing change it returns 0
        }
    }
    catch (exception $e) {
        $Error = "Submit Edit Failed: Problem with getting the recipe id or adding the recipe";
        goto EditExit;
    }
    
    //Go through all the ingredients, add if needed and get unique Id
    $Item_Id;
    try {
        foreach ($PantryTable as $key => $row) { //Using key because I want to modify the array
            //Get the item_id for this ingredient must match name 1-3
            $Query = "SELECT item_id FROM pantry WHERE name1=? AND name2=? AND name3=?";
            $stmt = $connection->prepare($Query);
            $stmt->bind_param("sss", $row['Name1'], $row['Name2'], $row['Name3']);
            $stmt->execute();
            $ResultSet = $stmt->get_result();
            $result = $ResultSet->fetch_assoc();
            if($result){
                $Item_Id = $result['item_id'];
            } else {
                //Ingredient does not exist so add it
                $Query = "INSERT INTO pantry (name1,name2,name3,status,recipe_id) VALUES (?, ?, ?, '0', '0')";
                $stmt = $connection->prepare($Query);
                $stmt->bind_param("sss", $row['Name1'], $row['Name2'], $row['Name3']);
                $stmt->execute();
                $Item_Id = $connection->insert_id;
            }
            //Save the Item_Id to PantryTable
            $PantryTable[$key]['Item_Id'] = $Item_Id;
        }
    }
    catch (exception $e) {
        $Error = "Submit Edit Failed: Some error with adding ingredients or getting unique Id";
        goto EditExit;
    }
    
    //Clear the Ingredients table for the given Recipe_Id
    try {
        $Query = "DELETE FROM ingredient WHERE recipe_id=?";
        $stmt = $connection->prepare($Query);
        $stmt->bind_param("i", $RecipeId);
        $stmt->execute();
    }
    catch (exception $e) {
        $Error = "Submit Edit Failed: Could not remove data from Ingredients table";
        goto EditExit;
    }
    
    //Fill the Ingredients table with the submited data  
    try {
        foreach ($IngredientTable as $IngredientItem){
            //Get the Item_Id
            foreach($PantryTable as $PantryItem){
                if($PantryItem['ItemId'] == $IngredientItem['ItemId']){
                    $Item_Id = $PantryItem['Item_Id'];
                    break;
                }
            }
            //Insert ingredients into table
            $Query = "INSERT INTO ingredient (recipe_id, item_id, category, quantity, unit, step, prep) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($Query);
            $stmt->bind_param("iiissii", $RecipeId, $Item_Id, $IngredientItem['Category'], $IngredientItem['Quantity'], $IngredientItem['Unit'], $IngredientItem['Step'], $IngredientItem['Prep']);
            $stmt->execute();
        }
    }
    catch (exception $e) {
        $Error = "Submit Edit Failed: Could not finish adding data to Ingredients table";
        goto EditExit;
    }

    //Clear the tags table for the given Recipe_Id
    try {
        $Query = "DELETE FROM tags WHERE recipe_id=?";
        $stmt = $connection->prepare($Query);
        $stmt->bind_param("i", $RecipeId);
        $stmt->execute();
    }
    catch (exception $e) {
        $Error = "Submit Edit Failed: Could not remove data from Tags table";
        goto EditExit;
    }

    //Add the new tags
    try {
        foreach ($TagsTable as $Tag){
            //Insert tag into table 
            $Query = "INSERT INTO tags (recipe_id, tag) VALUES (?, ?)";
            $stmt = $connection->prepare($Query);
            $stmt->bind_param("is", $RecipeId, $Tag);
            $stmt->execute();
        }
    }
    catch (exception $e) {
        $Error = "Submit Edit Failed: Could not add to the Tags table";
        goto EditExit;
    }

    //Generate the JSON file
    try {
        $myObj = (object) [
            'Recipe_ID' => $RecipeId,
            'Name' => $Name,
            'Link' => $Link,
            'Image' => $Image,
            'People' => $People,
            'ActiveTime' => $ActiveTime,
            'PassiveTime' => $PassiveTime,
            'Rating' => $Rating,
            'Notes' => $Notes,
            'Steps' => $StepDirections
        ];
        //Write to file
        $fp = fopen('../json/'.$RecipeId.'.json', 'w');
        fwrite($fp, json_encode($myObj));
        fclose($fp);
    }
    catch (exception $e) {
        $Error = "Submit Edit Failed: Could not generate JSON file";
        goto EditExit;
    }

    //Exit
    EditExit:
    if($Error){
        echo($Error);
    }else{
        echo('Recipe updated');
    }
    
    $stmt->close();
    db_disconnect($connection);
?>