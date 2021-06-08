<!DOCTYPE html>
<html>
<head>
  <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
  <script src="js/mainstyle.js"></script>
</head>
<body>
<style>
  .bttnWide {
    background-color: #81B29A;
    width:100%;
    border-radius: 5px; 
    border: 2px solid #9BCCB4; 
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

    //Get all ingredients sorted by alphabetically
    $Query1 = "SELECT * FROM pantry ORDER BY name1, name2, name3";
    $ResultSet1 = $connection->query($Query1);
    //Get all ingredients sorted by category
    $Query1 = "SELECT * FROM pantry ORDER BY category,status DESC,name1,name2,name3";
    $ResultSet2 = $connection->query($Query1);
    //Get all ingredients sorted by status
    $Query1 = "SELECT * FROM pantry ORDER BY status DESC,name1,name2,name3";
    $ResultSet3 = $connection->query($Query1);
    //Get all ingredients sorted by age
    $Query1 = "SELECT * FROM pantry order by status DESC, DATEDIFF(CURDATE(), purchase) DESC"; 
    $ResultSet4 = $connection->query($Query1);

    db_disconnect($connection);
?>

<div class="center">
  <p style="text-align: center; font-size: 5vw; color: #81B29A; font-weight: bold;">Ingredients</p>
  <table style="width: 100%; border-collapse:collapse; border-spacing:0;">
    <tr>
      <td style="width: 25%;"><button class="bttnWide" onclick="TryThis()">A-Z</button></td>
      <td style="width: 25%;"><button class="bttnWide" onclick="TryThis()">Category</button></td>
      <td style="width: 25%;"><button class="bttnWide" onclick="TryThis()">Status</button></td>
      <td style="width: 25%;"><button class="bttnWide" onclick="TryThis()">Age</button></td>
    </tr>
  </table>
  <table style="width: 100%; border-collapse:collapse; border-spacing:0;">
    <tr>
      <td style="width: 80%;">Tomatoes Canned Diced</td>
      <td style="width: 20%;">
        <label class="switch">
          <input type="checkbox" checked>
          <span class="slider round"></span>
        </label>
      </td>
    </tr>
    <tr>
      <td style="width: 80%;">Tomatoes Canned Diced</td>
      <td style="width: 20%;">
        <label class="switch">
          <input type="checkbox" checked>
          <span class="slider round"></span>
        </label>
      </td>
    </tr>
    <tr>
      <td style="width: 80%;">Tomatoes Canned Diced</td>
      <td style="width: 20%;">
        <label class="switch">
          <input type="checkbox" checked>
          <span class="slider round"></span>
        </label>
      </td>
    </tr>

  </table>
  
</div>
<p>
  > Display all ingredients
  >> Name1,Name2,Name3
  >> Status
  ->> Buildable
  >> Expires
  ->> Date
  ->> Category
  ->> Group

  > Sort
  >> Alphabetically: SELECT * FROM fridgemate_db.pantry ORDER BY name1, name2, name3;
  >> Category: SELECT * FROM fridgemate_db.pantry ORDER BY category,status DESC,name1,name2,name3;
  >> Status: SELECT * FROM fridgemate_db.pantry ORDER BY status DESC,name1,name2,name3;
  >> Age -> status: SELECT * FROM fridgemate_db.pantry order by status DESC, DATEDIFF(CURDATE(), purchase) DESC;

  > Edit ingredients 
  >> Edit names (advanced)
  >> status: set status -> sets date to current automatically
  >> Buildable (advanced)
  >> expires: set expires (advanced)
  >> Category: Change category (advanced)
  >> Group: Add to group or create new (advanced)
  >> remove: will need to check if a recipe uses first (advanced)
</p>


</body>
</html>
