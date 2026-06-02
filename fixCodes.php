<?php
include_once "indexHeader.php";
?>
<html>
<h1>Hopefully this fixes stuff</h1>
</html>
<?php
$counter=[];
$query="SELECT * FROM `users` WHERE `status`='1'";
$conn=new mysqli($host, $user, $pass, $db);
$result = mysqli_query($conn, $query);
if($result){ // if query runs
if(mysqli_num_rows($result) > 0){ //checks if there are any records
while($row = mysqli_fetch_array($result)){
  $lastname=$row['last_name'];
  $baseCode  = strtoupper(substr($lastname, 0, 3));
  $countStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE code LIKE ?");
  $likeParam = $baseCode . "%";
  $countStmt->bind_param("s", $likeParam);
  $countStmt->execute();
  $countStmt->bind_result($existingCount);
  $countStmt->fetch();
  $countStmt->close();
  $code = $baseCode . str_pad($existingCount + 1, 3, "0", STR_PAD_LEFT);
}
}
}
echo ('<h3>Codes have been fixed!</h3>');
?>

<?php
include_once "indexFooter.php";
?>