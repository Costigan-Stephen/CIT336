<?php

/*
ACME CONTROLLER
*/
// Create or access a Session
if(empty($_SESSION)) session_start(); 

require_once 'library/connections.php';
require_once 'model/acme-model.php';
require_once 'model/products-model.php';
require_once 'model/uploads-model.php';
require_once 'library/functions.php';


$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
if ($action == NULL) {
 $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
}

 switch ($action){
  case 'something':
    break;

  default:
    $access = accessLevel('2');
    if($access == 1){
      $categoryList = showProductList();
      redirect($message, 'accounts', 'checklogin');
    } else {
      include 'view/home.php';
    }
    
}

?>