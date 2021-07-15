<?php
    //Get the response data
    $Notes = filter_var($_GET['notes'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

    //Generate the JSON file
    try {
        //Write to file
        $fp = fopen('../json/ShoppingNotes.json', 'w');
        fwrite($fp, json_encode($Notes));
        fclose($fp);
    }
    catch (exception $e) {
        echo $e;
    }
?>
