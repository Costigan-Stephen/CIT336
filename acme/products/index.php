<?php $page="Products"; //Current Navigation?> 
<?php
/*
PRODUCT CONTROLLER
*/

// Get the database connection file
require_once '../library/connections.php';

// Get the models for use as needed
require_once '../model/acme-model.php';
require_once '../model/products-model.php';


$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
  $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {
  case 'template':
    include '../template/template.php';
    break;
  case 'newProd':
    include '../view/new-prod.php';
    break;
  case 'newCat':
    include '../view/new-cat.php';
    break;
  case 'prodMgmt':
    include '../view/prod-mgmt.php';
    break;
  case 'submitProd':
    testProduct();
    break;
  case 'submitCat':
    testCategory();
    break;
  case 'return': //Redundant
    include '../view/prod-mgmt.php';
    break;
  default:
    include '../view/prod-mgmt.php';
}

?>