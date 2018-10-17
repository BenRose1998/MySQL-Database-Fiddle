<?php
require_once('config.php');
session_start();

if (isset($_POST['button'])) {
  switch ($_POST['button']) {
    case 'schema':
      if (isset($_POST['schema']) && !empty($_POST['schema'])){
        createDatabase();
        $pdo = createPDO();
        buildSchema($_POST['schema'], $pdo);
        deleteDatabase($pdo);
        }
      break;
    case 'query':
      if (isset($_POST['query']) && !empty($_POST['query'])){
        createDatabase();
        $pdo = createPDO();
        buildSchema($_POST['schema'], $pdo);
        $stmts = explode(";", $_POST['query']);
        echo json_encode(runQueries($stmts, $pdo));
        deleteDatabase($pdo);
      }
      break;
  }
}
function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

function createDatabase(){

  $_SESSION['id'] = generateRandomString();
  $id = $_SESSION['id'];

  $dsn = 'mysql:host=' . HOSTNAME;
  $pdo = new PDO($dsn, USERNAME, PASSWORD);

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "CREATE USER '".$id."'@".HOSTNAME." IDENTIFIED VIA mysql_native_password USING '';
  GRANT USAGE ON *.* TO '".$id."'@".HOSTNAME." REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
  CREATE DATABASE IF NOT EXISTS `".$id."`;
  GRANT ALL PRIVILEGES ON `".$id."`.* TO '".$id."'@".HOSTNAME.";";

  // $stmt = $pdo->prepare($sql);
  $pdo->exec($sql);

  $conn = null;
}

function createPDO(){
  $dsn = 'mysql:host=' . HOSTNAME . ';dbname=' . $_SESSION['id'];

  $opt = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM,
  PDO::ATTR_EMULATE_PREPARES => false ];

  return new PDO($dsn, $_SESSION['id'], '', $opt);
}

function deleteDatabase($pdo){
  $sql = "DROP DATABASE " . $_SESSION['id'];
  // $stmt = $pdo->prepare($sql);
  $pdo->exec($sql);
  $conn = null;
}

function buildSchema($schema, $pdo){
  $stmts = explode(";", $schema);
  for ($i = 0; $i < count($stmts); $i++) {
    $stmt = $i != count($stmts)- 1 ? $stmts[$i] . ";" : $stmts[$i];
    if (!empty($stmts[$i])) {
      try {
        $pdo->exec($stmt);
      } catch(PDOException $e){
        echo $e->getMessage();
      }
    }
  }
}

function runQueries($stmts, $pdo){
  $success = false;
  $returnData = array();
  foreach ($stmts as $stmt) {
    if (!empty($stmt)) {
      try{
        $query = $pdo->query($stmt . ";");
        $result = $query->fetchAll();
        $headers = array();
        // Add column header to headers array backwards as array_unshift() adds to front of array
        for ($j = $query->columnCount() - 1; $j >= 0; $j--) {
          $header = $query->getColumnMeta($j);
          array_unshift($headers, $header['name']);
        }
        // Add headers array to start of result array
        array_unshift($result, $headers);
        array_push($returnData, $result);
        $success = true;
      } catch(PDOException $e){
        $error = array($e->getMessage());
        array_unshift($returnData, $error);
        // If an error occurs with any query, stop running queries and send error message
        break;
      }
    }
  }
  array_unshift($returnData, $success);
  return $returnData;
}

?>
