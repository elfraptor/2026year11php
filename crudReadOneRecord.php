
<?php 
include_once "indexHeader.php";
?>
<h1>Template</h1>     
<form action="crudReadOneRecordAction.php" method="post">
  <label for="id">ID:</label>
  <input type="number" id="id" name="id"><br><br>
  <input type="submit" value="Submit">
</form>
<?php 
include_once "indexFooter.php";
?>
