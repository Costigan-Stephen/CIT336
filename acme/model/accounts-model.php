<?php
/*
ACCOUNTS MODEL
*/
function sqlConnection($var){
    // Create a connection object from the acme connection function
    $db = acmeConnect();
    // The SQL statement to be used with the database 
    if($var){ $sql = $var; } 
    else { $sql = 'SELECT invName FROM inventory'; }     
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


function regClient($clientFirstname, $clientLastname, $clientEmail, $clientPassword){
    // Create a connection object using the acme connection function
    $db = acmeConnect();
    // The SQL statement
    $sql = 'INSERT INTO clients (clientFirstname, clientLastname,clientEmail, clientPassword)
        VALUES (:clientFirstname, :clientLastname, :clientEmail, :clientPassword)';
    // Create the prepared statement using the acme connection
    $stmt = $db->prepare($sql);
    // The next four lines replace the placeholders in the SQL
    // statement with the actual values in the variables
    // and tells the database the type of data it is
    $stmt->bindValue(':clientFirstname', $clientFirstname, PDO::PARAM_STR);
    $stmt->bindValue(':clientLastname', $clientLastname, PDO::PARAM_STR);
    $stmt->bindValue(':clientEmail', $clientEmail, PDO::PARAM_STR);
    $stmt->bindValue(':clientPassword', $clientPassword, PDO::PARAM_STR);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $rowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();
    // Return the indication of success (rows changed)
    return $rowsChanged;
}

function parseData(){
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

    $email = filter_input(INPUT_POST, 'clientEmail');
    $pass = filter_input(INPUT_POST, 'clientPassword');

    $emailCheck = validate("clientEmail", $clientEmail);

    //User has already signed up!
    if($emailCheck){
        $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Sorry, '.$clientFirstname.', that email is already registered!</h3>';
        include '../view/register.php';
        exit;
    }

    // Send the data to the model
    $regOutcome = regClient($clientFirstname, $clientLastname, $clientEmail, $clientPassword);

    // Check and report the result
    if($regOutcome === 1){
        $message = '<h3 style="text-align:center; font-weight: bold;">Thanks for registering, '.$clientFirstname.'. Please use your email and password to login.</h3>';
        include '../view/login.php';
        exit;
    } else {
        $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Sorry, '.$clientFirstname.', but the registration failed. Please try again.</h3>';
        include '../view/register.php';
        exit;
    }
}

function loginCheck(){

    $email = filter_input(INPUT_POST, 'clientEmail');
    $pass = filter_input(INPUT_POST, 'clientPassword');

    $emailCheck = validate("clientEmail", $email);
    $passCheck = validate("clientPassword", $pass);

  if(empty($emailCheck) || empty($passCheck)){
    // echo "Email/password does not exist!";
    $message = '<h3 style="text-align:center; font-weight: bold; color: red;">Invalid Email and Password combination</h3>';
    include '../view/login.php';
  } else {
    header('Location: /acme/products/index.php');
    $message = '<h3 style="text-align:center; font-weight: bold; color: green;">Login was Successful</h3>';
    exit; 
  }

}

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
  
    //Convert SQL to JSON and then parse searched field.
    $value = json_encode($return);
    $someArray = json_decode($value, true);
      
    if (!empty($return)) {
        $fieldResult = 1;
    } else {
        $fieldResult = '';
    }
    // $fieldResult = $someArray[0][$colname];
    return $fieldResult;
  }

?>