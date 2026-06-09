<?php
include_once "indexHeader.php";
?>
<html>
<?php
$code = trim($_POST['user_code'] ?? '');
$enteredPassword = $_POST['user_pass'] ?? '';

if ($code === '' || $enteredPassword === '') {
    echo "<h3>Please enter both a user code and password.</h3>";
} else {
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $stmt = $conn->prepare('SELECT id, code, f_name, l_name, pass FROM users WHERE code = ? LIMIT 1');

    if ($stmt) {
        $stmt->bind_param('s', $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $userRow = $result ? $result->fetch_assoc() : null;

        if ($userRow && $userRow['pass'] === $enteredPassword) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $userRow['id'];
            $_SESSION['user_code'] = $userRow['code'];
            $_SESSION['f_name'] = $userRow['f_name'];
            $_SESSION['l_name'] = $userRow['l_name'];

            $stmt->close();
            $conn->close();

            header('Location: index.php');
            exit;
        }

        $stmt->close();
    }

    $conn->close();

    echo "<h3>Incorrect user code or password.</h3>";
}
?>
<?php
include_once "indexFooter.php";
?>