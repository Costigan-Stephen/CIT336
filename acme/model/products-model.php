<?php
/*
ACME MODEL
*/
function sqlConnection($var){
  // Create a connection object from the acme connection function
  $db = acmeConnect();
  // The SQL statement to be used with the database 
  if($var){ 
    $sql = $var; 
  } else { 
    $sql = 'SELECT invName FROM inventory'; 
  }     
  $stmt = $db->prepare($sql);
  // The next line runs the prepared statement 
  $stmt->execute();
  // The next line gets the data from the database and 
  // stores it as an array in the $categories variable 
  $return = $stmt->fetchAll();
  // The next line closes the interaction with the database 
  $stmt->closeCursor();
  // The next line sends the array of data back to where the function 
  // was called (this should be the controller) 
  return $return;
}

function regCat($categoryName){
  // Create a connection object using the acme connection function
  $db = acmeConnect();
  // The SQL statement
  $sql = 'INSERT INTO categories (categoryId, categoryName)
          VALUES (:categoryId, :categoryName)';
  // Create the prepared statement using the acme connection
  $stmt = $db->prepare($sql);
  // The next four lines replace the placeholders in the SQL
  // statement with the actual values in the variables
  // and tells the database the type of data it is
  $stmt->bindValue(':categoryId', '', PDO::PARAM_STR);
  $stmt->bindValue(':categoryName', $categoryName, PDO::PARAM_STR);
  // Insert the data
  $stmt->execute();
  // Ask how many rows changed as a result of our insert
  $rowsChanged = $stmt->rowCount();
  // Close the database interaction
  $stmt->closeCursor();
  // Return the indication of success (rows changed)
  return $rowsChanged;
}

function regProduct($CategoryId, $invName, $invDescription, $invImage, $invThumbnail, $invPrice, $invStock, $invSize, $invWeight, $invLocation, $invVendor, $invStyle,$invId=NULL){
  // Create a connection object using the acme connection function
  $db = acmeConnect();
  // The SQL statement
  $sql = 'INSERT INTO inventory ( invId, invName, invDescription, invImage, invThumbnail, invPrice, invStock, invSize, invWeight, invLocation, categoryId, invVendor, invStyle) 
          VALUES (:invId, :invName, :invDescription, :invImage, :invThumbnail, :invPrice, :invStock, :invSize, :invWeight, :invLocation, :categoryId, :invVendor, :invStyle)';
  // Create the prepared statement using the acme connection

  $stmt = $db->prepare($sql);
  // The next four lines replace the placeholders in the SQL
  // statement with the actual values in the variables
  // and tells the database the type of data it is
  $stmt->bindValue(':invId', NULL, PDO::PARAM_STR);
  $stmt->bindValue(':invName', $invName, PDO::PARAM_STR);
  $stmt->bindValue(':invDescription', $invDescription, PDO::PARAM_STR);
  $stmt->bindValue(':invImage', $invImage, PDO::PARAM_STR);
  $stmt->bindValue(':invThumbnail', $invThumbnail, PDO::PARAM_STR);
  $stmt->bindValue(':invPrice', $invPrice, PDO::PARAM_STR);
  $stmt->bindValue(':invStock', $invStock, PDO::PARAM_STR);
  $stmt->bindValue(':invSize', $invSize, PDO::PARAM_STR);
  $stmt->bindValue(':invWeight', $invWeight, PDO::PARAM_STR);
  $stmt->bindValue(':invLocation', $invLocation, PDO::PARAM_STR);
  $stmt->bindValue(':categoryId', $CategoryId, PDO::PARAM_STR);
  $stmt->bindValue(':invVendor', $invVendor, PDO::PARAM_STR);
  $stmt->bindValue(':invStyle', $invStyle, PDO::PARAM_STR);

  // Insert the data
  $stmt->execute();
  // Ask how many rows changed as a result of our insert
  $rowsChanged = $stmt->rowCount();
  // Close the database interaction
  $stmt->closeCursor();
  // Return the indication of success (rows changed)
  return $rowsChanged;
}

function productList(){

  // Get the array of categories
  $categories = getCategories();
  $return = '';
  foreach ($categories as $category) {
    $return .= "<option value='" . strtolower($category['categoryName']) . "'>". $category['categoryName'] ."</option>";
  }
  return $return ;
}

function selectFrom($colname="invName", $table="inventory",$sort="invName",$order="ASC"){

  $sql = 'SELECT '.$colname.' FROM '.$table.' ORDER BY '.$sort. ' ASC';  
  $return = sqlConnection($sql);
  return $return;

}

//Check if category/product exists in database
function searchForField($colname, $table="inventory", $field, $col2=NULL, $result=0){

  if (empty($col2) || $col2 == NULL){
    $col2 = $colname;
  }

    $fieldParse = '\''.$field.'\'';
    $sql = 'SELECT '. $colname .' FROM '.$table.' WHERE '.$col2.' = '. $fieldParse .';';
    $fieldResult = '';

    $stmt = sqlConnection($sql);
    // print_r (array_values($stmt));
    // printf ($colname);
    // print_r ($stmt[0][$colname]);
    // print_r ( array_search( $colname, $stmt ));
    // exit;
    $array = array_values($stmt);
    if (empty($stmt) || $stmt = NULL) {
      $fieldResult = '0';
    } else {
      $fieldResult = $array[0][$colname];
    }
    return $fieldResult;
}

//SUBMIT!
function testCategory(){

  $categoryName = filter_input(INPUT_POST, 'categoryName');

  if(empty($categoryName)){
    $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Please enter a category name!.</h3>';
      include '../view/new-cat.php';
      exit; 
  }
  
  $exists = searchForField("categoryName", "categories", $categoryName);

  if(empty($exists)){
    // echo "good to go";
    $regOutcome = regCat($categoryName);
    $message = '<h3 style="text-align:center; font-weight: bold; color: green;">Category Added Successfully</h3>';
    include '../view/new-cat.php';
  } else {
    $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Category already exists</h3>';
    include '../view/new-cat.php';
    exit; 
  }
  
}

function testProduct(){
  // Filter and store the data
  $invCategory = filter_input(INPUT_POST, 'invCategory');
  $invName = filter_input(INPUT_POST, 'invName');
  $invDescription = filter_input(INPUT_POST, 'invDescription');
  $invImage = filter_input(INPUT_POST, 'invImage');
  $invThumbnail = filter_input(INPUT_POST, 'invThumbnail');
  $invPrice = filter_input(INPUT_POST, 'invPrice');
  $invStock = filter_input(INPUT_POST, 'invStock');
  $invSize = filter_input(INPUT_POST, 'invSize');
  $invWeight = filter_input(INPUT_POST, 'invWeight');
  $invLocation = filter_input(INPUT_POST, 'invLocation');
  $invVendor = filter_input(INPUT_POST, 'invVendor');
  $invStyle = filter_input(INPUT_POST, 'invStyle');


  if ($invCategory == ""){
    $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Please select a Category!</h3>';
      include '../view/new-prod.php';
      exit; 
  }

  if(empty($invCategory) || empty($invName) || empty($invDescription) || empty($invImage) || empty($invThumbnail) || empty($invPrice) || empty($invStock) || empty($invSize) || empty($invWeight) || empty($invLocation) || empty($invVendor) || empty($invStyle)){
      $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Please provide information for all empty form fields.</h3>';
      include '../view/new-prod.php';
      exit; 
  }

  //GET Cat ID
  $CategoryId = searchForField('categoryId', 'categories', $invCategory, 'categoryName', 1);

  if(empty($CategoryId)) {
    $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Category Error.</h3>';
    include '../view/new-prod.php';
    exit; 
}
  // Send the data to the model
  $regOutcome = regProduct($CategoryId, $invName, $invDescription, $invImage, $invThumbnail, $invPrice, $invStock, $invSize, $invWeight, $invLocation, $invVendor, $invStyle);

  // Check and report the result
  if($regOutcome === 1){
      $message = '<h3 style="text-align:center; font-weight: bold;">Item Added Successfully!</h3>';
      include '../view/new-prod.php';
      exit;
  } else {
      $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Something went wrong, Please try again!</h3>';
      include '../view/new-prod.php';
      exit;
  }
}
?>