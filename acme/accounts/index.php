
<?php
/*
ACCOUNT CONTROLLER
*/

// Create or access a Session
if(empty($_SESSION)) session_start(); 


require_once '../library/connections.php';
require_once '../model/acme-model.php';
require_once '../model/accounts-model.php';
require_once '../model/reviews-model.php';
require_once '../library/functions.php';


$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
if ($action == NULL) {
 $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
}


switch ($action) {
  case 'accountupdate':
    $clientFirstname = filter_input(INPUT_POST, 'clientFirstname');
    $clientLastname = filter_input(INPUT_POST, 'clientLastname');
    $clientEmail = filter_input(INPUT_POST, 'clientEmail');
      
    if (empty($clientFirstname) || empty($clientLastname) || empty($clientEmail) ) {
     $message = '<p class="center">Please Enter all updated information!.</p>';
     include '../view/client-update.php';
     exit;
    }

    //Check to see if email is different, if it is, see if it already exists
    if($_SESSION['clientData']['clientEmail'] != $clientEmail){

    $emailCheck = checkExistingEmail($clientEmail);
      if($emailCheck > 0){
        $message = '<p class="center">That email address belongs to another account!</p>';
        include '../view/client-update.php';
        exit;
      }
    }

    $updateResult = updateAccount($clientFirstname, $clientLastname, $clientEmail);
    if ($updateResult) {
      //Update session information
      $clientData = getClient($clientEmail);
      array_pop($clientData);
      $_SESSION['clientData'] = $clientData;

      $message = "<p class='center'>Congratulations, your account was successfully updated.</p>";
      redirect($message, 'accounts', 'checklogin');
    } else {
      $message = "<p class='center bold error'>Error. could not update your account!</p>";
      redirect($message, 'accounts', 'update');
    }
  break;

  case 'checklogin':
    if(isset($_SESSION['loggedin'])){
      $prodReviews = getReviews();
      $i = 0;

      foreach($prodReviews as $review){
        $i = $i + 1;
        if($review['clientId'] == $_SESSION['clientData']['clientId']){ 
          $filterReviews[$i] = $review;
        }
        $invName = $review['invName'];
      }

      

      if( !empty($filterReviews) ){$reviews = (buildReviewsDisplay($filterReviews,$_SESSION['clientData']['clientId'],$invName));} 
      include '../view/admin.php';
    } else {
      include '../view/login.php';
    }
  break;

  case 'login':
    include '../view/login.php';
  break;

  case 'loginsubmit':
    $clientEmail = filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_EMAIL);
    $clientEmail = filter_var($clientEmail, FILTER_VALIDATE_EMAIL);
    $clientPassword = filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_STRING);

    if(empty($clientEmail) || empty($clientPassword)){
        // echo "Email/password does not exist!";
        $message = '<h3 class="center bold error">Invalid Email and Password combination</h3>';
        // include '../view/login.php';
        redirect($message, 'accounts', 'login');
        exit;
    }
    
    // A valid password exists, proceed with the login process
    // Query the client data based on the email address
    $clientData = getClient($clientEmail);
    $hashCheck = password_verify($clientPassword, $clientData['clientPassword']);
    // If the hashes don't match create an error
    // and return to the login view
    if (!$hashCheck) {
        $message = '<p class="notice">Please check your password and try again.</p>';
        redirect($message, 'accounts', 'login');
        exit; 
    }
    // A valid user exists, log them in
    $_SESSION['loggedin'] = TRUE;
    // Remove the password from the array
    // the array_pop function removes the last
    // element from an array
    array_pop($clientData);
    // Store the array into the session
    $_SESSION['clientData'] = $clientData;  
    deleteCookie('firstname');
    
    // setcookie( 'clientFirstname', $clientData['clientFirstname'], strtotime('+1 day'), '/');
    // setcookie( 'clientLastname', $clientData['clientLastname'], strtotime('+1 day'), '/');
    // setcookie( 'clientEmail', $clientData['clientEmail'], strtotime('+1 day'), '/');
    // Send them to the admin view
    $message = '<h3 class="center bold success">Login was Successful '.$clientData['clientFirstname'].'</h3>';
    redirect($message, 'accounts', 'checklogin');
  break;

  case 'logout':
    //Apparently some overkill is needed here!
    if(empty($_SESSION)) session_start();
        
    unset ($_SESSION['loggedin']);
    deleteCookie('firstname');
    $_SESSION = array();
    session_destroy();
        
    $message = '<h3 class="center bold">Logged Out Successfully</h3>';
    redirect($message, 'accounts', 'login');
  break;

  case 'password':
    if(isset($_SESSION['loggedin'])){
      include '../view/pass-update.php';;
    } else {
      include '../view/login.php';
    }
  break;

  case 'passwordupdate':
    $clientPassword = filter_input(INPUT_POST, 'clientPassword');
    $clientPasswordNew = filter_input(INPUT_POST, 'clientPasswordNew');
    $clientPasswordVerify = filter_input(INPUT_POST, 'clientPasswordVerify');

    //Check if password is right
    $clientEmail = $_SESSION['clientData']['clientEmail'];
    $clientData = getClient($clientEmail);
    
    $hashCheck = password_verify($clientPassword, $clientData['clientPassword']);

    if (!$hashCheck) {
      $message = "<p class='center'>The Password was entered incorrectly!</p>";
      include '../view/pass-update.php';
      exit; 
    } 

    $pattern = '/^(?=.*[[:digit:]])(?=.*[[:punct:]])(?=.*[A-Z])(?=.*[a-z])([^\s]){8,}$/';
    $checkPassword = preg_match($pattern, $clientPasswordNew);

    //Check to see if new password has correct symbols etc
    if (empty($checkPassword)){
      $message = "<p class='center'>Your new password must meet the requirements!</p>";
      include '../view/pass-update.php';
      exit;
    }

    //Check to see if new passwords match
    if ($clientPasswordNew != $clientPasswordVerify){
      $message = "<p class='center'>The passwords do not match!</p>";
      include '../view/pass-update.php';
      exit;
    }

    //Make sure all fields are not empty
    if (empty($clientPassword) || empty($clientPasswordNew) || empty($clientPasswordVerify) ) {
     $message = "<p class='center'>Please Enter all updated information!.</p>";
     include '../view/pass-update.php';
     exit;
    }
    $newPassword = password_hash($clientPasswordNew, PASSWORD_DEFAULT);

    $updateResult = updatePassword($newPassword);
    if ($updateResult) {
      $message = "<p class='center bold'>Congratulations, your password was successfully updated.</p>";
      redirect($message, 'accounts', 'checklogin');
    } else {
      $message = "<p class='center bold error'>Error. could not update your password!</p>";
      redirect($message, 'accounts', 'password');
    }
  break;

  case 'register':
    include '../view/register.php';
  break;

  case 'registersubmit':
    // Filter and store the data
    $clientFirstname = filter_input(INPUT_POST, 'clientFirstname');
    $clientLastname = filter_input(INPUT_POST, 'clientLastname');
    $clientEmail = filter_input(INPUT_POST, 'clientEmail');
    $clientPassword = filter_input(INPUT_POST, 'clientPassword');

    $pattern = '/^(?=.*[[:digit:]])(?=.*[[:punct:]])(?=.*[A-Z])(?=.*[a-z])([^\s]){8,}$/';

    $clientEmail = filter_var($clientEmail, FILTER_VALIDATE_EMAIL);
    $checkPassword = preg_match($pattern, $clientPassword);

    if(empty($clientFirstname) || empty($clientLastname) || empty($clientEmail) || empty($checkPassword)){
        $message = '<h3 class="center bold error">Please provide information for all empty form fields.</h3>';
        redirect($message, 'accounts', 'register');
        exit; 
    }

    $hashedPassword = password_hash($clientPassword, PASSWORD_DEFAULT);

    //Called from 209
    function validate($colname, $field){

      if (empty($field)){
          if ($colname == "clientEmail"){ echo "Please enter a valid email"; } 
          else { echo "Please enter a valid password"; }
          return $fieldResult = "";
      }
      
      $fieldQuote = "'".$field."'";
      $colQuote = "`".$colname."`";
      if ( $colname == "clientEmail" ){ 
          $search = " LIKE "; 
      } else { 
          $search = " = "; 
      }
  
      $sql = 'SELECT '. $colQuote .' FROM `clients` WHERE '. $colname . $search . $fieldQuote .';';
      $return = sqlConnection($sql);
        
      if (!empty($return)) {
          $fieldResult = 1;
      } else {
          $fieldResult = '';
      }
      // $fieldResult = $someArray[0][$colname];
      return $fieldResult;
    }

    //Check if email exists in Database
    $emailCheck = validate("clientEmail", $clientEmail);

    //User has already signed up!
    if($emailCheck){
        if ($clientFirstname){$message = '<h3 class="center bold error">Sorry, '.$clientFirstname.', that email is already registered. Do you want to login instead? </h3>';}
        else { $message = '<h3 class="center bold">That email is already registered. Do you want to login instead? </h3>'; }     
        // include '../view/login.php';
        redirect($message, 'accounts', 'login');
        exit;
    }

    // Send the data to the model
    $regOutcome = regClient($clientFirstname, $clientLastname, $clientEmail, $hashedPassword);

    // Check and report the result
    if($regOutcome === 1){
        setcookie( 'firstname', $clientFirstname, strtotime('+1 year'), '/');

        $message = '<h3 class="center bold">Thanks for registering, '.$clientFirstname.'. Please use your email and password to login.</h3>';
        // include '../view/login.php';
        redirect($message, 'accounts', 'login');
        exit;
    } else {
        $message = '<h3 class="center bold error">Sorry, '.$clientFirstname.', but the registration failed. Please try again.</h3>';
        // include '../view/register.php';
        redirect($message, 'accounts', 'register');
        exit;
    }
  break;

  case 'template':
    include '../template/template.php';
  break;

  case 'update':
    if( (isset($_SESSION['loggedin'])) && (isset($_SESSION['clientData'])) ){
      include '../view/client-update.php';;
    } else {
      include '../view/login.php';
    }
  break;

  default:
    include '../view/login.php';
}

?>