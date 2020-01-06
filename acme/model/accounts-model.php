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


// Get client data based on an email address
function getClient($clientEmail){
    $db = acmeConnect();
    $sql = 'SELECT clientId, clientFirstname, clientLastname, clientEmail, 
            clientLevel, clientPassword FROM clients WHERE clientEmail = :email';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':email', $clientEmail, PDO::PARAM_STR);
    $stmt->execute();
    $clientData = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    return $clientData;
}

//
// Check for an existing email address
//
function checkExistingEmail($clientEmail) {
    $db = acmeConnect();
    $sql = 'SELECT clientEmail FROM clients WHERE clientEmail = :email';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':email', $clientEmail, PDO::PARAM_STR);
    $stmt->execute();
    $matchEmail = $stmt->fetch(PDO::FETCH_NUM);
    $stmt->closeCursor();
    if(empty($matchEmail)){
        return 0;
    } else {
        return 1;
    }
}

function logout(){
    //Apparently some overkill is needed here!
    if(empty($_SESSION)) session_start();
        
    unset ($_SESSION['loggedin']);
    unset ($_SESSION['clientData']);
    $_SESSION = array();
    session_destroy();
        
    $message = '<h3 class="center bold error">Logged Out Successfully</h3>';
    redirect($message, 'accounts', 'login');
    exit;
}

function updateAccount($clientFirstname, $clientLastname, $clientEmail){
    $clientId = $_SESSION['clientData']['clientId'];
    $db = acmeConnect();
    // The SQL statement to be used with the database
    $sql = 'UPDATE clients SET clientFirstname = :clientFirstname, 
      clientLastname = :clientLastname, clientEmail = :clientEmail WHERE clientId = :clientId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientFirstname', $clientFirstname, PDO::PARAM_STR);
    $stmt->bindValue(':clientLastname', $clientLastname, PDO::PARAM_STR);
    $stmt->bindValue(':clientEmail', $clientEmail, PDO::PARAM_STR);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_STR);

    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}

function updatePassword($clientPassword){
    $clientId = $_SESSION['clientData']['clientId'];
    $db = acmeConnect();
    // The SQL statement to be used with the database
    $sql = 'UPDATE clients SET clientPassword = :clientPassword WHERE clientId = :clientId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientPassword', $clientPassword, PDO::PARAM_STR);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_STR);

    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}

?>
