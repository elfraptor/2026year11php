<?php
include_once "indexHeader.php";
?>
<html>
<h1>CRUD|Create</h1>
<form method="post" action="">
    <label for="code">Code:</label><br>
    <input type="text" id="code" name="code"><br>
    <label for="first_name">First name:</label><br>
    <input type="text" id="first_name" name="first_name"><br>
    <label for="last_name">Last name:</label><br>
    <input type="text" id="last_name" name="last_name"><br>
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email"><br>
    <input type="submit" name="submit" value="Submit">
</form>
</html>
<!-- create a new record and insert in the users table in the datable yr_test_php-->
 <!-- You will need to create a new page where you have input boxes -->
  <?php
  if(isset($_POST['submit'])){
    $code = trim($_POST['code']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $status = 1;

    // connect to the database
    $conn = mysqli_connect("localhost", "root", "", "yr11_test_db");

    // check connection
    if(!$conn){
      die("Connection failed: " . mysqli_connect_error());
    }

    // insert data into the users table
    $sql = "INSERT INTO users (code, first_name, last_name, email, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if($stmt){
      mysqli_stmt_bind_param($stmt, "ssssi", $code, $first_name, $last_name, $email, $status);

      if(mysqli_stmt_execute($stmt)){
        echo "New record created successfully";
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