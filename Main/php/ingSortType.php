<?php
    //Connect to database
    require_once '../dbconnect.php';

    //Check the requested sorting method
    $type = filter_var($_GET['type'], FILTER_SANITIZE_STRING);

    //Build the query string
    $CategoryMode = false;
    if($type == 'A-Z'){
        //Get all ingredients sorted by alphabetically
        $Query1 = "SELECT item_id, status, name1, name2, name3, purchase  FROM pantry ORDER BY name1, name2, name3";
        $ResultSet1 = $connection->query($Query1);    
    } elseif($type == 'Cat'){
        //Get all ingredients sorted by category
        $Query1 = "SELECT item_id, status, category, name1, name2, name3, purchase FROM pantry ORDER BY category,status DESC,name1,name2,name3";
        $ResultSet1 = $connection->query($Query1);
        $CategoryMode = true;
    } elseif($type == 'Stat'){
        //Get all ingredients sorted by status
        $Query1 = "SELECT item_id, status, name1, name2, name3, purchase FROM pantry ORDER BY status DESC,name1,name2,name3";
        $ResultSet1 = $connection->query($Query1);
    } else{
        //Get all ingredients sorted by age
        $Query1 = "SELECT * FROM pantry order by status DESC, DATEDIFF(CURDATE(), purchase) DESC"; 
        $ResultSet1 = $connection->query($Query1);
    }

    //Print the ingredients into table rows
    $Category = 'Nothing Selected Yet';
    while ($row = $ResultSet1->fetch_assoc()) {
        //Special section for category
        if($CategoryMode){
            if($row["category"] != $Category){
                echo '<tr>';
                    echo '<th colspan="3" class="catHeader"> '.$row["category"].' </th>';
                echo '</tr>';
                $Category = $row["category"];
            }
        }
        
        echo '<tr>';
            //Print status
            echo '<td  style="width: 8vw;">';
                echo '<label class="switch">';
                //Print switch with current status
                if($row["status"]=='1'){
                    echo '<input id="'.$row["item_id"].'" onchange="changeStatus(\''.$row["item_id"].'\')" type="checkbox" checked>';
                }
                else{
                    echo '<input id="'.$row["item_id"].'" onchange="changeStatus(\''.$row["item_id"].'\')" type="checkbox">';
                }
                    echo '<span class="slider round"></span>';
                echo '</label>';
            echo '</td>';

            //Print name
            echo '<td>';
                echo '<a class="ingLink" href="ingredient.php?id='.$row["item_id"].'">';
                    echo $row["name1"].' '.$row["name2"].' '.$row["name3"];
                echo '</a>';
            echo '</td>';
            
            //Print date
            echo '<td style="text-align: right">';
                    echo $row["purchase"];
            echo '</td>';
        echo '</tr>';
    }

    //Exit
    db_disconnect($connection);
?>
