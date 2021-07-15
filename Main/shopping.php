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

<body>
<span id="error"></span>

<div class="center">
    <p style="text-align: center; font-size: 5vw; color: #81B29A; font-weight: bold;">Shopping</p>
    <table style="width: 100%; border-collapse:collapse; border-spacing:0;">
        <tr>
            <td style="width: 50%;"><button class="bttnGreen" onclick="DispShopping()">Shopping List</button></td>
            <td style="width: 50%;"><button class="bttnGreen" onclick="SortType('Cat')">Recommendations</button></td>
        </tr>
    </table>
    <table id="ResultTable" style="width: 100%; border-collapse:collapse; border-spacing:0;">
    </table>
</div>
</body>


<Script>
    
    DispShopping();
    function DispShopping(){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Add data for display
                document.getElementById("ResultTable").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","php/shopping.php",true);
        xmlhttp.send();
    }
    function DispRecomendation(){
    }
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


