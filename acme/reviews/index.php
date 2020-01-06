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

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
if ($action == NULL) {
 $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
}


//Useful variables
$clientId = $_SESSION['clientData']['clientId'];
$returnTo = $_SESSION['returnTo'];
$ReviewInvId = $_SESSION['invId'];
$redirectVar = '&invName='.urlencode($_SESSION['invName']).'&invId='.$_SESSION['invId'];

switch ($action) {
    case 'reviewDelete':
        $screenName = filter_input(INPUT_POST, 'screenName', FILTER_SANITIZE_STRING);
        $reviewId = filter_input(INPUT_GET, 'reviewNumber', FILTER_VALIDATE_INT);

        $deleteResult = deleteReviews($reviewId);
        if ($deleteResult) {
            $message = "<p class='center success'>Congratulations', the review was successfully deleted.</p>";
            if($returnTo == "Admin"){
                $_SESSION['returnTo'] = "";
                redirect($message, 'accounts', 'checklogin');
            }else{
                redirect($message, 'products', 'product'.$redirectVar);
            }
            
        } else {
            $message = "<p class='center error'>Error: Review was not deleted.</p>";
            if($returnTo == "Admin"){
                $_SESSION['returnTo'] = "";
                redirect($message, 'accounts', 'checklogin');
            }else{
                redirect($message, 'products', 'product'.$redirectVar);
            }
        }
    break;
    case 'reviewEdit':
        // Filter and store the data
        $reviewId = filter_input(INPUT_GET, 'reviewNumber', FILTER_VALIDATE_INT);
        $_SESSION['reviewId'] = $reviewId;

        $prodReviews = getReviews($clientId);
        $i = 0;

        foreach($prodReviews as $review){
            $i = $i + 1;
            if($review['reviewId'] == $reviewId){
                $userReview = $review['reviewText'];
                $screenName = substr( $review['clientFirstname'], 0, 1 ).$review['clientLastname'];
            }
        }
        
        include '../view/review-modify.php';
    break;
    case 'reviewEdit-Submit':
        // Filter and store the data
        $userReview = filter_input(INPUT_POST, 'userReview');
        $reviewId = $_SESSION['reviewId'];
        $_SESSION['reviewId'] = ""; //Clear Variable
    
        if( empty($userReview) ){
            $message = '<h3 class="center bold error">Error: The review cannot be empty!</h3>';
            redirect($message, 'reviews', 'reviewEdit');
            exit; 
        }
    
        // Send the data to the model
        $regOutcome = updateReviews($userReview, $reviewId);
    
        // Check and report the result
        if($regOutcome === 1){
            $message = '<h3 class="center bold success">Review Modified Successfully!</h3>';
            if($returnTo == "Admin"){
                $_SESSION['returnTo'] = "";
                redirect($message, 'accounts', 'checklogin');
            }else{
                redirect($message, 'products', 'product'.$redirectVar);
            }
        } else {
            $message = '<h3 class="center bold error">Something went wrong, Please try again!</h3>';
            redirect($message, 'reviews', 'reviewEdit');
        }
    break;
    case 'reviewSubmit':  
        // Filter and store the data
        $screenName = filter_input(INPUT_POST, 'screenName', FILTER_SANITIZE_STRING);
        $userReview = filter_input(INPUT_POST, 'userReview', FILTER_SANITIZE_STRING);
    
        if( empty($userReview) ){
            $message = '<h3 class="center bold error">Please provide information for all empty review fields.</h3>';
            redirect($message, 'products', 'product'.$redirectVar);
            exit; 
        }

        // Send the data to the model
        $regOutcome = storeReviews($ReviewInvId, $clientId, $userReview);
    
        // Check and report the result
        if($regOutcome === 1){
            $message = '<h3 class="center bold">Review Added Successfully!</h3>';
            redirect($message, 'products', 'product'.$redirectVar);
        } else {
            $message = '<h3 class="center bold error">Something went wrong, Please try again!</h3>';
            redirect($message, 'products', 'product'.$redirectVar);
        }
    break;
    default:
        redirect($message, 'products', 'product'.$redirectVar);
    exit;
}

?>