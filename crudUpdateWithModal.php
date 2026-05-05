<?php include_once "indexHeader.php"; ?>
<h1>CRUD Read One Record Action Result</h1>
  <!-- Code for Middle Part -->
  <form method="post" action="">
  <label for="id">ID</label><br>
    <input type="text" id="id" name="id"><br><br>
  <label for="submit"></label><br>
    <input type="submit" value="Submit">
  </form>
  <?php
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = htmlspecialchars($_POST['id']);
    //echo $id;
    $query1 = "SELECT * FROM users WHERE id=$id";
    //echo ('<br>' . $query1 . '<br>');
    $conn = new mysqli($host, $user, $pass, $db);
    $result = mysqli_query($conn, $query1);
    $conn->close();
    //var_dump($result); // very useful for debugging
    if($result){ // query runs
    if(mysqli_num_rows($result) > 0){
      $output = '<table class="table">';
        $output .=  '<tr>
        <th>ID</th>
        <th>Code</th>
        <th>First name</th>
        <th>Last name</th>
        <th>Email</th>
        <th>Status</th>
        <th>Operations</th>
        </tr>';
        while($row = mysqli_fetch_array($result)){
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
                      <form method="crudUpdateWithModal.php" action="">
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
        $output .=  "</table>";
        echo ($output);
      } else {
        echo ("No Records found");
      }
    }else {
      echo ("Query returned FALSE");
    }
  }
  ?>
  <?php

  include_once "indexFooter.php";
  ?>
  <?php
  include_once "indexFooter.php";
  ?>
