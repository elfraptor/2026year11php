
<header data-bs-theme="dark">
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Yr11PHP2026 (bootstrap 5)</a>
      <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarCollapse"
      aria-controls="navbarCollapse"
      aria-expanded="false"
      aria-label="Toggle navigation"
      >
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
      <li class="nav-item"><a class="nav-link" href="https://www.chattrath.com.au/allProjects/recipe">Bobby's Recipes</a>
      </li>
      <li class="nav-item">
      <a class="nav-link disabled" aria-disabled="true">Disabled</a>
      </li>
      <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Core</a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
        <a class="dropdown-item" href="coreDecisions.php">Decisions</a>
        <a class="dropdown-item" href="coreVariables.php">Variables</a>
        <a class="dropdown-item" href="coreIterations.php">Iterations</a>
        <a class="dropdown-item" href="coreArrays.php">Arrays</a>
        </div>
      </li>
      <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">CRUD</a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
        <a class="dropdown-item" href="crudCreate.php">Create</a>
        <a class="dropdown-item" href="crudRead.php">Read</a>
        <a class="dropdown-item" href="crudReadOneRecord.php">Read One Record</a>
        <a class="dropdown-item" href="crudUpdate.php">Update</a>
        <a class="dropdown-item" href="crudUpdateWithModal.php">Update With Modal</a>
        <a class="dropdown-item" href="crudUpdateAllWithModal.php">Update All With Modal</a>
        <a class="dropdown-item" href="crudDelete.php">Delete</a>
        <a class="dropdown-item" href="fixCodes.php">Fix Codes</a>
        </div>
      </li>
      <?php if ($_SESSION['logged_in']): ?>
      <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Payslip</a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
        <a class="dropdown-item" href="payslipShifts.php">Shifts</a>
        </div>
      </li>
      <?php endif; ?>
    </ul>
    <?php if ($_SESSION['logged_in']): ?>
  <span class="navbar-text text-light me-3">Logged in as <?php echo htmlspecialchars($_SESSION['f_name'] ?? ''); ?></span>
  <a class="btn btn-outline-warning" href="indexLogout.php">Sign out</a>
    <?php else: ?>

  <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalLogin" data-bs-whatever="@mdo">Login</button>
    <?php endif; ?>
  </div>
</div>
</nav>
</header>

<?php if (!$_SESSION['logged_in']): ?>
<div class="modal fade" id="modalLogin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h3 class="modal-title" id="exampleModalLabel">Login</h3>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="input-box">
          <br>
          <!-- login fields  -->
          <form class="form-signin" action="indexLoginAction.php" method="post">
            <!-- input fields for loginID -->
            <div class="form-floating">
              <input name="user_code" id="user_code" type="text" class="form-control" autofocus="">
            <label for="user_code">User Code</label>
            </div>
            <br>
            <div class="form-floating">
              <input name="user_pass" id="user_pass" type="password" class="form-control" placeholder="Password">
            <label for="user_pass">Password</label>
            </div>
            <br>
          <button name="myButton" class="btn btn-outline-success" type="submit">Sign in</button>

          </form>

        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
