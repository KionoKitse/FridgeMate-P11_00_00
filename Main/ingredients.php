<!DOCTYPE html>
<html>
<head>
  <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
  <script src="js/mainstyle.js"></script>
</head>

<!-- Style for this page -->
<style>
  .bttnGreen {
    background-color: #81B29A;
    width:100%;
    border-radius: 5px; 
    border: 2px solid #9BCCB4; 
  }
  .bttnYellow {
    background-color: #F2CC8F;
    width:100%;
    border-radius: 5px; 
    border: 2px solid #FFE6A9; 
  }
  .ingLink{
    color: #3D405B; 
    text-decoration: none;
    font-size: 4vw;
  }
  .catHeader{
    text-align: left;
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
  <p style="text-align: center; font-size: 5vw; color: #81B29A; font-weight: bold;">Ingredients</p>
  <table style="width: 100%; border-collapse:collapse; border-spacing:0;">
    <tr>
      <td style="width: 25%;"><button class="bttnGreen" onclick="SortType('A-Z')">A-Z</button></td>
      <td style="width: 25%;"><button class="bttnGreen" onclick="SortType('Cat')">Category</button></td>
      <td style="width: 25%;"><button class="bttnGreen" onclick="SortType('Stat')">Status</button></td>
      <td style="width: 25%;"><button class="bttnGreen" onclick="SortType('Age')">Age</button></td>
    </tr>
  </table>

  <table id="ResultTable" style="width: 100%; border-collapse:collapse; border-spacing:0;">
  </table>
  
</div>
</body>
</html>

<script>
  //First run display sort A-Z
  SortType('A-Z');

  //Function update ingredient table
  function SortType(type){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //Add data for display
            document.getElementById("ResultTable").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET","php/ingSortType.php?type="+type,true);
    xmlhttp.send();
  }

  //Function to change status
  function changeStatus(id){
    //Get the new value
    var val = document.getElementById(id).checked ? 1 : 0;
    
    //Submit the changes
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("error").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET","php/changeStatus.php?id="+id+"&val="+val,true);
    xmlhttp.send();
  }
  //Function to change cart
  function changeCart(id){
    //Get the new value
    var val = document.getElementById(id).checked ? 1 : 0;
    
    //Submit the changes
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("error").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET","php/changeCart.php?id="+id+"&val="+val,true);
    xmlhttp.send();
  }
</script>

