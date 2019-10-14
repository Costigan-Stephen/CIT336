<?php $page="Accounts"; //Current Navigation?> 
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
  case 'register':
    // Filter and store the data
    $clientFirstname = filter_input(INPUT_POST, 'clientFirstname');
    $clientLastname = filter_input(INPUT_POST, 'clientLastname');
    $clientEmail = filter_input(INPUT_POST, 'clientEmail');
    $clientPassword = filter_input(INPUT_POST, 'clientPassword');

    if(empty($clientFirstname) || empty($clientLastname) || empty($clientEmail) || empty($clientPassword)){
        $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Please provide information for all empty form fields.</h3>';
        include '../view/register.php';
        exit; 
    }
    break;
  case 'login':
    include '../view/login.php';
    break;
  case 'signup':
    include '../view/register.php';
    break;
  default:
    include '../view/home.php';
}

?>