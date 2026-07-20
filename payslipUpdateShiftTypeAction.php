<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['logged_in'])) {
    header('Location: indexLogin.php');
    exit;
}

include_once "indexHeader.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: payslipShifts.php');
    exit;
}

$id = trim($_POST['id'] ?? '');
$user_code = trim($_POST['user_code'] ?? '');
$name = trim($_POST['name'] ?? '');
$rate = floatval(trim($_POST['rate'] ?? 0));
$deductable = floatval(trim($_POST['deductable'] ?? 0));
$l_allow = floatval(trim($_POST['l_allow'] ?? 0));
$u_allow = floatval(trim($_POST['u_allow'] ?? 0));
$fringe = floatval(trim($_POST['fringe'] ?? 0));
$tax = floatval(trim($_POST['tax'] ?? 0));

$conn = mysqli_connect("localhost", "root", "", "yr11_test_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "UPDATE shift_types SET user_code=?, name=?, rate=?, deductable=?, l_allow=?, u_allow=?, fringe=?, tax=? WHERE id=? AND user_code=?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssddddddis", $user_code, $name, $rate, $deductable, $l_allow, $u_allow, $fringe, $tax, $id, $user_code);

    if (mysqli_stmt_execute($stmt)) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
<br>
<a href="payslipShifts.php" class="btn btn-secondary">Back to shifts</a>
<?php include_once "indexFooter.php"; ?>