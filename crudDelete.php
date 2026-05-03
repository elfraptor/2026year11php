<?php
include_once "indexHeader.php";

?>
<h1>CRUD|Delete</h1>

  <form method="post" action="">
  <label for="id">ID:</label><br>
    <input type="integer" id="id" name="id"><br><br>
    <input type="submit" value="Delete">
  </form>
  <!-- delete a record from the users table in the datable yr_test_php-->
  <!-- You will need to create a new page where you have an input box -->
  <?php
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = trim($_POST['id']);

    // connect to the database
    $conn = mysqli_connect("localhost", "root", "", "yr11_test_db");

    // check connection
    if(!$conn){
      die("Connection failed: " . mysqli_connect_error());
    }

    // delete data from the users table
    $sql = "UPDATE users SET status = 0 WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if($stmt){
      mysqli_stmt_bind_param($stmt, "i", $id);

      if(mysqli_stmt_execute($stmt)){
        echo "Record deleted successfully";
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