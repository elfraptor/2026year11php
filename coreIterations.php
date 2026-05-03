<?php
include_once "indexHeader.php";
?>
<html>
<h1>Iterations</h1>
</html>
<!-- For loop -->
 <?php
  for($i=0;$i<5;$i++){
    echo $i."<br>";
  }
 ?>
 <!-- While-do Loop -->
  <?php
  $i=0;
  while($i<5){
    echo $i."<br>";
    $i++;
  }
  ?>
 <!-- Do-While loop -->
  <?php
  $i=0;
  do{
    echo $i."<br>";
    $i++;
  }while($i<5);
  ?>
<?php
include_once "indexFooter.php";
?>