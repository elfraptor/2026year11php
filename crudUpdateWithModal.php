<?php
include_once "indexHeader.php";
?>
<html>
<h1>Title</h1>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Update</button>
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <form method="post" action="">
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Send message</button>
        </div>
      </div>
    </div>
  </div>
</html>

<?php
include_once "indexFooter.php";
?>