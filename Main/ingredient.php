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

    //Get the ingredient information
    $Query1 = "SELECT * FROM pantry WHERE item_id=?";
    $stmt = $connection->prepare($Query1);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $ResultSet1 = $stmt->get_result();
    $Ingredient = $ResultSet1->fetch_assoc();
    //echo var_dump($Ingredient);

    //Get all the categories
    $Query1 = "SELECT DISTINCT category FROM pantry";
    $ResultSet2 = $connection->query($Query1);    

    //Get all the recipes
    $Query1 = "SELECT recipe_id, name FROM recipe";
    $ResultSet3 = $connection->query($Query1);    

    //Create the group tables
    $hello = CreateGroupTables($connection,$id);

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
  <div id="Lists">
    <!-- All the categories -->
    <datalist id="CategoryAll">
    <?php
      while ($row = $ResultSet2->fetch_assoc()) {
        echo '<option value="'.$row["category"].'">'.$row["category"].'</option>';
      }
    ?>
    </datalist>

    <!-- All the recipes -->
    <datalist id="RecipeAll">
    <?php
      while ($row = $ResultSet2->fetch_assoc()) {
        echo '<option value="'.$row["category"].'">'.$row["category"].'</option>';
      }
    ?>
    </datalist>
  </div>

  <div class="center">
    <p style="text-align: center; font-size: 5vw; color: #81B29A; font-weight: bold;">Edit Ingredient</p>
    <table style="width: 100%; border-collapse:collapse; border: spacing 1px;">
    <tr>
      <td>Id:</td>
      <td colspan="3"><?php echo $Ingredient["item_id"];?></td>
    </tr>
    <tr>
      <td>Status:</td>
      <td colspan="3">
        <label class="switch">
          <?php
            if($Ingredient["status"]=='1'){
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
      <td><input onchange="GetName2('Main1')" type="text" value="<?php echo $Ingredient["name1"];?>" id="Main1Name1" list="Name1All"></td>
      <td><input onchange="GetName3('Main1')" type="text" value="<?php echo $Ingredient["name2"];?>" id="Main1Name2" list="Name2Main1"></td>
      <td><input type="text" value="<?php echo $Ingredient["name3"];?>" id="Main1Name3" list="Name3Main1"></td>
    </tr>
    <tr>
      <td>Category:</td>
      <td colspan="3"><input type="text" value="<?php echo $Ingredient["category"];?>" id="Category" list="CategoryAll"></td>
    </tr>
    <tr>
      <td>Purchase:</td>
      <td colspan="3"><input type="text" value="<?php echo $Ingredient["purchase"];?>" id="Purchase"></td>
    </tr>
    <tr>
      <td>Recipe:</td>
      <td colspan="3" style="padding-right: 0;">
        <select style="width:100%" id="Recipe" onchange="UpdateSelectOption('Recipe')">
          <option value="0"></option>
            <?php
              while ($row = $ResultSet3->fetch_assoc()) {
                echo '<option value="'.$row["recipe_id"].'">'.$row["name"].'</option>';
              }
            ?>
        </select>
      </td>
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
  var ChangeList=[];
  function ChangeGroup(id,item){
    var Bttn = document.getElementById("BttnGroup"+id);
    var List = document.getElementById("Group"+id);
    //Add to group
    if (Bttn.className == "far fa-plus-square"){
      //Change icon
      Bttn.className = "far fa-minus-square";
      //Record changes
      var temp = [id,item];
      ChangeList.push(temp);
      //Add to select options
      var option = document.createElement("option");
      option.text = "This Ingredient";
      List.add(option);
    }
    //Remove from group
    else{
      //Change icon
      Bttn.className = "far fa-plus-square";
      //Record changes
      var temp = [id,false];
      ChangeList.push(temp);
      //Add to select options
      var option = document.createElement("option");
      option.text = "This Ingredient";
      List.remove(List.selectedIndex);
    }
  }
  function AddGroup(){
    var table = document.getElementById("GroupTable");
  }

</script>
<?php
  function CreateGroupTables($connection,$id){
    $left = true;
    $inGroup = false;
    $GroupTable = '<table id="GroupTable" style="width: 100%; border-collapse:collapse; border: spacing 1px;">';
    $GroupTable .= '<tr><th colspan="4">Groups <i onclick="AddGroup()" class="far fa-plus-square"></i></td></tr>';
    
    //Get the different group ids
    $Query1 = "SELECT DISTINCT group_id FROM fridgemate_db.group";
    $ResultSet4 = $connection->query($Query1);  
    echo var_dump($ResultSet4);
    while ($group = $ResultSet4->fetch_assoc()) {
      //Get all the ingredients in that group
      $Query1 = "SELECT item_id FROM fridgemate_db.group WHERE group_id=".$group["group_id"];
      $Query2 = "SELECT item_id, name1, name2, name3 FROM pantry WHERE item_id IN (".$Query1.")";
      $ResultSet5 = $connection->query($Query2); 

      //Print the left column
      if($left){
        $GroupTable .= '<tr><td><select id="Group'.$group["group_id"].'">';
        $GroupTable .= '<option selected="selected">Group '.$group["group_id"].'</option>';
        while ($row = $ResultSet5->fetch_assoc()) {
          $GroupTable .= '<option>'.$row["name1"].' '.$row["name2"].' '.$row["name3"].'</option>';
          if($row["item_id"] == $id){
            $inGroup = true;
          }
        } 
        $GroupTable .= '</select></td>';
        //Ingredient is in group use minus button
        if($inGroup){
          $GroupTable .= '<td><i id="BttnGroup'.$group["group_id"].'" onclick="ChangeGroup('.$group["group_id"].','.$id.')" class="far fa-minus-square"></i></td>';
          $inGroup = false;
        } 
        //Ingredient is not in group use plus
        else{
          $GroupTable .= '<td><i id="BttnGroup'.$group["group_id"].'" onclick="ChangeGroup('.$group["group_id"].','.$id.')" class="far fa-plus-square"></i></td>';
        }
        
        $left = false;
        
      //Print the right column
      }else{
        $GroupTable .= '<td><select id="Group'.$group["group_id"].'">';
        $GroupTable .= '<option selected="selected" value="1">Group '.$group["group_id"].'</option>';
        while ($row = $ResultSet5->fetch_assoc()) {
          $GroupTable .= '<option>'.$row["name1"].' '.$row["name2"].' '.$row["name3"].'</option>';
          if($row["item_id"] == $id){
            $inGroup = true;
          }
        } 
        $GroupTable .= '</select></td>';
        //Ingredient is in group use minus button
        if($inGroup){
          $GroupTable .= '<td><i id="BttnGroup'.$group["group_id"].'" onclick="ChangeGroup('.$group["group_id"].','.$id.')" class="far fa-minus-square"></i></td>';
          $inGroup = false;
        }
        //Ingredient is not in group use plus
        else{
          $GroupTable .= '<td><i id="BttnGroup'.$group["group_id"].'" onclick="ChangeGroup('.$group["group_id"].','.$id.')" class="far fa-plus-square"></i></td>';
        }
        $GroupTable .= '</tr>';
        $left = true;
      }
    } 
    //create last right column if needed 
    if(!$left){
      $GroupTable .= '<td><select id="Group0">';
      $GroupTable .= '<option selected="selected" value="1">Group0</option>';
      $GroupTable .= '</select></td>';
      $GroupTable .= '<td><i id="BttnGroup0" onclick="ChangeGroup(0,'.$id.')" class="far fa-plus-square"></i></td>';
      $GroupTable .= '</tr>';
    }

    $GroupTable .= '</table>';
    echo $GroupTable;
  }
  
?>



