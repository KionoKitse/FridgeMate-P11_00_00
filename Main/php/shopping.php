<?php
    //Connect to database
    include 'genFunc.php';
    require_once '../dbconnect.php';
    $Result = "";

    //Get all ingredients sorted by category
    $Query1 = "SELECT pantry.item_id, pantry.name1, pantry.name2, pantry.name3, pantry.category, shopping.cart FROM shopping
    INNER JOIN pantry ON shopping.item_id=pantry.item_id ORDER BY pantry.category";
    $ResultSet1 = $connection->query($Query1);

    //Get the shopping notes
    $JsonPath = file_get_contents('../json/ShoppingNotes.json');
    $JsonNotes = json_decode($JsonPath, true);

    $Category = 'Nothing Selected Yet';
    //Print the items in the cart
    while ($row = $ResultSet1->fetch_assoc()) {
        //Print category header
        if($row["category"] != $Category){
            $Result .= '<tr>';
                $Result .= '<th colspan="3" class="catHeader"> '.$row["category"].' </th>';
            $Result .= '</tr>';
            $Category = $row["category"];
        }

        //Print ingredient information
        $Result .= '<tr>';
            //Check box status
            $Result .= '<td style="width: 8vw;">';
                $Result .= '<label class="switch">';
                    //Item is in cart
                    if($row["cart"]){
                        $Result .= '<input id="'.$row["item_id"].'" onchange="updateCart('.$row["item_id"].')" type="checkbox" checked>';
                    }
                    //Item not in cart
                    else{
                        $Result .= '<input id="'.$row["item_id"].'" onchange="updateCart('.$row["item_id"].')" type="checkbox">';
                    }
                    $Result .= '<span class="slider round"></span>';
                $Result .= '</label>';
            $Result .= '</td>';
            
            //Names
            $Result .= '<td style="white-space: nowrap">'.$row["name1"].' '.$row["name2"].' '.$row["name3"].'</td>';

            //List with recipes
            $Query2 = "SELECT name FROM recipe WHERE recipe_id 
                IN (SELECT DISTINCT recipe_id FROM ingredient WHERE item_id = ".$row["item_id"].") 
                ORDER BY rating DESC ";
            $ResultSet2 = $connection->query($Query2);
            $Result .= '<td>';
                $Result .= '<select>';  
                while ($row2 = $ResultSet2->fetch_assoc()) {
                    $Result .= '<option>'.$row2["name"].'</option>'; 
                }
                $Result .= '</select>';  
            $Result .= '</td>';
        $Result .= '</tr>';

    }
    //Print the shopping notes
    $Result .= '<tr>';
        $Result .= '<td style="vertical-align: middle;">';
            $Result .= '<button id="Notes" onclick="SaveNotes()" class="bttnTall">';
                $Result .= '<i style="color: #3D405B" class="fas fa-kiwi-bird"></i>';
            $Result .= '</button>';
        $Result .= '</td>';
    $Result .= '<td colspan="2"><textarea style="width:98%;" rows="5" id="NotesBox">'.$JsonNotes.'</textarea></td>';
    $Result .= '</tr>';

    //Button for purchased
    $Result .= '<tr><td colspan="3"><button class="bttnYellow" onclick="SubmitPurchase()">Purchase</button></td></tr>';
    


    echo $Result;

?>