<?php
include_once "indexHeader.php";
?>

<html>
    <br>
<h1>Login</h1>
</html>
<div class="col-md-3 col-sm-3 mt-3">
            <br>
            <!-- login fields  -->
            <form class="form-signin" action="indexLoginAction.php" method="post" onsubmit="indexLoginAction.php">
                <!-- input fields for loginID -->
                <div class="form-floating">
                    <input name="user_code" id="user_code" type="test" class="form-control" autofocus="">
                    <label for="user_code">User Code</label>
                </div>
                <br>
                <div class="form-floating">
                    <input name="user_pass" id="user_pass" type="password" class="form-control" placeholder="Password">
                    <label for="user_pass">Password</label>
                </div>
                <br>
                <button name="myButton" class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>

            </form>

        </div>
<?php
include_once "indexFooter.php";
?>