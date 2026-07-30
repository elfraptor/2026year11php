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
$id = intval($_POST['id'] ?? 0);
$shift_type = intval($_POST['shift_type'] ?? 0);
  $date = trim($_POST['date']);
  $start_time = trim($_POST['start_time']);
  $end_time = trim($_POST['end_time']);
  $breaks = floatval(trim($_POST['breaks']));
    $rate_input = trim($_POST['rate']);
    $laundry_input = trim($_POST['laundry']);
    $uniform_input = trim($_POST['uniform']);
  $shift_allow = floatval(trim($_POST['shift_allow'] ?? 0));
  $is_holi = isset($_POST['is_holi']) ? 1 : 0;

  // connect to the database
  $conn = mysqli_connect("localhost", "root", "", "yr11_test_db");

  // check connection
  if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
  }

  $shift_type_defaults = ['rate' => 0, 'l_allow' => 0, 'u_allow' => 0];
  if ($shift_type > 0) {
    $type_sql = "SELECT rate, l_allow, u_allow FROM shift_types WHERE id = ? AND user_code = ? AND status = '1' LIMIT 1";
    $type_stmt = mysqli_prepare($conn, $type_sql);

    if ($type_stmt) {
      mysqli_stmt_bind_param($type_stmt, "is", $shift_type, $user_code);
      mysqli_stmt_execute($type_stmt);
      mysqli_stmt_store_result($type_stmt);
      mysqli_stmt_bind_result($type_stmt, $type_rate, $type_l_allow, $type_u_allow);

      if (mysqli_stmt_fetch($type_stmt)) {
        $shift_type_defaults = [
          'rate' => $type_rate,
          'l_allow' => $type_l_allow,
          'u_allow' => $type_u_allow,
        ];
      }

      mysqli_stmt_close($type_stmt);
    }
  }

  $rate = $rate_input === '' ? floatval($shift_type_defaults['rate']) : floatval($rate_input);
  $laundry = $laundry_input === '' ? floatval($shift_type_defaults['l_allow']) : floatval($laundry_input);
  $uniform = $uniform_input === '' ? floatval($shift_type_defaults['u_allow']) : floatval($uniform_input);

  // insert data into the shifts table
  $sql = "UPDATE shift_records SET user_code=?, shift_type=?, date=?, s_time=?, e_time=?, break=?, rate=?, l_allowance=?, u_allowance=?, shift_allow=?, is_holi=? WHERE id=? AND user_code=?";
  $stmt = mysqli_prepare($conn, $sql);

  if($stmt){
    // types: user_code (s), shift_type (i), date (s), start_time (s), end_time (s), break (d), rate (d), laundry (d), uniform (d), shift_allow (d), is_holi (i), id (i), user_code (s)
    mysqli_stmt_bind_param($stmt, "sisssdddddiis", $user_code, $shift_type, $date, $start_time, $end_time, $breaks, $rate, $laundry, $uniform, $shift_allow, $is_holi, $id, $user_code);

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