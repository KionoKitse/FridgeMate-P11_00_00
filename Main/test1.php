<!DOCTYPE html>
<html>
<body>

<h1>test1</h1>


<?php
include './includes/ChromePhp.php';

require_once('./includes/dbconnect.php');

$sql = "SELECT * FROM fridgemate_db.recipe;";
$result = $connection->query($sql);
ChromePhp::log($result);

if ($result) {
  while ($row = $result -> fetch_row()) {
    printf ("%s %s <br>", $row[0], $row[1]);
  }
  $result -> free_result();
}

//echo $result;
echo "hello";
db_disconnect($connection);

?>

</body>
</html>

