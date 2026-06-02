<?php
include_once "indexHeader.php";
?>
<html>
    <?php
     $code = htmlspecialchars($_POST['user_code']);
    $query="SELECT * FROM `users` WHERE `code`='".$code."'";
    $conn=new mysqli($host, $user, $pass, $db);
    $result = mysqli_query($conn, $query);
    $conn->close();
    $yay=[];
    $yay = mysqli_fetch_array($result);
    if ($yay){
        echo $yay['pass'];
    }
    else{
        echo "<h3>Please make an account.<h3>";
    }
?>
<?php
include_once "indexFooter.php";
?>