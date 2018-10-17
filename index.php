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
</head>
<body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.1/ace.js"></script>
  <?php include('navbar.php') ?>
</nav>

<div class="container-fluid" id="main">
  <div class="row" id="editors">
    <div class="container-fluid col-lg editorContainer">
    </br>
    <h4>Build Schema</h4>
    <div class="form-control editor" id="schemaEditor"></div>
    <div id="schemaButtons">
      <button class="btn btn-primary" type="button" id="schemaButton" name="button">Build Schema</button>
      <div class="dropdown" id="loadExample">
        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Load Example
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <a id="exampleButton1" class="dropdown-item" href="#">Basic</a>
          <a id="exampleButton2" class="dropdown-item" href="#">Inner Join</a>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid col-lg editorContainer">
  </br>
  <h4>Run SQL</h4>
  <div class="form-control editor" id="queryEditor"></div>
  <button class="btn btn-primary" type="button" id="queryButton" name="button">Run SQL</button>
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
