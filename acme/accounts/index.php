
<?php
/*
ACCOUNT CONTROLLER
*/

// Get the database connection file
require_once '../library/connections.php';

// Get the models for use as needed
require_once '../model/acme-model.php';
require_once '../model/accounts-model.php';



$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
  $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {
  case 'template':
    include '../template/template.php';
    break;
  case 'login':
    include '../view/login.php';
    break;
  case 'register':
    include '../view/register.php';
    break;
  case 'registersubmit':
    parseData();
    break;
  case 'loginsubmit':
    loginCheck();
    break;
  default:
    include '../view/home.php';
}

?>