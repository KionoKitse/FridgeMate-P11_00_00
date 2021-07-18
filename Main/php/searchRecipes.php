<?php
    //Includes
    include 'genFunc.php';

    //Connect to database
    require_once '../dbconnect.php';

    //Get input
    $type = filter_var($_GET['type'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $val = filter_var($_GET['val'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

    $Error = false;
    $Result = '';

    switch ($type) {
        case 'Stars':
            //Convert val to an integer
            $Search = intval($val);

            //Validate
            if(!$Search){
                $Error = "Submit Search Failed: Could not convert stars to a number";
                goto SearchExit;
            }
            if($Search<1 || $Search>5){
                $Error = "Submit Search Failed: Could not convert stars to a number between 1 & 5";
                goto SearchExit;
            }

            //Get results
            $Query = "SELECT *FROM recipe WHERE rating = ? ORDER BY percent DESC";
            $stmt = $connection->prepare($Query);
            $stmt->bind_param("i", $Search);
            $stmt->execute();
            $ResultSet1 = $stmt->get_result();
            $Result = GetSearchItems($ResultSet1);
            $stmt->close();
            break;

        case 'Time':
            //Split the val into two strings
            $pos = strpos($val, '-');
            if($pos === false){
                $Error = "Submit Search Failed: Could not split time should be int-int";
                goto SearchExit;
            }
            $Search = explode("-", $val);
            
            //Convert to int
            $Search[0] = intval($Search[0]);
            $Search[1] = intval($Search[1]);
            
            //Validate
            if($Search[0] >= $Search[1]){
                $Error = "Submit Search Failed: Time format should be int < int";
                goto SearchExit;
            }

            //Get results
            $Query = "SELECT * FROM recipe WHERE active > ? AND active <= ? ORDER BY percent DESC";
            $stmt = $connection->prepare($Query);
            $stmt->bind_param("ii", $Search[0], $Search[1]);
            $stmt->execute();
            $ResultSet1 = $stmt->get_result();
            $Result = GetSearchItems($ResultSet1);
            $stmt->close();
            break;

        case 'Tags':
            //Get results
            $Query1 = "SELECT DISTINCT recipe_id FROM tags WHERE tag = ?";
            $Query2 = "SELECT * FROM recipe WHERE recipe_id IN (".$Query1.") ORDER BY percent DESC";
            $stmt = $connection->prepare($Query2);
            $stmt->bind_param("s", $val);
            $stmt->execute();
            $ResultSet1 = $stmt->get_result();
            $Result = GetSearchItems($ResultSet1);
            $stmt->close();
            break;

        case 'Ingredient':
            //Split search term
            $Search = explode("-", $val);
            if(sizeof($Search)!=3){
                $Error = "Submit Search Failed: Ingredient format should be str-str-str";
                goto SearchExit;
            }
            //Get results
            $Query1 = "SELECT item_id FROM pantry WHERE name1=? AND name2=? AND name3=?";
            $Query2 = "SELECT DISTINCT recipe_id  FROM ingredient WHERE item_id IN (".$Query1.")";
            $Query3 = "SELECT * FROM recipe WHERE recipe_id IN (".$Query2.") ORDER BY percent DESC";
            $stmt = $connection->prepare($Query3);
            $stmt->bind_param("sss", $Search[0], $Search[1], $Search[2]);
            $stmt->execute();
            $ResultSet1 = $stmt->get_result();
            $Result = GetSearchItems($ResultSet1);
            $stmt->close();
            break;

        case 'Name':
            $Query1 = "SELECT * FROM recipe WHERE name=?";
            $stmt = $connection->prepare($Query1);
            $stmt->bind_param("s", $val);
            $stmt->execute();
            $ResultSet1 = $stmt->get_result();
            $Result = GetSearchItems($ResultSet1);
            $stmt->close();
            break;

        case 'Score':
            $Query1 = "SELECT * FROM recipe ORDER BY percent DESC";
            $ResultSet1 = $connection->query($Query1);
            $Result = GetSearchItems($ResultSet1);
            break;  

        case 'Old':
            $Query1 = "SELECT item_id FROM pantry where DATEDIFF(CURDATE(), purchase) > expires AND status = '1'";
            $Query2 = "SELECT recipe_id FROM ingredient WHERE item_id IN (".$Query1.")";
            $ResultSet1 = $connection->query($Query2);
            $Query1 = "SELECT * FROM recipe where RECIPE_ID IN (".$Query2.") ORDER BY percent DESC"; 
            $ResultSet2 = $connection->query($Query1);
            $Result = GetOlderItems($ResultSet2, $ResultSet1);
            break;   

        case 'New':
            $Query1 = "SELECT * FROM recipe WHERE rating = 0 ORDER BY percent DESC";
            $ResultSet1 = $connection->query($Query1);
            $Result = GetSearchItems($ResultSet1);
            break;
        default:
            $Error = "Submit Search Failed: Could not find type";
    }
    
    //Return results
    SearchExit:
    
    if($Error){
        echo $Error;
    }else{
        echo $Result;
    }

    //Exit
    db_disconnect($connection);
?>