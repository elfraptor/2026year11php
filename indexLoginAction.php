<?php
include_once "indexHeader.php";
?>
<?php
$code = trim($_POST['user_code'] ?? '');
$enteredPassword = $_POST['user_pass'] ?? '';

if ($code === '' || $enteredPassword === '') {
    echo "<h3>Please enter both a user code and password.</h3>";
    echo '<div class="input-box">
            <br>
            <!-- login fields  -->
            <form class="form-signin" action="indexLoginAction.php" method="post">
                <!-- input fields for loginID -->
                <div class="form-floating">
                    <input name="user_code" id="user_code" type="text" class="form-control" autofocus="">
                    <label for="user_code">User Code</label>
                </div>
                <br>
                <div class="form-floating">
                    <input name="user_pass" id="user_pass" type="password" class="form-control" placeholder="Password">
                    <label for="user_pass">Password</label>
                </div>
                <br>
                <button name="myButton" class="btn btn-submit full" type="submit">Sign in</button>

            </form>

        </div>';
} else{
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
    echo '<div class="input-box">
            <br>
            <!-- login fields  -->
            <form class="form-signin" action="indexLoginAction.php" method="post">
                <!-- input fields for loginID -->
                <div class="form-floating">
                    <input name="user_code" id="user_code" type="text" class="form-control" autofocus="">
                    <label for="user_code">User Code</label>
                </div>
                <br>
                <div class="form-floating">
                    <input name="user_pass" id="user_pass" type="password" class="form-control" placeholder="Password">
                    <label for="user_pass">Password</label>
                </div>
                <br>
                <button name="myButton" class="btn btn-submit full" type="submit">Sign in</button>

            </form>

        </div>';
}
?>
<?php
include_once "indexFooter.php";
?>