<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

if (empty($_SESSION['logged_in'])) {
	header('Location: indexLogin.php');
	exit;
}

include_once "indexHeader.php";
    $id = trim($_POST['id']);

    // connect to the database
    $conn = mysqli_connect("localhost", "root", "", "yr11_test_db");

    // check connection
    if(!$conn){
      die("Connection failed: " . mysqli_connect_error());
    }

    // delete data from the shift_records table
    $sql = "UPDATE shift_records SET status = 0 WHERE id = ?";
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
?>
  <br>
  <a href="payslipShifts.php" class="btn btn-secondary">Back to shifts</a>
<?php
include_once "indexFooter.php";
?>