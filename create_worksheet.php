<?php
session_start();


if ($_SESSION['admin'] != true) {
  header("Location: index.php");
  die();
}else{

  if (isset($_GET['c']) && !empty($_GET['c'])) {
    if (file_exists('worksheets/' . $_GET['c'] . '.json')) {
      header('Location: dashboard.php?e=1');
      die();
    }
  }

  if (isset($_POST)) {
    if (isset($_POST['data']) && !empty($_POST['data']) && !isset($_GET['p'])) {
      $data = $_POST['data'];

      $decoded_data = json_decode($_POST['data'], true);
      // use first piece of data as file name (will usually be worksheet title)
      $filename = "worksheets/" . $decoded_data[0]['value'] . ".json";

      $fh = fopen($filename, 'w') or die("can't open file");

      fwrite($fh, $data);
      fclose($fh);
    }
  }

  if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $worksheet = "";
    //$worksheet = "Worksheet found";
    $file = file_get_contents('worksheets/' . $_GET['edit'] . '.json');
    $array = json_decode($file, true);
    $index = 0;

    foreach ($array as $arr) {
      switch ($arr['name']) {
        case 'title':
        // Create input element with default value as current value on worksheet (so it can be editted)
        $worksheet .= '<tr class="row' . $index . '"><td class="tableTitle"><h5>Title</h5></td></tr><tr class="row' . $index . '"><td><input type="text" name="title" placeholder="Title" class="form-control" value="' . $arr['value'] . '" /></td><td><button type="button" name="remove" id="' . $index . '" class="btn btn-danger btn_remove">X</button></td></tr>';
        $index++;
        break;
        case 'subtitle':
        $worksheet .= '<tr class="row' . $index . '"><td class="tableTitle"><h5>Subtitle</h5></td></tr><tr class="row' . $index . '"><td><input type="text" name="subtitle" placeholder="Subtitle" class="form-control" value="' . $arr['value'] . '" /></td><td><button type="button" name="remove" id="' . $index . '" class="btn btn-danger btn_remove">X</button></td></tr>';
        $index++;
        break;
        case 'text':
        $worksheet .= '<tr class="row' . $index . '"><td class="tableTitle"><h5>Text</h5></td></tr><tr class="row' . $index . '"><td><textarea type="text" name="text" placeholder="Text" class="form-control">'.$arr['value'].'</textarea></td><td><button type="button" name="remove" id="' . $index . '" class="btn btn-danger btn_remove">X</button></td></tr>';
        $index++;
        break;
        case 'code':
        $worksheet .= '<tr class="row' . $index . '"><td class="tableTitle"><h5>Read-Only Code</h5></td></tr><tr class="row' . $index . '"><td><textarea type="text" name="code" placeholder="Code" class="form-control">' . $arr['value'] . '</textarea></td><td class="removeBut"><button type="button" name="remove" id="' . $index . '" class="btn btn-danger btn_remove">X</button></td></tr>';
        $index++;
        break;
      }
    }
  }



  if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    unlink('worksheets/' . $_GET['delete'] . '.json');
    header('Location: dashboard.php');
    die();
  }

  ?>

  <!DOCTYPE html>
  <html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Fiddle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/create_worksheet.css">
  </head>
  <body>
    <script src="ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
    <?php include('navbar.php') ?>
    <div class="container-fluid" id="main">
      <div class="dropdown" id="addElement">
      </div>
      <form id="worksheet" method="post">
        <table class="table table-borderless" id="dynamicTable">
          <h3 id="filename"><?php
          if (isset($_GET)) {
            if (isset($_GET['c']) && !empty($_GET['c'])) {
              echo $_GET['c'];
            }else if (isset($_GET['edit']) && !empty($_GET['edit'])) {
              echo $_GET['edit'];
              echo $worksheet;
            }
          }
          ?></h3>
          <tr>
          </tr>
        </table>

        <button class="btn btn-success dropdown-toggle btn-block" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Add Element
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
          <button class="dropdown-item" type="button" id="addTitle">Title</button>
          <button class="dropdown-item" type="button" id="addSubtitle">Subtitle</button>
          <button class="dropdown-item" type="button" id="addText">Text</button>
          <button class="dropdown-item" type="button" id="addCode">Read-only Code</button>
        </div>
        <div class="" id="submitButtons">
          <button class="btn btn-primary" type="submit" id="submitButton"><?php echo isset($_GET['edit']) ? 'Save' : 'Create'; ?></button>
        </div>

      </form>
    </div>
  </body>
  <script src="https://unpkg.com/ionicons@4.3.0/dist/ionicons.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="js/main.js"></script>

  <?php if(isset($_GET['c'])){
    echo "<script>
    $(document).ready(function(){
      $('#addTitle').trigger('click');
      $('#addSubtitle').trigger('click');
      $('#addText').trigger('click');
      $('#addCode').trigger('click');
    });
    </script>";
  } ?>
  </html>
<?php } ?>
