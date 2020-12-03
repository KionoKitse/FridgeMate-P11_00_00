<!DOCTYPE html>
<html>
<body>

<h3>test2</h3>

<?php
//include for printing to console
//include 'ChromePhp.php';
//ChromePhp::log("Connected");
echo "hello";

//MySQL database connection
require_once('./includes/dbconnect.php');

$sql = "SELECT * FROM recipe;";
$result = $connection->query($sql);

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
