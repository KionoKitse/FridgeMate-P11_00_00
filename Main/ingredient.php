<!DOCTYPE html>
<html>
<head>
  <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
  <script src="js/mainstyle.js"></script>
</head>

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

    echo var_dump($ResultSet1);

    //exit
    $stmt->close();
    db_disconnect($connection);
?>

<body>
<span id="error"></span>
<div class="center">
  
</div>
</body>
</html>
<script>
</script>