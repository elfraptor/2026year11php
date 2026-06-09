
<header data-bs-theme="dark">
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Carousel</a>
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
      <?php if (!empty($_SESSION['logged_in'])): ?>
        <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Payslip</a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
        <a class="dropdown-item" href="payslipShifts.php">Shifts</a>
        </div>
      </li>
      <?php endif; ?>
    </ul>
      <?php if (!empty($_SESSION['logged_in'])): ?>
        <span class="navbar-text text-light me-3">Logged in</span>
        <a class="btn btn-outline-warning" href="indexLogout.php">Sign out</a>
      <?php else: ?>
        <a class="btn btn-outline-success" href="indexLogin.php">Login</a>
      <?php endif; ?>
  </div>
</div>
</nav>
</header>
