<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
  <script src="js/mainstyle.js"></script>
  <script src="js/scripts.js"></script>
</head>

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

<!-- Style specific to this page -->
<style>
  input{
    width:100%;
    text-align: center; 
  }
  td {
    padding-right: 2vw;
  }
  select{
    width: 35vw;
    height: 6vw;
    font-size: 3.2vw;
  }
  i{
    color: #F2CC8F; 
    font-size: 5.5vw;
  }
</style>

<!-- Get content to render this page -->
<?php
    //Includes
    require_once 'dbconnect.php';

    //Get the response data
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    //Update the parameters for an existing recipe
    $Query = "SELECT * FROM pantry WHERE item_id=?";
    $stmt = $connection->prepare($Query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $ResultSet1 = $stmt->get_result();
    $row = $ResultSet1->fetch_assoc();

    echo var_dump($row);

    //exit
    $stmt->close();
    db_disconnect($connection);
?>

<!-- SGet content to render this page -->
<script>
  document.addEventListener("DOMContentLoaded", GetName1);
</script>

<body>
  <span id="error"></span>

  <!-- Lists used for the datalists -->
  <div id="Lists"></div>

  <div class="center">
    <p style="text-align: center; font-size: 5vw; color: #81B29A; font-weight: bold;">Edit Ingredient</p>
    <table style="width: 100%; border-collapse:collapse; border: spacing 1px;">
    <tr>
      <td>Id:</td>
      <td colspan="3"><?php echo $row["item_id"];?></td>
    </tr>
    <tr>
      <td>Status:</td>
      <td colspan="3">
        <label class="switch">
          <?php
            if($row["status"]=='1'){
              echo '<input id="Status" type="checkbox" checked>';
            }
            else{
              echo '<input id="Status" type="checkbox">';
            }
          ?>
          <span class="slider round"></span>
        </label>
      </td>
    </tr>
    <tr>
      <td>Name:</td>
      <td><input onchange="GetName2('Main1')" type="text" value="<?php echo $row["name1"];?>" id="Main1Name1" list="Name1All"></td>
      <td><input onchange="GetName3('Main1')" type="text" value="<?php echo $row["name2"];?>" id="Main1Name2" list="Name2Main1"></td>
      <td><input type="text" value="<?php echo $row["name3"];?>" id="Main1Name3" list="Name3Main1"></td>
    </tr>
    <tr>
      <td>Category:</td>
      <td colspan="3"><input onchange="GetName2('Main1')" type="text" value="Bread" id="Main1Name1" list="Name1All"></td>
    </tr>
    <tr>
      <td>Purchase:</td>
      <td colspan="3"><input onchange="GetName2('Main1')" type="text" value="Bread" id="Main1Name1" list="Name1All"></td>
    </tr>
    <tr>
      <td>Recipe:</td>
      <td colspan="3"><input onchange="GetName2('Main1')" type="text" value="Bread" id="Main1Name1" list="Name1All"></td>
    </tr>
  </table>
  
  <table style="width: 100%; border-collapse:collapse; border: spacing 1px;">
    <tr>
      <th colspan="4">Groups <i onclick="AddIngredient('Main')" class="far fa-plus-square"></i></td>
    </tr>
    <tr>
      <td style="vertical-align: bottom" >
        <select id="SelectStep0Item1" onchange="UpdateSelectOption('SelectStep0Item1')">
          <option value="1">Group 1</option>
        </select>
      </td>
      <td><i onclick="AddIngredient('Main')" class="far fa-plus-square"></i></td>
      <td>
        <select id="SelectStep0Item1" onchange="UpdateSelectOption('SelectStep0Item1')">
          <option value="2">Group 2</option>
      </td>
      <td><i onclick="AddIngredient('Main')" class="far fa-minus-square"></i></td>
    </tr>

    
  </table>
    
  </div>
</body>
</html>
<script>
</script>