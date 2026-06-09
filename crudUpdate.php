<?php
include_once "indexHeader.php";
include_once "indexMenubar.php";
?>
<html>
<h1>CRUD|Update</h1>
  <form method="post" action="crudUpdateAction.php">
  <label for="id">ID</label><br>
    <input type="text" id="id" name="id"><br><br>
  <label for="f_name">First Name:</label><br>
    <input type="text" id="f_name" name="f_name"><br><br>
  <label for="l_name">Last Name:</label><br>
    <input type="text" id="l_name" name="l_name"><br><br>
  <label for="email">Email:</label><br>
    <input type="email" id="email" name="email"><br><br>
  <label for="code">Code:</label><br>
    <input type="text" id="code" name="code"><br><br>
    <input type="submit" value="Update">
  </form>
  <!-- update a record in the users table in the datable yr_test_php-->
  <!-- You will need to create a new page where you have an input box -->
  <?php
  include_once "indexFooter.php";
  ?>