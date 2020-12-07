<!DOCTYPE html>
<html>
<head>

</head>
<body>

<?php
$q = intval($_GET['q']);

$con = mysqli_connect('localhost','DevOtter','Topspeed5','fridgemate_db');
if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"ajax_demo");
$sql="SELECT * FROM recipe WHERE RECIPE_ID = '".$q."'";
$result = mysqli_query($con,$sql);

echo "<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
<th>Age</th>
<th>Hometown</th>
<th>Job</th>
</tr>";
while($row = mysqli_fetch_array($result)) {
  echo "<tr>";
  echo "<td>" . $row['RECIPE_ID'] . "</td>";
  echo "<td>" . $row['NAME'] . "</td>";
  echo "<td>" . $row['PEOPLE'] . "</td>";
  echo "<td>" . $row['RATING'] . "</td>";
  echo "<td>" . $row['PERCENT'] . "</td>";
  echo "</tr>";
}
echo "</table>";
mysqli_close($con);
?>
</body>
</html>