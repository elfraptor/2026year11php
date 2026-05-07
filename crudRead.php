<?php
include_once "indexHeader.php";
?>
<html>
<h1>CRUD|Read</h1>
<p>SELECT * FROM `users` WHERE condition=value</p>

    <?php
    $query="SELECT * FROM `users` WHERE `status`='1'";
    $conn=new mysqli($host, $user, $pass, $db);
    $result = mysqli_query($conn, $query);
    $conn->close();

    // var_dump($result); // very useful for debugging

    if($result){ // if query runs
    if(mysqli_num_rows($result) > 0){ //checks if there are any records
    $output = '<table class="table">'; //$output is a variable that stores as a table
        $output .=  '<tr>
        <th>ID</th>
        <th>Code</th>
        <th>First name</th>
        <th>Last name</th>
        <th>Email</th>
        <th>Status</th>
        </tr>';
        while($row = mysqli_fetch_array($result)){ //loops through each record in result and outputs as row in table
        $output .=  '<tr>
        <td>'. $row["id"] .'</td>
        <td>'. $row["code"] .'</td>
        <td>'. $row["first_name"] .'</td>
        <td>'. $row["last_name"] .'</td>
        <td>'. $row["email"] .'</td>
        <td>'. $row["status"] .'</td>
        </tr>';
    }
} else {
    echo ("No Records found");
}
}else {
    echo ("Query returned FALSE");
}
$output .=  "</table>"; //closes the table
echo ($output); //outputs the table

include_once "indexFooter.php";
?>