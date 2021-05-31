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

    //Escape inputs
    $People = "five";
    //Check if it's a new recipe
    if(!$Id){
        //Check if there is a recipe with the same name
        $Query1 = "SELECT recipe_id FROM recipe WHERE name = '".$Name."'";
        $ResultSet = $connection->query($Query1);
        while ($row = $ResultSet->fetch_row()) {
            //If there is a recipe with the same name check if the link is the same
            $JsonPath = file_get_contents('../json/'.$row[0].'.json');
            $JsonData = json_decode($JsonPath, true);
            //If the name is the same and the link is the same don't add go to exit
            if($Link == $JsonData["Link"]){
                $Error = "Submit Edit Failed: Recipe already exists same name and link Recipe_Id=".$row[0];
                goto EditExit;
            }
        }
        //New recipe so add to database
        $stmt = $connection->prepare("INSERT INTO recipe (name,people,active,pasive,rating) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siddi", $Name, $People, $ActiveTime, $PassiveTime, $Rating);
        $stmt->execute();
        
        echo (var_dump($stmt));



    } else {
        echo ("Some id");
    }


    
    
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