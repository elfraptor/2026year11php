<?php
include_once "indexHeader.php";
?>
<h1>CRUD Updater</h1>
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