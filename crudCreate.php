<?php
include_once "indexHeader.php";
?>
<html>
<h1>CRUD|Create</h1>
    <form method="post" action="crudCreateAction.php">
    <label for="f_name">First name:</label><br>
        <input type="text" id="f_name" name="f_name"><br>
    <label for="l_name">Last name:</label><br>
        <input type="text" id="l_name" name="l_name"><br>
    <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>
        <input type="submit" name="submit" value="Submit">
    </form>
</html>
<!-- create a new record and insert in the users table in the datable yr_test_php-->
<!-- You will need to create a new page where you have input boxes -->
<?php
include_once "indexFooter.php";
?>