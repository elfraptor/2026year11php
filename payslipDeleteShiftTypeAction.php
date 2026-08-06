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

            $conn = mysqli_connect("localhost", "root", "", "yr11_test_db");

            if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $sql = "UPDATE shift_types SET status = 0 WHERE id = ? AND user_code = ?";
                $stmt = mysqli_prepare($conn, $sql);

                if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "is", $id, $user_code);

                        if (mysqli_stmt_execute($stmt)) {
                                echo "Record deleted successfully";
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