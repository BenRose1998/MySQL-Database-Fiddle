<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Fiddle</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="css/master.css">
  <link rel="stylesheet" href="css/adminarea.css">
</head>
<body>
  <script src="ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
  <?php include('navbar.php') ?>
  <div class="container" id="main">

    <?php
    if(isset($_GET['e']) && !empty($_GET['e'])){
      switch ($_GET['e']) {
        case 1:
        echo '<div class="alert alert-danger" role="alert">';
        echo 'Worksheet with that file name already exists. Please try again.';
        echo '</div>';
        break;
        default:
        echo '<div class="alert alert-danger" role="alert">';
        echo 'An unknown error occured.';
        echo '</div>';
        break;
      }
    }

    if(!isset($_SESSION['first_name'])){
      header("Location: index.php");
      die();
    }else if($_SESSION['admin'] === true){
      echo '<h4>Admin Options</h4>';
      echo '<div id="dashboardButtons">';
        echo '<a role="button" class="btn btn-dark btn-lg" href="create_worksheet.php" data-toggle="modal" data-target="#filenameModal">Create Worksheet</a>';
      echo '</div>';
    }
    ?>

    <ul class="list-group" id="worksheetList">
      <h4>Worksheets</h4>
      <?php
      $dir   = 'worksheets';
      $files = scandir($dir);
      for ($i=2; $i < count($files); $i++) {
        $title = str_replace('.json', '', $files[$i]);
        echo '<a class="list-group-item list-group-item-action" href="worksheet.php?w=' . $title . '">' . $title . '</a>';
      }
      ?>
    </ul>
    <div class="modal fade" id="filenameModal" tabindex="-1" role="dialog" aria-labelledby="filenameModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="filenameModalLabel">File Name</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Send GET request with name of new worksheet -->
          <form action="create_worksheet.php" method="get">
            <div class="modal-body">
              <!-- Worksheet Name -->
              <input class="form-control" type="text" name="c" value="" placeholder="Please enter a file name">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Create</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
<script src="https://unpkg.com/ionicons@4.3.0/dist/ionicons.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="js/admin_area.js"></script>


</html>
