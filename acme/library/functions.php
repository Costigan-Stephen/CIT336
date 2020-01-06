<?PHP

//
//  START ACCOUNT FUNCTIONS
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////

  function CheckForLoggedIn(){
    if(isset($_SESSION['loggedin'])){
      if(page_title('products') == 1){
        $access = accessLevel(2);

        if($access != 1){
          $message = '<h3 class="center bold error">Insufficient Permissions</h3>';
          redirect($message, 'accounts', 'checklogin');
          exit;
        }
      }

      if(!$_SESSION['loggedin']){
          $message = '<h3 class="center bold error">User Verification Failed, Please Log Back In to Continue</h3>';
          setcookie( 'message', $message, time(3600), '/');
          header( 'Location:/acme/accounts/index.php?action=login');
          exit;
      }
    }
  }

  function accessLevel($level){
    if(empty($_SESSION['clientData']['clientLevel'])){
      return 0;
    }

    if($_SESSION['clientData']['clientLevel'] >= $level){
      return 1;
    } else {
      return 0;
    }
  }
  
  function deleteCookie($cookie){
    setcookie( $cookie, "", -1, '/'); 
  }

// /////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  END ACCOUNT FUNCTIONS
//






//
//  START PRODUCT FUNCTIONS
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////

  //Check if category/product exists in database
  function searchForField($colname, $table="inventory", $field, $col2=NULL, $result=0){
  
    if (empty($col2) || $col2 == NULL){
      $col2 = $colname;
    }
  
      $fieldParse = '\''.$field.'\'';
      $sql = 'SELECT '. $colname .' FROM '.$table.' WHERE '.$col2.' = '. $fieldParse .';';
      $fieldResult = '';
  
      $stmt = sqlConnection($sql);

      $array = array_values($stmt);
      if (empty($stmt) || $stmt = NULL) {
        $fieldResult = '0';
      } else {
        $fieldResult = $array[0][$colname];
      }
      return $fieldResult;
  }

  function showProductList(){
    $categories = getCategories();
    $categoryList = buildCategoryList($categories);
    return $categoryList;
  }

  function buildCategoryList($categories){ 
    $catList = '<select name="categoryId" id="categoryList">'; 
    $catList .= "<option>Choose a Category</option>"; 
    foreach ($categories as $category) { 
      $catList .= "<option value='$category[categoryID]'>$category[categoryName]</option>"; 
    } 

    $catList .= '</select>'; 
    return $catList; 
  }

  function buildProductsDisplay($products){
    $pd = '<ul id="prod-display">';

    foreach ($products as $product) {
      $price = formatNumber($product['invPrice']);
      $pd .= '<li><a href="/acme/products/index.php?action=product&invName='.urlencode($product['invName']).'">';
      $pd .= "<img class='stretchFull imageScale' src='$product[invThumbnail]' alt='Image of $product[invName] on Acme.com'>";
      $pd .= '<hr>';
      $pd .= "<h2>$product[invName]</h2>";
      $pd .= "<span class='price'>$price</span>";
      $pd .= '</a></li>';
    }

    $pd .= '</ul>';
    return $pd;
  }

  function buildProductView($products){
    $pd = '<div id="item-display" class="showGrid">';

    foreach ($products as $product) {
      $price = formatNumber($product['invPrice']);
      
      $pd .= "<img class='area1 stretchFull imageScale' src='$product[invImage]' alt='Image of $product[invName] on Acme.com'>";
      $pd .= "<div class='area2 padding'>";

        $pd .= "<h1>$product[invName]</h1>";
        $pd .= "<span class='bold larger'>Price: $$price</span><br><br>";
        $pd .= displayData(-1, "Description:", $product['invDescription'], 1, -2)."<br><br>";
        $pd .= displayData(-1, "Item Size:", formatNumber($product['invSize'],0) ,1, -1)."<br>";
        $pd .= displayData(-1, "Weight:", formatNumber($product['invWeight'],0) ,1)."<br>";
        $pd .= displayData(-1, "In Stock:", formatNumber($product['invStock'],0) ,1)."<br>";
        $pd .= displayData(-1, "Vendor:", $product['invVendor'],1)."<br>";
        $pd .= displayData(-1, "Location:", $product['invLocation'],1, -1)."<br>";
        $pd .= displayData(-1, "Material:", $product['invStyle'],1, -1)."<br>";

      $pd .= '</div>';

      $_SESSION['invId'] = $product['invId'];
      $_SESSION['invName'] = $product['invName'];
    }

    $pd .= '</div>';
    return $pd;
  }

// /////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  END PRODUCT FUNCTIONS
//






//
//  START REVIEW FUNCTIONS
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////

  function buildReviewsDisplay($reviews, $user="",$productName=""){

    $pd = '';

    foreach ($reviews as $review) {
      $screenName = substr( $review['clientFirstname'], 0, 1 ).$review['clientLastname'];
      $date = date_format(date_create($review['reviewDate']),"d F, Y");

        if($productName){
          $pd .= '<div class="review-display margins">';
          $pd .= "<div class='reviewHead stretchFull'>";
            $pd .= "<span class='bold larger'>"."$review[invName]"."</span>  written on ";
            $pd .= $date.":"; 
        } else {
          $pd .= '<div class="review-display margins">';
          $pd .= "<div class='reviewHead stretchFull'>";
            $pd .= "<span class='bold larger'>"."$screenName"."</span>  wrote on ";
            $pd .= $date.":";
        }
        if(isset($_SESSION['loggedin']) && isset($_SESSION['clientData'])){
          if($_SESSION['clientData']['clientId'] == $review['clientId']){
            $pd .= '<span class="right stretchFull">';
              
              if($productName) { //ADMIN VIEW
                $_SESSION['returnTo'] = "Admin";
                $pd .= add_spaces(4).'<a href="/acme/reviews/index.php?action=reviewEdit&reviewNumber='.$review['reviewId'].'">Modify</a>'.add_spaces(1);
                $pd .= '<a href="/acme/reviews/index.php?action=reviewDelete&reviewNumber='.$review['reviewId'].'">Delete</a>';
              }else{ //PRODUCT VIEW
                $_SESSION['returnTo'] = "";
                $pd .= add_spaces(4).'<a href="/acme/reviews/index.php?action=reviewEdit&reviewNumber='.$review['reviewId'].'">Modify</a>'.add_spaces(1);
                $pd .= '<a href="/acme/reviews/index.php?action=reviewDelete&reviewNumber='.$review['reviewId'].'">Delete</a>';
              }
            $pd .= '</span>';
          }
        }
        $pd .= "</div>";

        $pd .= "<div class='reviewBody padding stretchFull'>";
          $pd .= $review['reviewText'];
        $pd .= '</div>';
      $pd .= '</div>';
    }
    return $pd;
  }
  

// /////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  END REVIEW FUNCTIONS
//






//
//  START UPLOAD FUNCTIONS
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Adds "-tn" designation to file name
function makeThumbnailName($image) {
  $i = strrpos($image, '.');
  $image_name = substr($image, 0, $i);
  $ext = substr($image, $i);
  $image = $image_name . '-tn' . $ext;
  return $image;
}

// Build images display for image management view
function buildImageDisplay($imageArray, $class="",$prod="") {
  if ($class) { $class.= " class='".$class."'"; }

  $id = '<ul id="image-display"'.$class.'>';
  foreach ($imageArray as $image) {
      // if(substr_count ($image['imgName'], "-tn") || $prod){
        $id .= '<li>';
        $id .= "<img class='stretchFull imageScale' src='$image[imgPath]' title='$image[invName] image on Acme.com' alt='$image[invName] image on Acme.com'>";

        $access = accessLevel(2);

        //Change permissions of access
        if($access > 0){
          $id .= "<p><a href='/acme/uploads?action=delete&imgId=$image[imgId]&filename=$image[imgName]' title='Delete the image'>Delete $image[imgName]</a></p>";
          if(!substr_count ($image['imgName'], "-tn") || $prod){
            $id .= "<p><a href='/acme/uploads?action=update&imgId=$image[imgId]&invId=$image[invId]&filename=$image[imgName]&imgPath=$image[imgPath]' title='Update the image'>Make $image[imgName] default</a></p>";
          }
          
        }
        $id .= '</li>';
      // }
    }
  $id .= '</ul>';
  return $id;
}

// Build the products select list
function buildProductsSelect($products) {
  $prodList = '<select name="invId" id="invId" class="dropdown stretchFull">';
  $prodList .= "<option>Choose a Product</option>";
  foreach ($products as $product) {
    $prodList .= "<option value='$product[invId]'>$product[invName]</option>";
  }
  $prodList .= '</select>';
  return $prodList;
}

// Handles the file upload process and returns the path
// The file path is stored into the database
function uploadFile($name) {
  // Gets the paths, full and local directory
  global $image_dir, $image_dir_path;
  if (isset($_FILES[$name])) {
    // Gets the actual file name
    $filename = $_FILES[$name]['name'];
    if (empty($filename)) {
      return;
    }
    // Get the file from the temp folder on the server
    $source = $_FILES[$name]['tmp_name'];
    // Sets the new path - images folder in this directory
    $target = $image_dir_path . '/' . $filename;
    // Moves the file to the target folder
    move_uploaded_file($source, $target);
    // Send file for further processing
    processImage($image_dir_path, $filename);
    // Sets the path for the image for Database storage
    $filepath = $image_dir . '/' . $filename;
    // Returns the path where the file is stored
    return $filepath;
  }
}

// Processes images by getting paths and 
// creating smaller versions of the image
function processImage($dir, $filename) {
  // Set up the variables
  $dir = $dir . '/';
 
  // Set up the image path
  $image_path = $dir . $filename;
 
  // Set up the thumbnail image path
  $image_path_tn = $dir.makeThumbnailName($filename);
 
  // Create a thumbnail image that's a maximum of 200 pixels square
  resizeImage($image_path, $image_path_tn, 200, 200);
 
  // Resize original to a maximum of 500 pixels square
  resizeImage($image_path, $image_path, 500, 500);
}

function resizeImage($old_image_path, $new_image_path, $max_width, $max_height) {
     
  // Get image type
  $image_info = getimagesize($old_image_path);
  $image_type = $image_info[2];
 
  // Set up the function names
  switch ($image_type) {
  case IMAGETYPE_JPEG:
    $image_from_file = 'imagecreatefromjpeg';
    $image_to_file = 'imagejpeg';
  break;
  case IMAGETYPE_GIF:
    $image_from_file = 'imagecreatefromgif';
    $image_to_file = 'imagegif';
  break;
  case IMAGETYPE_PNG:
    $image_from_file = 'imagecreatefrompng';
    $image_to_file = 'imagepng';
  break;
  default:
  return;
 } // ends the resizeImage function
 
  // Get the old image and its height and width
  $old_image = $image_from_file($old_image_path);
  $old_width = imagesx($old_image);
  $old_height = imagesy($old_image);
 
  // Calculate height and width ratios
  $width_ratio = $old_width / $max_width;
  $height_ratio = $old_height / $max_height;
 
  // If image is larger than specified ratio, create the new image
  if ($width_ratio > 1 || $height_ratio > 1) {
 
    // Calculate height and width for the new image
    $ratio = max($width_ratio, $height_ratio);
    $new_height = round($old_height / $ratio);
    $new_width = round($old_width / $ratio);
  
    // Create the new image
    $new_image = imagecreatetruecolor($new_width, $new_height);
  
    // Set transparency according to image type
    if ($image_type == IMAGETYPE_GIF) {
      $alpha = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
      imagecolortransparent($new_image, $alpha);
    }
  
    if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_GIF) {
      imagealphablending($new_image, false);
      imagesavealpha($new_image, true);
    }
  
    // Copy old image to new image - this resizes the image
    $new_x = 0;
    $new_y = 0;
    $old_x = 0;
    $old_y = 0;
    imagecopyresampled($new_image, $old_image, $new_x, $new_y, $old_x, $old_y, $new_width, $new_height, $old_width, $old_height);
  
    // Write the new image to a new file
    $image_to_file($new_image, $new_image_path);
    // Free any memory associated with the new image
    imagedestroy($new_image);
  } else {
    // Write the old image to a new file
    $image_to_file($old_image, $new_image_path);
  }
    // Free any memory associated with the old image
  imagedestroy($old_image);
} 

// /////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  END UPLOAD FUNCTIONS
//




//
//  START MISCELANEOUS FUNCTIONS
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getMessage($message){
  if ( isset($message) && $message != "" ) { 
      echo $message; 
  } else if (isset($_COOKIE['message'])) { 
      $message = filter_input(INPUT_COOKIE, 'message'); 
      setcookie( 'message', "", -1, '/');
      echo $message; 
  }
  unset($_SESSION['message']);   
}

function navBar($page){
  // Build a navigation bar using the $categories array
  // Get the array of categories
  $categories = getCategories();

  // // var_dump($categories);
  // postVariable($categories);
  $navList = '<ul>';
  $navList .= "<li><a href='/acme/index.php' title='View the Acme home page' class='";
  if($page=="Home"){
    $navList .= 'active';
  }
  $navList .= "'>Home</a></li>";

  foreach ($categories as $category) {
    $navList .= "<li><a href='/acme/products/index.php?action=category&categoryName=".urlencode($category['categoryName'])."' class='";
    if($page == $category['categoryName']){
      $navList .= 'active';
    }
    $navList .= "' title='View our $category[categoryName] product line'>$category[categoryName]</a></li>";
  }
  $navList .= '</ul>';
  return $navList;
}

// ALL NAVIGATION TO BASE INDEX FILES WITH APPROPRIATE ACTIONS
function redirect($message='', $location='', $action=''){

  if(!empty($action))   {$action    = "?action=".$action;}
  if(!empty($location)) {$location  = "/".$location;}
  if(!empty($message))  {setcookie( 'message', $message, time(3600), '/');}

  header( 'Location: /acme'.$location.'/index.php'.$action );
}

//Take variables and return button for general use.  Optional additional class
function buttonGenerate ($location, $action, $buttonText, $classAdd=""){
  if (!empty($classAdd)){$classAdd = ' '.$classAdd;}

  echo '<form class="textblock margins" style="padding: 0; margin-top: 0; margin-bottom: 0;" action="/acme/'.$location.'/index.php">
          <input type="submit" value="'.$buttonText.'" class="button full'.$classAdd.'">
          <input type="hidden" name="action" value="'.$action.'">
        </form>';
}

function page_title($location) {
  $uri = $_SERVER['REQUEST_URI'];
  // $url = filter_input(INPUT_POST, $uri, FILTER_SANITIZE_STRING);
  $check = strpos("/{$uri}/", $location);
  if( $check !== false ) {
    $result = True;
  } else {
    $result=False;
  }
  // echo $url; // Outputs: URI

  return $result;
}

function add_spaces($x){
  $spaces = "";
  for($i=0; $i <= $x; $i++){ 
    $spaces .= "&nbsp;"; 
  }
  return $spaces;
}

function displayData($spaces, $customField, $sessionData,$other="",$adjustment=0){
  
  if($customField){
    $length = strlen($customField);
    $gap = add_spaces(20 - $length + $adjustment);
    $customField = "<span class='bold'>" . $customField . "</span>";
  } else {
    $gap = "";
  }
  if(!$other){
    $session = $_SESSION['clientData'][$sessionData];
    echo add_spaces($spaces) . $customField . $gap . $session;
  } else {
    if(!$sessionData){
      $session = $_SESSION['clientData'][$sessionData]; 
    } else { 
      $session = $sessionData; 
    }
    $return = add_spaces($spaces) . $customField . $gap . $session;
    return $return;
  }
  // $cookie = htmlspecialchars($_COOKIE[$cookieData]);
  
}

function formatNumber($item,$dec=2){
  return number_format ( $item , $dec , "." , "," );
}


// /////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  END MISCELANEOUS FUNCTIONS
//






//
//   REMOVED FUNCTIONS (KEPT FOR REFERENCE)
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////

  // function parseData(){
  //   // Filter and store the data
  //   $clientFirstname = filter_input(INPUT_POST, 'clientFirstname');
  //   $clientLastname = filter_input(INPUT_POST, 'clientLastname');
  //   $clientEmail = filter_input(INPUT_POST, 'clientEmail');
  //   $clientPassword = filter_input(INPUT_POST, 'clientPassword');
  //   $clientEmail = checkEmail($clientEmail);
  //   $checkPassword = checkPassword($clientPassword);
  //   if(empty($clientFirstname) || empty($clientLastname) || empty($clientEmail) || empty($checkPassword)){
  //       $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Please provide information for all empty form fields.</h3>';
  //       // include '../view/register.php';
  //       redirect($message, 'accounts', 'register');
  //       exit; 
  //   }
  //   $hashedPassword = password_hash($clientPassword, PASSWORD_DEFAULT);
  //   //Check if email exists in Database
  //   $emailCheck = validate("clientEmail", $clientEmail);
  //   //User has already signed up!
  //   if($emailCheck){
  //       if ($clientFirstname){$message = '<h3 style="text-align:center; font-weight: bold; color: red;">Sorry, '.$clientFirstname.', that email is already registered. Do you want to login instead? </h3>';}
  //       else { $message = '<h3 style="text-align:center; font-weight: bold; color: red;">That email is already registered. Do you want to login instead? </h3>'; }     
  //       // include '../view/login.php';
  //       redirect($message, 'accounts', 'login');
  //       exit;
  //   }
  //   // Send the data to the model
  //   $regOutcome = regClient($clientFirstname, $clientLastname, $clientEmail, $hashedPassword);
  //   // Check and report the result
  //   if($regOutcome === 1){
  //       setcookie( 'firstname', $clientFirstname, strtotime('+1 year'), '/');
  //       $message = '<h3 style="text-align:center; font-weight: bold;">Thanks for registering, '.$clientFirstname.'. Please use your email and password to login.</h3>';
  //       // include '../view/login.php';
  //       redirect($message, 'accounts', 'login');
  //       exit;
  //   } else {
  //       $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Sorry, '.$clientFirstname.', but the registration failed. Please try again.</h3>';
  //       // include '../view/register.php';
  //       redirect($message, 'accounts', 'register');
  //       exit;
  //   }
  // }

  //Check to see if value exists in SQL database
  // function validate($colname, $field){
  //   if (empty($field)){
  //       if ($colname == "clientEmail"){ echo "Please enter a valid email"; } 
  //       else { echo "Please enter a valid password"; }
  //       return $fieldResult = "";
  //   }
  //   $fieldQuote = "'".$field."'";
  //   $colQuote = "`".$colname."`";
  //   if ( $colname == "clientEmail" ){ 
  //       $search = " LIKE "; 
  //   } else { 
  //       $search = " = "; 
  //   }
  //   $sql = 'SELECT '. $colQuote .' FROM `clients` WHERE '. $colname . $search . $fieldQuote .';';
  //   $return = sqlConnection($sql);
  //   if (!empty($return)) {
  //       $fieldResult = 1;
  //   } else {
  //       $fieldResult = '';
  //   }
  //   // $fieldResult = $someArray[0][$colname];
  //   return $fieldResult;
  // }

  // function checkEmail($clientEmail){
  //      $valEmail = filter_var($clientEmail, FILTER_VALIDATE_EMAIL);
  //      return $valEmail;
  // }

  // function checkPassword($clientPassword){
  //   $pattern = '/^(?=.*[[:digit:]])(?=.*[[:punct:]])(?=.*[A-Z])(?=.*[a-z])([^\s]){8,}$/';
  //   return preg_match($pattern, $clientPassword);
  // }

  // function loginCheck(){
  //   $clientEmail = filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_EMAIL);
  //   $clientEmail = checkEmail($clientEmail);
  //   $clientPassword = filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_STRING);
  //   if(empty($clientEmail) || empty($clientPassword)){
  //       // echo "Email/password does not exist!";
  //       $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Invalid Email and Password combination</h3>';
  //       // include '../view/login.php';
  //       redirect($message, 'accounts', 'login');
  //       exit;
  //   }
  //   validateLogin($clientEmail, $clientPassword);
  //   exit; 
  // }

  // function accountUpdate(){
  //   $clientFirstname = filter_input(INPUT_POST, 'clientFirstname');
  //   $clientLastname = filter_input(INPUT_POST, 'clientLastname');
  //   $clientEmail = filter_input(INPUT_POST, 'clientEmail');   
  //   if (empty($clientFirstname) || empty($clientLastname) || empty($clientEmail) ) {
  //    $message = '<p class="center">Please Enter all updated information!.</p>';
  //    include '../view/client-update.php';
  //    exit;
  //   }
  //   //Check to see if email is different, if it is, see if it already exists
  //   if($_SESSION['clientData']['clientEmail'] != $clientEmail){
  //   $emailCheck = checkExistingEmail($clientEmail);
  //     if($emailCheck > 0){
  //       $message = '<p class="center">That email address belongs to another account!</p>';
  //       include '../view/client-update.php';
  //       exit;
  //     }
  //   }
  //   $updateResult = updateAccount($clientFirstname, $clientLastname, $clientEmail);
  //   if ($updateResult) {
  //     //Update session information
  //     $clientData = getClient($clientEmail);
  //     array_pop($clientData);
  //     $_SESSION['clientData'] = $clientData;
  //    $message = "<p class='center'>Congratulations, your account was successfully updated.</p>";
  //    redirect($message, 'accounts', 'checklogin');
  //    exit;
  //   } else {
  //     $message = "<p class='center'>Error. could not update your account!</p>";
  //     redirect($message, 'accounts', 'update');
  //     exit;
  //   }
  // }


  // function accountPassword(){
  //   $clientPassword = filter_input(INPUT_POST, 'clientPassword');
  //   $clientPasswordNew = filter_input(INPUT_POST, 'clientPasswordNew');
  //   $clientPasswordVerify = filter_input(INPUT_POST, 'clientPasswordVerify');
  //   //Check if password is right
  //   $verifyPassword = passwordCheck($clientPassword);
  //   //Make sure password is correct format
  //   $checkPassword = checkPassword($clientPasswordNew);
  //   if($verifyPassword < 1){
  //     $message = "<p class='center'>The Password was entered incorrectly!</p>";
  //     include '../view/pass-update.php';
  //     exit; 
  //   }
  //   //Check to see if new passwords match
  //   if ($clientPasswordNew != $clientPasswordVerify){
  //     $message = "<p class='center'>The passwords do not match!</p>";
  //     include '../view/pass-update.php';
  //     exit;
  //   }
  //   //Check to see if new passwords match
  //   if (empty($checkPassword)){
  //     $message = "<p class='center'>Your new password must meet the requirements!</p>";
  //     include '../view/pass-update.php';
  //     exit;
  //   }
  //   //Make sure all fields are not empty
  //   if (empty($clientPassword) || empty($clientPasswordNew) || empty($clientPasswordVerify) ) {
  //    $message = "<p class='center'>Please Enter all updated information!.</p>";
  //    include '../view/pass-update.php';
  //    exit;
  //   }
  //   $newPassword = password_hash($clientPasswordNew, PASSWORD_DEFAULT);
  //   $updateResult = updatePassword($newPassword);
  //   if ($updateResult) {
  //     $message = "<p class='center'>Congratulations, your password was successfully updated.</p>";
  //     redirect($message, 'accounts', 'checklogin');
  //     exit;
  //   } else {
  //     $message = "<p class='center'>Error. could not update your password!</p>";
  //     redirect($message, 'accounts', 'password');
  //     exit;
  //   }
  // }


  // function testProduct(){
  //   // Filter and store the data
  //   $invCategory = filter_input(INPUT_POST, 'invCategory');
  //   $invName = filter_input(INPUT_POST, 'invName');
  //   $invDescription = filter_input(INPUT_POST, 'invDescription');
  //   $invImage = filter_input(INPUT_POST, 'invImage');
  //   $invThumbnail = filter_input(INPUT_POST, 'invThumbnail');
  //   $invPrice = filter_input(INPUT_POST, 'invPrice');
  //   $invStock = filter_input(INPUT_POST, 'invStock');
  //   $invSize = filter_input(INPUT_POST, 'invSize');
  //   $invWeight = filter_input(INPUT_POST, 'invWeight');
  //   $invLocation = filter_input(INPUT_POST, 'invLocation');
  //   $invVendor = filter_input(INPUT_POST, 'invVendor');
  //   $invStyle = filter_input(INPUT_POST, 'invStyle');
  //   if ($invCategory == "-"){
  //     $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Please select a Category!</h3>';
  //       redirect($message, 'products', 'newProd');
  //       exit; 
  //   }
  //   if(empty($invCategory) || empty($invName) || empty($invDescription) || empty($invImage) || empty($invThumbnail) || empty($invPrice) || empty($invStock) || empty($invSize) || empty($invWeight) || empty($invLocation) || empty($invVendor) || empty($invStyle)){
  //       $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Please provide information for all empty form fields.</h3>';
  //       redirect($message, 'products', 'newProd');
  //       exit; 
  //   }
  //   //GET Cat ID
  //   $CategoryId = searchForField('categoryId', 'categories', $invCategory, 'categoryName', 1);
  //   if(empty($CategoryId)) {
  //     $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Category Error.</h3>';
  //     redirect($message, 'products', 'newProd');
  //     exit; 
  //   }
  //   // Send the data to the model
  //   $regOutcome = regProduct($CategoryId, $invName, $invDescription, $invImage, $invThumbnail, $invPrice, $invStock, $invSize, $invWeight, $invLocation, $invVendor, $invStyle);
  //   // Check and report the result
  //   if($regOutcome === 1){
  //       $message = '<h3 style="text-align:center; font-weight: bold;">Item Added Successfully!</h3>';
  //       redirect($message, 'products', 'prodMgmt');
  //       exit;
  //   } else {
  //       $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Something went wrong, Please try again!</h3>';
  //       redirect($message, 'products', 'newCat');
  //       exit;
  //   }
  // }
    

  //SUBMIT!
  // function testCategory(){ 
  //   $categoryName = filter_input(INPUT_POST, 'categoryName');
  //   if(empty($categoryName)){
  //     $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Please enter a category name!.</h3>';
  //       redirect($message, 'products', 'newCat');
  //       exit; 
  //   }
  //   $exists = searchForField("categoryName", "categories", $categoryName);
  //   if(empty($exists)){
  //     regCat($categoryName);
  //     $message = '<h3 style="text-align:center; font-weight: bold; color: green;">Category Added Successfully</h3>';
  //     redirect($message, 'products', 'prodMgmt');
  //   } else {
  //     $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Category already exists</h3>';
  //     redirect($message, 'products', 'newCat');
  //     exit; 
  //   }  
  // }


  // function productUpdate(){
  //   $invCategory = filter_input(INPUT_POST, 'invCategory', FILTER_SANITIZE_NUMBER_INT);
  //   $invName = filter_input(INPUT_POST, 'invName', FILTER_SANITIZE_STRING);
  //   $invDescription = filter_input(INPUT_POST, 'invDescription', FILTER_SANITIZE_STRING);
  //   $invImage = filter_input(INPUT_POST, 'invImage', FILTER_SANITIZE_STRING);
  //   $invThumbnail = filter_input(INPUT_POST, 'invThumbnail', FILTER_SANITIZE_STRING);
  //   $invPrice = filter_input(INPUT_POST, 'invPrice', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  //   $invStock = filter_input(INPUT_POST, 'invStock', FILTER_SANITIZE_NUMBER_INT);
  //   $invSize = filter_input(INPUT_POST, 'invSize', FILTER_SANITIZE_NUMBER_INT);
  //   $invWeight = filter_input(INPUT_POST, 'invWeight', FILTER_SANITIZE_NUMBER_INT);
  //   $invLocation = filter_input(INPUT_POST, 'invLocation', FILTER_SANITIZE_STRING);
  //   $invVendor = filter_input(INPUT_POST, 'invVendor', FILTER_SANITIZE_STRING);
  //   $invStyle = filter_input(INPUT_POST, 'invStyle', FILTER_SANITIZE_STRING);
  //   $invId = filter_input(INPUT_POST, 'invId', FILTER_SANITIZE_NUMBER_INT);
  //   if (empty($invCategory) || empty($invName) || empty($invDescription) 
  //   || empty($invImage) || empty($invThumbnail) || empty($invPrice) 
  //   || empty($invStock) || empty($invSize) || empty($invWeight) 
  //   || empty($invLocation) || empty($invVendor) || empty($invStyle)) {
  //    $message = '<p class="center">Please complete all information for the new item! Double check the category of the item.</p>';
  //    include '../view/prod-update.php';
  //    exit;
  //   }
  //   $updateResult = updateProduct($invCategory, $invName, $invDescription, 
  //    $invImage, $invThumbnail, $invPrice, $invStock, $invSize, $invWeight, 
  //    $invLocation, $invVendor, $invStyle, $invId);
  //   if ($updateResult) {
  //    $message = "<p class='center'>Congratulations, $invName was successfully updated.</p>";
  //    redirect($message, 'products', '');
  //   //  include '../view/new-prod.php';
  //    exit;
  //   } else {
  //     $message = "<p class='center'>Error. The product could not be updated.</p>";
  //     redirect($message, 'products', 'updateProduct');
  //     // include '../view/new-prod.php';
  //     exit;
  //   }
  // }


  // function updateProduct($catType, $invName, $invDescription, $invImage, $invThumbnail, $invPrice, $invStock, $invSize, $invWeight, $invLocation, $invVendor, $invStyle, $invId){
  //   $db = acmeConnect();
  //   // The SQL statement to be used with the database
  //   $sql = 'UPDATE inventory SET invName = :invName, 
  //     invDescription = :invDescription, invImage = :invImage, 
  //     invThumbnail = :invThumbnail, invPrice = :invPrice, 
  //     invStock = :invStock, invSize = :invSize, 
  //     invWeight = :invWeight, invLocation = :invLocation, 
  //     categoryId = :catType, invVendor = :invVendor, 
  //     invStyle = :invStyle WHERE invId = :invId';
  //   $stmt = $db->prepare($sql);
  //   $stmt->bindValue(':catType', $catType, PDO::PARAM_INT);
  //   $stmt->bindValue(':invName', $invName, PDO::PARAM_STR);
  //   $stmt->bindValue(':invDescription', $invDescription, PDO::PARAM_STR);
  //   $stmt->bindValue(':invImage', $invImage, PDO::PARAM_STR);
  //   $stmt->bindValue(':invThumbnail', $invThumbnail, PDO::PARAM_STR);
  //   $stmt->bindValue(':invPrice', $invPrice, PDO::PARAM_STR);
  //   $stmt->bindValue(':invStock', $invStock, PDO::PARAM_INT);
  //   $stmt->bindValue(':invSize', $invSize, PDO::PARAM_INT);
  //   $stmt->bindValue(':invWeight', $invWeight, PDO::PARAM_INT);
  //   $stmt->bindValue(':invLocation', $invLocation, PDO::PARAM_STR);
  //   $stmt->bindValue(':invVendor', $invVendor, PDO::PARAM_STR);
  //   $stmt->bindValue(':invStyle', $invStyle, PDO::PARAM_STR);
  //   $stmt->bindValue(':invId', $invId, PDO::PARAM_INT);
  //   $stmt->execute();
  //   $rowsChanged = $stmt->rowCount();
  //   $stmt->closeCursor();
  //   return $rowsChanged;
  // }


  // function validateLogin ($clientEmail, $clientPassword){
  //   // A valid password exists, proceed with the login process
  //   // Query the client data based on the email address
  //   $clientData = getClient($clientEmail);
  //   $hashCheck = password_verify($clientPassword, $clientData['clientPassword']);
  //   // If the hashes don't match create an error
  //   // and return to the login view
  //   if (!$hashCheck) {
  //       $message = '<p class="notice">Please check your password and try again.</p>';
  //       redirect($message, 'accounts', 'login');
  //       exit; 
  //   }
  //   // A valid user exists, log them in
  //   $_SESSION['loggedin'] = TRUE;
  //   // Remove the password from the array
  //   // the array_pop function removes the last
  //   // element from an array
  //   array_pop($clientData);
  //   // Store the array into the session
  //   $_SESSION['clientData'] = $clientData;
    
  //   // setcookie( 'clientFirstname', $clientData['clientFirstname'], strtotime('+1 day'), '/');
  //   // setcookie( 'clientLastname', $clientData['clientLastname'], strtotime('+1 day'), '/');
  //   // setcookie( 'clientEmail', $clientData['clientEmail'], strtotime('+1 day'), '/');
  //   // Send them to the admin view
  //   $message = '<h3 style="text-align:center; font-weight: bold; color: green;">Login was Successful '.$clientData['clientFirstname'].'</h3>';
  //   redirect($message, 'accounts', 'checklogin');
  // }


  // function passwordCheck($clientPassword){
  //     $clientEmail = $_SESSION['clientData']['clientEmail'];
  //     $clientData = getClient($clientEmail);
  //     $hashCheck = password_verify($clientPassword, $clientData['clientPassword']);

  //     if (!$hashCheck) {
  //       $message = '<p class="notice">Please check your password and try again.</p>';
  //       return 0; 
  //     } else {
  //       return 1;
  //     }
  //     exit; 
  // }


  // function productList(){

  //   // Get the array of categories
  //   $categories = getCategories();
  //   $invId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
  //   $prodInfo = getProductInfo($invId);
  //   $return = '';
  //   foreach ($categories as $category) {
  //     $return .= "<option value='" . strtolower($category['categoryName']);
  //     if (isset($invCategory)) {
  //       if ($category['categoryId'] === $prodInfo) {
  //         $return .= ' selected ';
  //       }
  //     } elseif (isset($prodInfo['categoryId'])) {
  //       if ($category['categoryId'] === $prodInfo['categoryId']) {
  //         $return .= ' selected ';
  //       }
  //     }
  //     $return .= "'>" . $category['categoryName'] . "</option>";
  //   }
  //   return $return;
  // }


?>