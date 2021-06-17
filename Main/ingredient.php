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
    width:97.5%;
    text-align: center; 
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
  .bttnYellow {
    background-color: #F2CC8F;
    width:100%;
    border-radius: 5px; 
    border: 2px solid #FFE6A9; 
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
    $GroupTable = CreateGroupTables($connection,$id);

    //exit
    $stmt->close();
    db_disconnect($connection);
?>

<!-- Get content to render this page -->
<script>
  document.addEventListener("DOMContentLoaded", GetName1);
</script>

<body>

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
    <table id="ingredient" style="width: 100%; border-collapse:collapse; border: spacing 1px;">
      <tr>
        <td>Id:</td>
        <td colspan="3"><?php echo $Ingredient["item_id"];?></td>
      </tr>
      <tr>
        <td>Status:</td>
        <td>
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
        <td>Shopping List:</td>
        <td>
          <label class="switch">
            <?php
              if($Ingredient["cart"]=='1'){
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
        <td style="padding-right: 2vw;"><input onchange="GetName2('Main1')" type="text" value="<?php echo $Ingredient["name1"];?>" id="Main1Name1" list="Name1All"></td>
        <td style="padding-right: 2vw;"><input onchange="GetName3('Main1')" type="text" value="<?php echo $Ingredient["name2"];?>" id="Main1Name2" list="Name2Main1"></td>
        <td style="padding-right: 2vw;"><input type="text" value="<?php echo $Ingredient["name3"];?>" id="Main1Name3" list="Name3Main1"></td>
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
        <td>Expires:</td>
        <td colspan="3"><input type="text" value="<?php echo $Ingredient["expires"];?>" id="Expires"></td>
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
    <?php echo $GroupTable; ?>    
    <button class="bttnYellow" onclick="Submit()">Submit Changes</button>
  </div>
</body>
</html>

<script>
  var ChangeList=[];
  function ChangeGroup(id,item){
    var Bttn = document.getElementById("BttnGroup"+id);ingredient
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
  function Submit(){
    //Get the tables
    var dataTable = document.getElementById("ingredient");
    
    //Get updated pantry values
    var Item_id = dataTable.rows[0].cells[1].innerHTML;
    var Status = dataTable.rows[1].cells[1].children[0].children[0].checked ? 1: 0;
    var Cart = dataTable.rows[1].cells[3].children[0].children[0].checked ? 1: 0;
    var Name1 = dataTable.rows[2].cells[1].children[0].value;
    var Name2 = dataTable.rows[2].cells[2].children[0].value;
    var Name3 = dataTable.rows[2].cells[3].children[0].value;
    var Category = dataTable.rows[3].cells[1].children[0].value;
    var Purchase = dataTable.rows[4].cells[1].children[0].value;
    var Expires = dataTable.rows[5].cells[1].children[0].value;
    var Recipe_id = dataTable.rows[6].cells[1].children[0].value;
    var GroupCt = document.getElementById("GroupCt").value;


    //Parse the data into a JSON so it may be passed to PHP
    var Results = new Object();
    Results.Item_id = Item_id;
    Results.Status = Status;
    Results.Cart = Cart;
    Results.Name1 = Name1;
    Results.Name2 = Name2;
    Results.Name3 = Name3;
    Results.Category = Category;
    Results.Purchase = Purchase;
    Results.Expires = Expires;
    Results.Recipe_id = Recipe_id;
    Results.ChangeList = ChangeList;
    Results.GroupCt = GroupCt;
    var jsonResults= JSON.stringify(Results);

    //Submit the data to server for processing
    var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          alert(this.responseText);
        }
      };
    xmlhttp.open("POST","php/direct/submitIngredient.php",true);
    xmlhttp.send(jsonResults);
  }
</script>

<?php
  function CreateGroupTables($connection,$id){
    $left = true;
    $inGroup = false;
    $GroupCt = 0;
    $GroupTable = '<table id="GroupTable" style="width: 100%; border-collapse:collapse; border: spacing 1px;">';
    $GroupTable .= '<tr><th colspan="4">Groups</td></tr>';
    
    //Get the different group ids
    $Query1 = "SELECT DISTINCT group_id FROM fridgemate_db.group";
    $ResultSet4 = $connection->query($Query1);  

    while ($group = $ResultSet4->fetch_assoc()) {
      //Get all the ingredients in that group
      $Query1 = "SELECT item_id FROM fridgemate_db.group WHERE group_id=".$group["group_id"];
      $Query2 = "SELECT item_id, name1, name2, name3 FROM pantry WHERE item_id IN (".$Query1.")";
      $ResultSet5 = $connection->query($Query2); 

      //Update GroupCt
      $GroupCt = $group["group_id"];

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
      $GroupTable .= '<option selected="selected" value="1">Group 0</option>';
      $GroupTable .= '</select></td>';
      $GroupTable .= '<td><i id="BttnGroup0" onclick="ChangeGroup(0,'.$id.')" class="far fa-plus-square"></i></td>';
      $GroupTable .= '</tr>';
    }
    else{
      //Create left as group 0
      $GroupTable .= '<tr><td><select id="Group0">';
      $GroupTable .= '<option selected="selected" value="1">Group 0</option>';
      $GroupTable .= '</select></td>';
      $GroupTable .= '<td><i id="BttnGroup0" onclick="ChangeGroup(0,'.$id.')" class="far fa-plus-square"></i></td>';
      //Create right as empty
      $GroupTable .= '<td></td>';
      $GroupTable .= '<td></td>';
      $GroupTable .= '</tr>';
    }

    $GroupTable .= '</table>';
    $GroupTable .= '<input type="hidden" id="GroupCt" value="'.$GroupCt.'">';
    return $GroupTable;
  }
  
?>



