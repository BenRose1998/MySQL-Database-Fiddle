
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="index.php">MySQL Coding Playground</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">

    <?php if (isset($_SESSION['first_name'])) {
      // give user links
      echo '<ul class="navbar-nav mr-auto">';
      echo '<li class="nav-item">';
      // If admin display link as 'Admin Area' instead of 'Dashboard'
      if(isset($_SESSION['admin'])){
        if ($_SESSION['admin'] === true) {
          echo '<a class="nav-link" href="dashboard.php">Admin Area</a>';
        }else{
          echo '<a class="nav-link" href="dashboard.php">Worksheets</a>';
        }
      }
      echo '</li>';
      echo '</ul>';
      echo '<ul class="navbar-nav ml-auto">';
      echo '<li class="nav-item right">';
      echo '<a class="nav-link" href="logout.php">Log out</a>';
      echo '</li>';
      echo '</ul>';
    } else{
      echo '<ul class="navbar-nav ml-auto">';
      echo '<li class="nav-item right">';
      echo '<a class="nav-link" href="staff_login.php">Staff Login</a>';
      echo '</li>';
      echo '</ul>';
    }
    ?>
  </div>
</nav>
