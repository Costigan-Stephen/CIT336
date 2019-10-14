<?php

/*
ACME CONTROLLER
*/

// Get the database connection file
require_once 'library/connections.php';
// Get the acme model for use as needed
require_once 'model/acme-model.php';

// echo $navList;
// exit;

$action = filter_input(INPUT_POST, 'action');
 if ($action == NULL){
  $action = filter_input(INPUT_GET, 'action');
 }

 switch ($action){
  case 'something':
    break;

  default:
    include 'view/home.php';

}

?>