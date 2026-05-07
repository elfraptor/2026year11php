
<?php 
include_once "indexHeader.php";
?>
<h1>Crud Update With Modal</h1>
<form action="crudUpdateWithModalAction.php" method="post"> <!-- calls crudUpdateWithModalAction.php when the form is submitted -->
    <label for="id">ID:</label>
  <input type="number" id="id" name="id"><br><br>
  <input type="submit" value="Submit">
</form>
<?php 
include_once "indexFooter.php";
?>
