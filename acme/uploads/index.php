<?php

/*
UPLOADS CONTROLLER
*/

require_once '../library/connections.php';
require_once '../model/acme-model.php';
require_once '../model/products-model.php';
require_once '../model/reviews-model.php';
require_once '../model/uploads-model.php';
require_once '../library/functions.php';


// Create or access a Session
if(empty($_SESSION)) session_start(); 

/* * ****************************************************
* Variables for use with the Image Upload Functionality
* **************************************************** */
// directory name where uploaded images are stored
$image_dir = '/acme/uploads/images';
// The path is the full path from the server root
$image_dir_path = $_SERVER['DOCUMENT_ROOT'] . $image_dir;

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
if ($action == NULL) {
 $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
}


switch ($action) {
    case 'upload':
        // Store the incoming product id
        $invId = filter_input(INPUT_POST, 'invId', FILTER_VALIDATE_INT);
        // Store the name of the uploaded image
        $imgName = $_FILES['file1']['name'];
            
        $imageCheck = checkExistingImage($imgName);
            
        if($imageCheck){
            $message = '<p class="notice">An image by that name already exists.</p>';
        } elseif (empty($invId) || empty($imgName)) {
            $message = '<p class="notice">You must select a product and image file for the product.</p>';
        } else {
            // Upload the image, store the returned path to the file
            $imgPath = uploadFile('file1');
                
            // Insert the image information to the database, get the result
            $result = storeImages($imgPath, $invId, $imgName);
                
            // Set a message based on the insert result
            if ($result) {
                $message = '<p class="notice">The upload succeeded.</p>';
            } else {
                $message = '<p class="notice">Sorry, the upload failed.</p>';
            }
        }
            
        // Store message to session
        $_SESSION['message'] = $message;
            
        // Redirect to this controller for default action
        header('location: .');
    break;
    case 'delete':
        // Get the image name and id
        $filename = filter_input(INPUT_GET, 'filename', FILTER_SANITIZE_STRING);
        $imgId = filter_input(INPUT_GET, 'imgId', FILTER_VALIDATE_INT);
            
        // Build the full path to the image to be deleted
        $target = $image_dir_path . '/' . $filename;
            
        // Check that the file exists in that location
        if (file_exists($target)) {
            // Deletes the file in the folder
            $result = unlink($target); 
        }
            
        // Remove from database only if physical file deleted
        if ($result) {
            $remove = deleteImage($imgId);
        }
            
        // Set a message based on the delete result
        if ($remove) {
            $message = "<p class='notice'>$filename was successfully deleted.</p>";
        } else {
            $message = "<p class='notice'>$filename was NOT deleted.</p>";
        }
            
        // Store message to session
        $_SESSION['message'] = $message;
            
        // Redirect to this controller for default action
        header('location: .');
    break;
    case 'update':
        // Get the image name and id
        $actual_link = "$_SERVER[REQUEST_URI]";
        $filename = filter_input(INPUT_GET, 'filename', FILTER_SANITIZE_STRING);
        $invId = filter_input(INPUT_GET, 'invId', FILTER_VALIDATE_INT);
        $imgPath = filter_input(INPUT_GET, 'imgPath', FILTER_SANITIZE_STRING);
            
        // Build the full path to the image to be deleted
        $target = $image_dir_path . '/' . $filename;
            
        // Check that the file exists in that location
        if (!file_exists($target)) {
            // Deletes the file in the folder
            $message = "<p class='notice'>$filename does not exist in the local directory!.</p>";
            exit;
        }
            
        $change = changeImages($invId, $imgPath);

        // Set a message based on the delete result
        if ($change) {
            $message = "<p class='notice center'>$filename was successfully made the default image for this product.</p>";
        } else {
            $message = "<p class='notice center'>$filename could not be set as the default image.</p>";
        }
            
        $DataSet = getProductInfo($invId);

        $invName = $DataSet['invName'];
        $categoryId = $DataSet['categoryId'];
            
        $categories = getCategoryName($categoryId);

        foreach ($categories as $category){
            $categoryName = $category['categoryName'];
        }

        // Redirect to this controller for default action
        redirect($message, 'products', 'product&invName='.$invName.'&categoryName='.$categoryName);
    break;
    case 'defaultView':  
        // Redirect to this controller for default action
        redirect('', 'uploads', '');
    break;
    default:
        // Call function to return image info from database
        $imageArray = getImages();
            
        // Build the image information into HTML for display
        if (count($imageArray)) {
            $imageDisplay = buildImageDisplay($imageArray,"",1);
        } else {
            $imageDisplay = '<p class="notice">Sorry, no images could be found.</p>';
        }
            
        // Get inventory information from database
        $products = getProductBasics();
        // Build a select list of product information for the view
        $prodSelect = buildProductsSelect($products);
            
        include '../view/image-admin.php';
        exit;
}

?>