<?php
    //Includes
    require_once '../dbconnect.php';

    //Get the response data
    $str_json = file_get_contents('php://input');
    $response = json_decode($str_json);
    $Id = $response->{'Id'};
    $Name = $response->{'Name'};
    $Link = $response->{'Link'};
    $Image = $response->{'Image'};
    $Rating = $response->{'Rating'};
    $ActiveTime = $response->{'ActiveTime'};
    $PassiveTime = $response->{'PassiveTime'};
    $People = $response->{'People'};
    $MainList = $response->{'MainList'};
    $SupportList = $response->{'SupportList'};
    $SpicesList = $response->{'SpicesList'};
    $GarnishList = $response->{'GarnishList'};
    $Notes = $response->{'Notes'};
    $Steps = $response->{'Steps'};
    $Error = false;

    //Test here
    //$Id = false;

    //Check if it's a new recipe and give it an id
    if(!$Id){
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
        $Query = "INSERT INTO recipe (name,people,active,pasive,rating) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($Query);
        $stmt->bind_param("siddi", $Name, $People, $ActiveTime, $PassiveTime, $Rating);
        $stmt->execute();
        
        //Get the last id number inserted in database
        $Id = $connection->insert_id;
    } 

    //Get the last id number inserted in table
    

            /*
            //Trying to use this
            $Query1 = "SELECT recipe_id FROM recipe WHERE name = ?";
            $stmt = $connection->prepare($Query1);
            $stmt->bind_param("s", $Name);
            $stmt->execute();
            $result = $stmt->get_result();
    
            while ($row = $result->fetch_assoc()) {
                echo ($row['recipe_id']);
            }
            */
    
    
    //Get all the name1 ingredients
    
    

    //Print the ingredients for a datalist
    

    //Exit
    EditExit:
    echo($Error);
    $stmt->close();
    db_disconnect($connection);





    /*
    //Getting a single variable
    $Id = $response->{'Id'};

    //Getting the length of an array
    sizeof($response->{'MainList'})

    //Getting an array value
    $ingredient = $response->{'MainList'}[0];
    $Name1 = $ingredient->{'Name1'};
    */


?>