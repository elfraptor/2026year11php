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
    $name = trim($_POST['shift_name']);
    $rate = floatval(trim($_POST['rate']));
    $deductable = floatval(trim($_POST['deductable']));
    $l_allow = floatval(trim($_POST['l_allow']));
    $u_allow = floatval(trim($_POST['u_allow']));
    $pm_allow = floatval(trim($_POST['pm_allow'] ?? 0));
    $sat_loading = floatval(trim($_POST['sat_loading'] ?? 0));
    $sun_loading = floatval(trim($_POST['sun_loading'] ?? 0));
    $holi_loading = floatval(trim($_POST['holi_loading'] ?? 0));
    $fringe = floatval(trim($_POST['fringe']));
    $tax = floatval(trim($_POST['tax']));

    // connect to the database
    $conn = mysqli_connect("localhost", "root", "", "yr11_test_db");

    // check connection
    if(!$conn){
      die("Connection failed: " . mysqli_connect_error());
    }

    // insert data into the shifts table
    $sql = "INSERT INTO shift_types (user_code, name, rate, deductable, l_allow, u_allow, pm_allow, sat_loading, sun_loading, holi_loading, fringe, tax) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if($stmt){
      // types: user_code (s), shift_name (s), then ten numeric values (d)
      mysqli_stmt_bind_param($stmt, "ssdddddddddd", $user_code, $name, $rate, $deductable, $l_allow, $u_allow, $pm_allow, $sat_loading, $sun_loading, $holi_loading, $fringe, $tax);

      if(mysqli_stmt_execute($stmt)){
        echo "New record created successfully";
      }else{
        echo "Error: " . mysqli_stmt_error($stmt);
      }

      mysqli_stmt_close($stmt);
    } else {
      echo "Error preparing statement: " . mysqli_error($conn);
    }
    ?>
    <br>
    <br>
  <a href="payslipShifts.php" class="btn btn-secondary">Back to shifts</a>
    <?php
    include_once "indexFooter.php";
    ?>