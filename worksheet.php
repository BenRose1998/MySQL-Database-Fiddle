<?php
session_start();


if (!isset($_GET['w']) || empty($_GET['w'])) {
  $worksheet = "Worksheet not found or empty";
  //$worksheet = "<h1>Worksheet 1</h1>";
}else{
  $worksheet = "";
  //$worksheet = "Worksheet found";
  $file = file_get_contents('worksheets/' . $_GET['w'] . '.json');
  $array = json_decode($file, true);

  $worksheet = displayWorksheet($array);
}

function displayWorksheet($array){
  $worksheet = "";
  foreach ($array as $arr) {
    switch ($arr['name']) {
      case 'title':
      $worksheet .= "<h2>" . $arr['value'] . "</h2>";
      break;
      case 'subtitle':
      $worksheet .= "<h4>" . $arr['value'] . "</h4>";
      break;
      case 'text':
      $worksheet .= "<p>" . $arr['value'] . "</p>";
      break;
      case 'code':
      $worksheet .= "<div class='editor readOnly' id='readOnly' value='" . $arr['value'] . "'></div>";
      //$worksheet .= "<p>" . $arr['value'] . "</p>";
      break;
    }
  }
  return $worksheet;
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Fiddle</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="css/master.css">
  <link rel="stylesheet" href="css/worksheet.css">
</head>
<body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.1/ace.js"></script>

  <?php include('navbar.php') ?>

  <div class="container-fluid" id="main">
    <!-- <a class="btn btn-secondary" href=""  role="button" id="hideWorksheetButton">Hide Worksheet</a> -->
    <?php
    if(isset($_SESSION['admin']) && isset($_GET['w'])){
      if ($_SESSION['admin'] === true) {
        ?>
      </br>
      <a class="btn btn-secondary" href="dashboard.php"  role="button">Back</a>
      <a class="btn btn-secondary" href="create_worksheet.php?edit=<?php echo $_GET['w'] ?>"  role="button">Edit</a>
      <a class="btn btn-danger" href="create_worksheet.php?delete=<?php echo $_GET['w'] ?>" role="button">Delete</a>
      <?php
    }
  }
  ?>
  <div class="row">
    <div class="col col-lg-5" id="worksheetCol">
      <div class="card" id="worksheet">
        <div class="card-body">
          <!-- echo worksheet here -->
          <?php echo $worksheet; ?>
        </div>
      </div>
    </div>
    <div class="col col-lg-7" id="editorCol">
      <div class="row" id="editors">
        <div class="container-fluid col-lg editorContainer">
        </br>
        <h4>Build Schema</h4>
        <div class="form-control editor" id="wSchemaEditor"></div>
        <div id="schemaButtons">
          <button class="btn btn-primary" type="button" id="schemaButton" name="button" data-toggle="tooltip" data-placement="right" title="Please rebuild after making any changes">Build Schema</button>
        </div>
      </div>
    </div>
    <div class="row" id="editors">
      <div class="container-fluid col-lg editorContainer">
      </br>
      <h4>Run SQL</h4>
      <div class="form-control editor" id="wQueryEditor"></div>
      <button class="btn btn-primary" type="button" id="queryButton" name="button">Run SQL</button>
    </div>
  </div>
</div>
</div>
</br>

<div id="messages"></div>
<div class="row">
  <div class="container-fluid col-lg">
    <div class="card">
      <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
        <h5 id="outputText" class="noselect">Output</h5>
        <ion-icon size="medium" name="arrow-dropdown"></ion-icon>
      </div>
      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne">
        <div class="card-body" id="output">
          Please build schema and run SQL.
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php include_once('footer.html') ?>
