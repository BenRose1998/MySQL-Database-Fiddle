<?php
require_once('config.php');
session_start();

$error = null;


// Checks if the form has been submitted
if (isset($_POST) && !empty($_POST)) {
  // Stores user inputs as variables to be used later
  $username = $_POST['username'];
  $password = $_POST['password'];
  // Checks if inputs are empty, if so sends an error
  if (empty($username) || empty($password)){
    $error = "Please fill in all information";
  }else{
    if($username == $a_username && $password == $a_password){
      $_SESSION['first_name'] = $username;
      $_SESSION['admin'] = true;
      header('Location: dashboard.php');
      die();
    }else{
      // If the password inputted by user did not match then an error is sent
      $error = "Invalid username or password";
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Fiddle</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="css/master.css">
</head>
<body>


  <div class="card" id="staffLogRegForm">
    <div class="card-body">
      <form action="staff_login.php" method="POST">
        <h4>Staff Login</h4>
      <!-- If an error is sent it is displayed -->
      <?php if($error != null): ?>
        <h6 class='error'><?php echo $error; ?></h6>
      <?php endif; ?>
      <div class="form-group">
        <input name="username" type="text" class="form-control" placeholder="Username">
      </div>
      <div class="form-group">
        <input name="password" type="password" class="form-control" placeholder="Password">
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
  </div>
</div>
</body>
<script src="https://unpkg.com/ionicons@4.3.0/dist/ionicons.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="js/main.js"></script>

</html>
