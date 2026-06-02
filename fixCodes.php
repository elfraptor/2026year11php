
<?php 
include_once "indexHeader.php";
?>
<h1>CRUD code clear</h1>     
<!-- Code for Middle Part -->
<?php 
$conn = new mysqli($host, $user, $pass, $db);
// create codes and update the code field
$query0 = "SELECT * FROM users";
$result0 = mysqli_query($conn, $query0);
$newNumber = 1;
if($result0){ 
    if(mysqli_num_rows($result0) > 0){
        while($row = mysqli_fetch_array($result0)){
            // 1. Get first 3 characters from last name
            $first_three = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '',$row["last_name"]), 0, 3));
            // 2. Generate a random 4-digit number (0000 - 9999) and pad it
            //$randomNumbers = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT); 
            // 2. find highest number instead of random number
            // Find last highest code for this prefix
            $queryLast = "SELECT code 
                          FROM users 
                          WHERE code LIKE '".$first_three."%' 
                          ORDER BY code DESC 
                          LIMIT 1";
            $resultLast = mysqli_query($conn, $queryLast);   
            if($resultLast && mysqli_num_rows($resultLast) > 0){
                $lastRow = mysqli_fetch_assoc($resultLast);
                // Extract number part
                $lastNumber = (int)substr($lastRow["code"], 3);
                // Increment number
                $newNumber = $lastNumber + 1;
            } else {
                // Start from 0001
                $newNumber = 1;
            }           
            // 3. Concatenate
            //$temp_code = $first_three . $randomNumbers;
            // Create new code
            $temp_code = $first_three . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            // update in database table
            $query = "UPDATE users SET code = '".$temp_code."' where id= '".$row["id"]."'";
            $result = mysqli_query($conn, $query);
        }
        echo ("Codes created successfully");
    } else {
        echo ("No Records found");
    }
}else {
    echo ("Query returned FALSE");
}
// display the records on screen
$query1 = "SELECT * FROM users";
$result = mysqli_query($conn, $query1);
$conn->close();
//var_dump($result); // very useful for debugging
$output = "";
if($result){ // querry runs
    if(mysqli_num_rows($result) > 0){
        $output .= '<table class="table">';
        $output .=  '<tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>';
        while($row = mysqli_fetch_array($result)){
            $output .=  '<tr>
                            <td>'. $row["id"] .'</td>
                            <td>'. $row["code"] .'</td>
                            <td>'. $row["first_name"] .'</td>
                            <td>'. $row["last_name"] .'</td>
                            <td>'. $row["email"] .'</td>
                            <td>'. $row["status"] .'</td>
                        </tr>';
        }
        $output .=  "</table>";
    } else {
        echo ("No Records found");
    }
}else {
    echo ("Query returned FALSE");
}
echo ($output);
?>
<?php 
include_once "indexFooter.php";
?>
