<?php
    //Connect to database
    require_once '../dbconnect.php';
    $Result = "";

    //Get all the ingredients that I don't have and are not in the cart
    $Query1 = "SELECT item_id, name1, name2, name3, weight FROM pantry 
    WHERE status = 0 AND cart = 0 ORDER BY weight DESC";
    $ResultSet1 = $connection->query($Query1);

    while ($row = $ResultSet1->fetch_assoc()) {
        $weight = floatval($row["weight"]);
        $weight = round($weight, 2);

        //Print item row
        $Result .= '<tr>';
            //Slider column
            $Result .= '<td style="width: 8vw;">';
                $Result .= '<label class="switch">';
                    $Result .= '<input id="'.$row["item_id"].'" onchange="updateCart('.$row["item_id"].')" type="checkbox">';
                    $Result .= '<span class="slider round"></span>';
                $Result .= '</label>';
            $Result .= '</td>';

            //Percent column
            $Result .= '<td style="width: 9vw;">'.$weight.'</td>';

            //Name column
            $Result .= '<td>'.$row["name1"].' '.$row["name2"].' '.$row["name3"].'</td>';

        $Result .= '<tr>';
    }


    echo $Result;
?>