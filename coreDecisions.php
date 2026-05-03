<?php
include_once "indexHeader.php";
?>
<html>
<h1>Decisions</h1>
</html>
<!-- If Else-If Elif -->
<?php
$age=18;
if($age>=18){
  echo "You are an adult";
}
elseif($age>=13){
  echo "You are a teenager";
}
else{
  echo "You are a child";
}
echo "<br>";
?>
<!-- Switch Case -->
<?php
$number=2;
switch($number){
  case 1:
    echo "One";
    break;
  case 2:
    echo "Two";
    break;
  case 3:
    echo "Three";
    break;
  default:
    echo "Number not between 1 and 3";
}
?>
<?php
include_once "indexFooter.php";
?>