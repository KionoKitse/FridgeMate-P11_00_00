<!DOCTYPE html>
<html>
<head>
  <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
  <script src="js/mainstyle.js"></script>
  <script src="js/scripts.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<!-- Style for this page -->
<style>
  .bttnGreen {
    background-color: #81B29A;
    width:100%;
    border-radius: 5px; 
    border: 2px solid #9BCCB4; 
    color: #3D405B;
  }
  .bttnYellow {
    background-color: #F2CC8F;
    width:100%;
    border-radius: 5px; 
    border: 2px solid #FFE6A9; 
    color: #3D405B;
  }
  .catHeader{
    text-align: left;
  }
  .bttnTall {
        background-color: #F2CC8F;
        height:20vw;
        border-radius: 5px; 
        border: 2px solid #FFE6A9; 
        color: #3D405B;
    }
</style>
<!-- Style for sliders -->
<style>
  .switch {
    position: relative;
    display: inline-block;
    width: 7vw;
    height: 4vw;
  }

  .switch input { 
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 3.2vw;
    width: 3.2vw;
    left: 0.3vw;
    bottom: 0.3vw;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked + .slider {
    background-color: #2196F3;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
    -webkit-transform: translateX(3.2vw);
    -ms-transform: translateX(3.2vw);
    transform: translateX(3.2vw);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }
</style>

<!-- Get the required information to render the page -->
<?php
  require_once 'dbconnect.php';

  //Get all ingredients sorted by category
  $Query1 = "SELECT pantry.item_id, pantry.name1, pantry.name2, pantry.name3, pantry.category, shopping.cart FROM shopping
  INNER JOIN pantry ON shopping.item_id=pantry.item_id ORDER BY pantry.category";
  $ResultSet1 = $connection->query($Query1);

  //Get the shopping notes
  $JsonPath = file_get_contents('json/ShoppingNotes.json');
  $JsonNotes = json_decode($JsonPath, true);

?>

<body>
<span id="error"></span>

<div class="center">
    <p style="text-align: center; font-size: 5vw; color: #81B29A; font-weight: bold;">Shopping</p>
    <table style="width: 100%; border-collapse:collapse; border-spacing:0;">
      <?php
        $Category = 'Nothing Selected Yet';
        while ($row = $ResultSet1->fetch_assoc()) {
          //Print category header
          if($row["category"] != $Category){
            echo '<tr>';
              echo '<th colspan="3" class="catHeader"> '.$row["category"].' </th>';
            echo '</tr>';
            $Category = $row["category"];
          }

          //Print ingredient information
          echo '<tr>';
            //Check box status
            echo '<td style="width: 8vw;">';
              echo '<label class="switch">';
                //Item is in cart
                if($row["cart"]){
                  echo '<input id="'.$row["item_id"].'" onchange="updateCart('.$row["item_id"].')" type="checkbox" checked>';
                }
                //Item not in cart
                else{
                  echo '<input id="'.$row["item_id"].'" onchange="updateCart('.$row["item_id"].')" type="checkbox">';
                }
                echo '<span class="slider round"></span>';
              echo '</label>';
            echo '</td>';
          
            //Names
            echo '<td style="white-space: nowrap">'.$row["name1"].' '.$row["name2"].' '.$row["name3"].'</td>';

            //List with recipes
            $Query2 = "SELECT name FROM recipe WHERE recipe_id 
                IN (SELECT DISTINCT recipe_id FROM ingredient WHERE item_id = ".$row["item_id"].") 
                ORDER BY rating DESC ";
            $ResultSet2 = $connection->query($Query2);
            echo '<td>';
              echo '<select>';  
              while ($row2 = $ResultSet2->fetch_assoc()) {
                echo '<option>'.$row2["name"].'</option>'; 
              }
              echo '</select>';  
            echo '</td>';
          echo '</tr>';
        }

        //Print the shopping notes
        echo '<tr>';
          echo '<td style="vertical-align: middle;">';
            echo '<button id="Notes" onclick="SaveNotes()" class="bttnTall">';
              echo '<i style="color: #3D405B" class="fas fa-kiwi-bird"></i>';
            echo '</button>';
          echo '</td>';
          echo '<td colspan="2"><textarea style="width:98%;" rows="5" id="NotesBox">'.$JsonNotes.'</textarea></td>';
        echo  '</tr>';
      ?>
      <tr>
        <td colspan="3">
          <button class="bttnYellow" onclick="SubmitPurchase()">Purchase</button>
        </td>
      </tr>
    
    </table>
    <table id="ResultTable" style="width: 100%; border-collapse:collapse; border-spacing:0;">
    </table>
</div>
</body>

<Script>
    function updateCart(id){
        //Get the value
        var val = document.getElementById(id).checked ? 1 : 0;
        var calc = 0;

        //If removing from cart
        if(!val){
            var Remove = confirm("Would you like to remove from shopping list?");
            if (Remove){
                //Remove from the table
                var table = document.getElementById("ResultTable");
                for (var i = 0; i < table.rows.length; i++) {
                    if(table.rows[i].cells[0].childElementCount>0){
                        var element = table.rows[i].cells[0].children[0].children[0];
                        if (element.id == id){
                            table.rows[i].remove();
                        break;
                        }
                    }
                }
                //Submit the changes
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("error").innerHTML = this.responseText;
                        location.reload(true);
                    }
                };
                xmlhttp.open("GET", "php/updateShopping.php?id=" + id + "&val=" + val, true);
                xmlhttp.send();
            } 
            //Accidental change reset slider
            else {
                document.getElementById(id).checked = true;
            }
        }else{
            //Submit the changes
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("error").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "php/updateShopping.php?id=" + id + "&val=" + val, true);
            xmlhttp.send();
        }
    }
    function SaveNotes(){
        var Notes = document.getElementById("NotesBox").value; 

        //Run the request to get Name2
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("error").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "php/saveNotes.php?notes=" + Notes, true);
        xmlhttp.send();
    }
    function SubmitPurchase(){
        var Purchase = confirm("Would you like to confirm purchase?");
        if (Purchase == true) {
            //Empty notes
            var NewNotes = confirm("Would you like to reset notes?");
            if (NewNotes == true) {
                document.getElementById("NotesBox").value = ":3";
                SaveNotes();
            }

            //Submit purchase
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("error").innerHTML = this.responseText;
                    //Refresh page
                    location.reload(true);
                }
            };
            xmlhttp.open("GET", "php/purchase.php", true);
            xmlhttp.send();
        } 
    }
</script>


