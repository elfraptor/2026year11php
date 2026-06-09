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
include_once "indexFooter.php";
?>