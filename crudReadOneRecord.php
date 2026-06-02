
<?php
include_once "indexHeader.php";
?>
<h1>Read One Record</h1>
  <form action="crudReadOneRecordAction.php" method="post"> <!-- calls crudReadOneRecordAction.php when the form is submitted -->
  <label for="id">Code:</label>
    <input type="text" id="id" name="id"><br><br>
    <input type="submit" value="Submit">
  </form>
  <?php
  include_once "indexFooter.php";
  ?>
