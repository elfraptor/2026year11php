<?php
include_once "indexHeader.php";
?>
<html>
<h1>Arrays</h1>
</html>
<!-- 1 Dimensional Array -->
<?php
$numbers=[1,2,3,4,5];
echo $numbers[0];
echo "<br>";
?>
<!-- 2 Dimensional Array (Matrices) -->
<?php
$matrix=[
  [1,2,3],
  [4,5,6],
  [7,8,9]
];
foreach($matrix as $row){
  foreach($row as $element){
    echo $element." ";

  }
  echo "<br>";
}
?>
<!-- Foreach loop (made for array specifically) -->
<?php
$fruits=["Apple","Banana","Cherry"];
foreach($fruits as $fruit){
  echo $fruit."<br>";
}
?>
<!-- For loop with array-->
<?php
$colors=["Red","Green","Blue"];
for($i=0;$i<count($colors);$i++){
  echo $colors[$i]."<br>";
}
?>
<!-- While loop with array -->
<?php
$animals=["Dog","Cat","Rabbit"];
$i=0;
while($i<count($animals)){
  echo $animals[$i]."<br>";
  $i++;
}
?>
<?php
include_once "indexFooter.php";
?>