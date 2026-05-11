<?php include_once "indexHeader.php"; ?>
<h1>CRUD Update AllWith Modal</h1>

  <?php
  include_once "crudUpdateAllWithModalAction.php";
  ?>
  <!-- Create new record button -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crudCreateModal" data-bs-whatever="@mdo">Create New Record</button>
  <div class="modal fade" id="crudCreateModal" tabindex="-1" aria-labelledby="crudCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <form method="post" action="crudCreateAction.php">
          <label for="first_name">First Name:</label><br>
            <input type="text" id="first_name" name="first_name"><br><br>
          <label for="last_name">Last Name:</label><br>
            <input type="text" id="last_name" name="last_name"><br><br>
          <label for="email">Email:</label><br>
            <input type="email" id="email" name="email"><br><br>
          <label for="code">Code:</label><br>
            <input type="text" id="code" name="code"><br><br>
            <input type="submit" value="Create">
          </form>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>        </div>
      </div>


    </html>
    <?php include_once "indexFooter.php";?>