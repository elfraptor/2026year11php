<?php
include_once "indexHeader.php";
include_once "indexMenubar.php";
?>
<html>
<h1>CRUD|Update</h1>
  <form method="post" action="">
  <label for="id">ID</label><br>
    <input type="text" id="id" name="id"><br><br>
  <label for="new_name">New First Name:</label><br>
    <input type="text" id="new_name" name="new_name"><br><br>
  <label for="last_name">Last Name:</label><br>
    <input type="text" id="last_name" name="last_name"><br><br>
  <label for="email">Email:</label><br>
    <input type="email" id="email" name="email"><br><br>
  <label for="code">Code:</label><br>
    <input type="text" id="code" name="code"><br><br>
    <input type="submit" value="Update">
  </form>
  <!-- update a record in the users table in the datable yr_test_php-->
  <!-- You will need to create a new page where you have an input box -->
  <?php
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = trim($_POST['id']);
    $new_name = trim($_POST['new_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $code = trim($_POST['code']);
    // connect to the database
    $conn = mysqli_connect("localhost", "root", "", "yr11_test_db");

    // check connection
    if(!$conn){
      die("Connection failed: " . mysqli_connect_error());
    }

    // update data in the users table using a proper prepared statement
    $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, code = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if($stmt){
      mysqli_stmt_bind_param($stmt, "ssssi", $new_name, $last_name, $email, $code, $id);

      if(mysqli_stmt_execute($stmt)){
        echo "Record updated successfully";
      }else{
        echo "Error: " . mysqli_stmt_error($stmt);
      }

      mysqli_stmt_close($stmt);
    } else {
      echo "Error preparing statement: " . mysqli_error($conn);
    }

    // close the connection
    mysqli_close($conn);
  }
  ?>
  <?php

  include_once "indexFooter.php";
  ?>