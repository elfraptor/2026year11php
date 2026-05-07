<?php include_once "indexHeader.php"; ?>
<h1>CRUD Update With Modal</h1>

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
    <th>Operations</th>
    </tr>';
    while($row = mysqli_fetch_array($result)){ //loops through each record in result and outputs as row in table
    $output .=  '<tr>
    <td>'. $row["id"] .'</td>
    <td>'. $row["code"] .'</td>
    <td>'. $row["first_name"] .'</td>
    <td>'. $row["last_name"] .'</td>
    <td>'. $row["email"] .'</td>
    <td>'. $row["status"] .'</td>
      <td>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Update</button>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <form method="post" action="crudUpdateAction.php">
                <label for="id">ID</label><br>
                  <input type="text" id="id" name="id"><br><br>
                <label for="new_name">New First Name:</label><br>
                  <input type="text" id="new_name" name="new_name"><br><br>
                <label for="last_name">Last Name:</label><br>
                  <input type="text" id="last_name" name="last_name"><br><br>
                <label for="email">Email:</label><br>
                  <input type="email" id="email" name="email"><br><br>
                <label for="code">Code:</label><br>
                  <input type="text" id="code" name="code"><br><br>
                  <input type="submit" value="Update">
                </form>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              </div>
              <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>        </div>
              </div>
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

include_once "indexFooter.php";
?>
<?php
include_once "indexFooter.php";
?>
