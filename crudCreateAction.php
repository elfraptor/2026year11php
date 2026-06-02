<?php
include_once "indexHeader.php";
?>
<html>
<h1>CRUD | Create</h1>
</html>
<?php
  $first_name = trim($_POST['first_name']);
  $last_name = trim($_POST['last_name']);
  $email = trim($_POST['email']);
  $status = 1;
  $baseCode  = strtoupper(substr($last_name, 0, 4));
  $code = $baseCode . str_pad(1, 4, "0", STR_PAD_LEFT);

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
?>

<button onclick="window.location.href='index.php'">Back to Home</button>
<?php
include_once "indexFooter.php";
?>
<?php
include_once "indexFooter.php";
?>