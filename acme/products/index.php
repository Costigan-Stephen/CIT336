<?php $page="Products"; //Current Navigation?> 
<?php
/*
PRODUCT CONTROLLER
*/

// Create or access a Session
if(empty($_SESSION)) session_start(); 

require_once '../library/connections.php';
require_once '../model/acme-model.php';
require_once '../model/products-model.php';
require_once '../model/uploads-model.php';
require_once '../model/reviews-model.php';
require_once '../library/functions.php';


$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
if ($action == NULL) {
 $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
}

switch ($action) {
  case 'category':
    $categoryName = filter_input(INPUT_GET, 'categoryName', FILTER_SANITIZE_STRING);
    $products = getProductsByCategory($categoryName);
    if(!count($products)){
      $message = "<p class='notice'>Sorry, no $categoryName products could be found.</p>";
    } else {
      $prodDisplay = buildProductsDisplay($products);
      // echo $prodDisplay;
      // exit;
    }
      include '../view/category.php';
  break;

  case 'del':
    $invId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $prodInfo = getProductInfo($invId);
    if (count($prodInfo) < 1) {
      $message = '<p class="center error">Sorry, no product information could be found.</p>';
    }
    include '../view/prod-delete.php';
  break; 

  case 'deleteProd':
    $invName = filter_input(INPUT_POST, 'invName', FILTER_SANITIZE_STRING);
    $invId = filter_input(INPUT_POST, 'invId', FILTER_SANITIZE_NUMBER_INT);
          
    $deleteResult = deleteProduct($invId);
    if ($deleteResult) {
      $message = "<p class='center success'>Congratulations', $invName was successfully deleted.</p>";
      redirect($message, 'products', '');
    } else {
      $message = "<p class='center error'>Error: $invName was not deleted.</p>";
      redirect($message, 'products', '');
    }
  break; 

  case 'getInventoryItems': 
    // Get the categoryId 
    $categoryId = filter_input(INPUT_GET, 'categoryId', FILTER_SANITIZE_NUMBER_INT); 
    // Fetch the products by categoryId from the DB 
    $productsArray = getProductsByCategoryId($categoryId); 
    // Convert the array to a JSON object and send it back 
    echo json_encode($productsArray); 
  break;

  case 'mod':
      $invId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
      $prodInfo = getProductInfo($invId);
      if(count($prodInfo)<1){
        $message = '<p class="center error">Sorry, no product information could be found.</p>';
      }
      include '../view/prod-update.php';
      exit;
  break;

  case 'newCat':
      $access = accessLevel('2');
      if($access == 1){
        include '../view/new-cat.php';
      } else {
        $message = '<h3 class="center bold error">Insufficient Permissions</h3>';
        redirect($message, '', '');
      }
  break;

  case 'newProd':
    $access = accessLevel('2');
    if($access == 1){
      include '../view/new-prod.php';
    } else {
      $message = '<h3 class="center bold error">Insufficient Permissions</h3>';
      redirect($message, 'accounts', 'checklogin');
    }
  break;

  case 'product':
    $invName = filter_input(INPUT_GET, 'invName', FILTER_SANITIZE_STRING);
    $categoryName = filter_input(INPUT_GET, 'categoryName', FILTER_SANITIZE_STRING);
    $product = getProduct($invName);

    foreach ($product as $productSet) { 
      $invId = $productSet['invId']; 
    } 

    $images = getImages();
    $prodReviews = getReviews();
    $i = 0;

    foreach($images as $image){
      $i = $i + 1;
      if($image['invId'] == $invId){ $filterImages[$i] = $image;}
    }

    foreach($prodReviews as $review){
      $i = $i + 1;
      if($review['invId'] == $invId){ $filterReviews[$i] = $review;}
    }

    if(!count($product)){
      $message = "<p class='notice'>Sorry, something went wrong. Couldn't display $invName!</p>";
    } else {
      $prodDisplay = buildProductView($product);
      if( !empty($filterImages) ){$imageDisplay = buildImageDisplay($filterImages);}
      if( !empty($filterReviews) ){$reviews = buildReviewsDisplay($filterReviews);} 
    }
      include '../view/product-detail.php';
  break;

  case 'prodMgmt':
    $access = accessLevel('2');
    if($access == 1){
      $categoryList = showProductList();
      include '../view/prod-mgmt.php';
    } else {
      $message = '<h3 class="center bold error">Insufficient Permissions</h3>';
      redirect($message, '', '');
    }
  break;

  case 'prodUpdate': //Go to ProdUpdate layout -For testing
      $access = accessLevel('2');
      if($access == 1){
        $categoryList = showProductList();
        include '../view/prod-update.php';
      } else {
        $message = '<h3 class="center bold error">Insufficient Permissions</h3>';
        redirect($message, '', '');
      }
  break;

  case 'return': //Redundant
    $access = accessLevel('2');
    if($access == 1){
      $categoryList = showProductList();
      include '../view/prod-mgmt.php';
    } else {
      $message = '<h3 class="center bold error">Insufficient Permissions</h3>';
      redirect($message, '', '');
    }
  break;

  case 'submitProd':
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
   
   
     if ($invCategory == "-"){
       $message = '<h3 class="center bold error">Please select a Category!</h3>';
         redirect($message, 'products', 'newProd');
         exit; 
     }
   
     if(empty($invCategory) || empty($invName) || empty($invDescription) || empty($invImage) || empty($invThumbnail) || empty($invPrice) || empty($invStock) || empty($invSize) || empty($invWeight) || empty($invLocation) || empty($invVendor) || empty($invStyle)){
         $message = '<h3 class="center bold error">Please provide information for all empty form fields.</h3>';
         redirect($message, 'products', 'newProd');
         exit; 
     }
   
     //GET Cat ID
     $CategoryId = searchForField('categoryId', 'categories', $invCategory, 'categoryName', 1);
   
     if(empty($CategoryId)) {
       $message = '<h3 class="center bold error">The category that you have selected for this product no longer exists.</h3>';
       redirect($message, 'products', 'newProd');
       exit; 
     }
     // Send the data to the model
     $regOutcome = regProduct($CategoryId, $invName, $invDescription, $invImage, $invThumbnail, $invPrice, $invStock, $invSize, $invWeight, $invLocation, $invVendor, $invStyle);
   
     // Check and report the result
     if($regOutcome === 1){
        $message = '<h3 class="center bold">Item Added Successfully!</h3>';
        redirect($message, 'products', 'prodMgmt');
     } else {
        $message = '<h3 class="center bold error">Something went wrong, Please try again!</h3>';
        redirect($message, 'products', 'newCat');
     }
  break;

  case 'submitCat':
    $categoryName = filter_input(INPUT_POST, 'categoryName');

    if(empty($categoryName)){
      $message = '<h3 class="center bold error">Please enter a category name!.</h3>';
        redirect($message, 'products', 'newCat');
        exit; 
    }
    
    $exists = searchForField("categoryName", "categories", $categoryName);
  
    if(empty($exists)){
      regCat($categoryName);
      $message = '<h3 class="center bold success">Category Added Successfully</h3>';
      redirect($message, 'products', 'prodMgmt');
    } else {
      $message = '<h3 class="center bold error">Category already exists</h3>';
      redirect($message, 'products', 'newCat');
      exit; 
    }
  break;

  case 'template':
    include '../template/template.php';
  break;

  case 'updateProd':  //Make changes to product
    $invCategory = filter_input(INPUT_POST, 'invCategory', FILTER_SANITIZE_NUMBER_INT);
    $invName = filter_input(INPUT_POST, 'invName', FILTER_SANITIZE_STRING);
    $invDescription = filter_input(INPUT_POST, 'invDescription', FILTER_SANITIZE_STRING);
    $invImage = filter_input(INPUT_POST, 'invImage', FILTER_SANITIZE_STRING);
    $invThumbnail = filter_input(INPUT_POST, 'invThumbnail', FILTER_SANITIZE_STRING);
    $invPrice = filter_input(INPUT_POST, 'invPrice', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $invStock = filter_input(INPUT_POST, 'invStock', FILTER_SANITIZE_NUMBER_INT);
    $invSize = filter_input(INPUT_POST, 'invSize', FILTER_SANITIZE_NUMBER_INT);
    $invWeight = filter_input(INPUT_POST, 'invWeight', FILTER_SANITIZE_NUMBER_INT);
    $invLocation = filter_input(INPUT_POST, 'invLocation', FILTER_SANITIZE_STRING);
    $invVendor = filter_input(INPUT_POST, 'invVendor', FILTER_SANITIZE_STRING);
    $invStyle = filter_input(INPUT_POST, 'invStyle', FILTER_SANITIZE_STRING);
    $invId = filter_input(INPUT_POST, 'invId', FILTER_SANITIZE_NUMBER_INT);

    if (empty($invCategory) || empty($invName) || empty($invDescription) 
    || empty($invImage) || empty($invThumbnail) || empty($invPrice) 
    || empty($invStock) || empty($invSize) || empty($invWeight) 
    || empty($invLocation) || empty($invVendor) || empty($invStyle)) {
     $message = '<p class="center">Please complete all information for the new item! Double check the category of the item.</p>';
     include '../view/prod-update.php';
     exit;
    }

    $db = acmeConnect();
    // The SQL statement to be used with the database
    $sql = 'UPDATE inventory SET invName = :invName, 
        invDescription = :invDescription, invImage = :invImage, 
        invThumbnail = :invThumbnail, invPrice = :invPrice, 
        invStock = :invStock, invSize = :invSize, 
        invWeight = :invWeight, invLocation = :invLocation, 
        categoryId = :catType, invVendor = :invVendor, 
        invStyle = :invStyle WHERE invId = :invId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':catType', $invCategory, PDO::PARAM_INT);
    $stmt->bindValue(':invName', $invName, PDO::PARAM_STR);
    $stmt->bindValue(':invDescription', $invDescription, PDO::PARAM_STR);
    $stmt->bindValue(':invImage', $invImage, PDO::PARAM_STR);
    $stmt->bindValue(':invThumbnail', $invThumbnail, PDO::PARAM_STR);
    $stmt->bindValue(':invPrice', $invPrice, PDO::PARAM_STR);
    $stmt->bindValue(':invStock', $invStock, PDO::PARAM_INT);
    $stmt->bindValue(':invSize', $invSize, PDO::PARAM_INT);
    $stmt->bindValue(':invWeight', $invWeight, PDO::PARAM_INT);
    $stmt->bindValue(':invLocation', $invLocation, PDO::PARAM_STR);
    $stmt->bindValue(':invVendor', $invVendor, PDO::PARAM_STR);
    $stmt->bindValue(':invStyle', $invStyle, PDO::PARAM_STR);
    $stmt->bindValue(':invId', $invId, PDO::PARAM_INT);
    $stmt->execute();
    $updateResult = $stmt->rowCount();
    $stmt->closeCursor();

    if ($updateResult) {
     $message = "<p class='center'>Congratulations, $invName was successfully updated.</p>";
     redirect($message, 'products', '');
    } else {
      $message = "<p class='center bold error'>Error. The product could not be updated.</p>";
      redirect($message, 'products', 'updateProd');
    }
  break;

  default:
    $access = accessLevel('2');
    if($access == 1){
      $categoryList = showProductList();
      include '../view/prod-mgmt.php';
    } else {
      $message = '<h3 class="center bold error">Insufficient Permissions</h3>';
      redirect($message, '', '');
    }
}

?>