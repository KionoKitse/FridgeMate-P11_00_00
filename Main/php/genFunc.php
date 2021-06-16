<?php
    //Function that returns an ingredient based on the requested ID
    function GetIngredient($Id, $Result){
        while ($row = mysqli_fetch_assoc($Result)) {
            if ($row["item_id"] == $Id) {
                //Set the pointer back to the beginning and send results
                mysqli_data_seek($Result, 0);
                return $row;
            }
        }
    }

    //Function that reads the JSON data
    function ReadJSON($Id){
        $JsonPath = file_get_contents('json/'.$Id.'.json');
        $JsonData = json_decode($JsonPath, true);
        return $JsonData;
    }
?>