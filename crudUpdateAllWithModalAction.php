<?php
//initialises query to select all records from users table where status is 1 (active)
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
  <th>Operations</th>
  </tr>';
  while($row = mysqli_fetch_array($result)){ //loops through each record in result and outputs as row in table
  $output .=  '<tr>
  <td>'. $row["id"] .'</td>
  <td>'. $row["code"] .'</td>
  <td>'. $row["f_name"] .'</td>
  <td>'. $row["l_name"] .'</td>
  <td>'. $row["email"] .'</td>
  <td>'. $row["status"] .'</td>
    <td>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal'. $row["id"] .'" data-bs-whatever="@mdo">Edit</button>
      <div class="modal fade" id="updateModal'. $row["id"] .'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
          <h3>Update Record ID: '. $row["id"] .'</h3>
            <div class="modal-header">
              <div class="modal-body">
                <form method="post" action="crudUpdateAction.php">
                  <input type="hidden" id="id" name="id" value="'. $row["id"] .'"><br>
                <label for="f_name">First Name:</label><br>
                  <input type="text" id="f_name" name="f_name" value="'. $row["f_name"] .'"><br>
                <label for="l_name">Last Name:</label><br>
                  <input type="text" id="l_name" name="l_name" value="'. $row["l_name"] .'"><br>
                <label for="email">Email:</label><br>
                  <input type="email" id="email" name="email" value="'. $row["email"] .'"><br>
                  <input type="submit" value="Submit">
                </form>
              </div>
            </div>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </td>
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

?>
