<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

if (empty($_SESSION['logged_in'])) {
	header('Location: indexLogin.php');
	exit;
}

include_once "indexHeader.php";
?>
<html>
<h1>Title</h1>
</html>
<?php
$user_code= trim($_POST['user_code']);
  $date = trim($_POST['date']);
  $start_time = trim($_POST['start_time']);
  $end_time = trim($_POST['end_time']);
  $breaks = floatval(trim($_POST['breaks']));
  $rate = floatval(trim($_POST['rate']));
  $laundry = floatval(trim($_POST['laundry']));
  $uniform = floatval(trim($_POST['uniform']));
  $start_day_holi = isset($_POST['start_day_holi']) ? 1 : 0;
  $end_day_holi   = isset($_POST['end_day_holi']) ? 1 : 0;

  // connect to the database
  $conn = mysqli_connect("localhost", "root", "", "yr11_test_db");

  // check connection
  if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
  }

  // insert data into the shifts table
  $sql = "UPDATE shift_records SET user_code=?, date=?, s_time=?, e_time=?, break=?, rate=?, l_allowance=?, u_allowance=?, start_day_holi=?, end_day_holi=? WHERE user_code=? AND date=?";
  $stmt = mysqli_prepare($conn, $sql);

  if($stmt){
    // types: user_code (s), date (s), start_time (s), end_time (s), then five floats (d)
    mysqli_stmt_bind_param($stmt, "ssssddddddss", $user_code, $date, $start_time, $end_time, $breaks, $rate, $laundry, $uniform, $start_day_holi, $end_day_holi, $user_code, $date);

    if(mysqli_stmt_execute($stmt)){
      echo "Record updated successfully";
    }else{
      echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
  } else {
    echo "Error preparing statement: " . mysqli_error($conn);
  }
  ?>
  <br>
  <a href="payslipShifts.php" class="btn btn-secondary">Back to shifts</a>
  <?php
include_once "indexFooter.php";
?>