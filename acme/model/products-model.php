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

// Get product information by invId<>
function getProductInfo($invId){
  $db = acmeConnect();
  $sql = 'SELECT * FROM inventory WHERE invId = :invId';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':invId', $invId, PDO::PARAM_INT);
  $stmt->execute();
  $prodInfo = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $prodInfo;
}

// Get products by categoryId 
function getProductsByCategoryId($categoryId){ 
  $db = acmeConnect(); 
  $sql = ' SELECT * FROM inventory WHERE categoryId = :categoryId'; 
  $stmt = $db->prepare($sql); 
  $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT); 
  $stmt->execute(); 
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC); 
  $stmt->closeCursor(); 
  return $products; 
}

function getCategoryName($categoryId){ 
  $db = acmeConnect(); 
  $sql = ' SELECT categoryName FROM categories WHERE categoryId = :categoryId'; 
  $stmt = $db->prepare($sql); 
  $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT); 
  $stmt->execute(); 
  $catName = $stmt->fetchAll(PDO::FETCH_ASSOC); 
  $stmt->closeCursor(); 
  return $catName; 
}

function deleteProduct($invId) {
  $db = acmeConnect();
  $sql = 'DELETE FROM inventory WHERE invId = :invId';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':invId', $invId, PDO::PARAM_INT);
  $stmt->execute();
  $rowsChanged = $stmt->rowCount();
  $stmt->closeCursor();
  return $rowsChanged;
}

function getProductsByCategory($categoryName){
  $db = acmeConnect();
  $sql = 'SELECT * FROM inventory WHERE categoryId IN (SELECT categoryId FROM categories WHERE categoryName = :categoryName) ';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':categoryName', $categoryName, PDO::PARAM_STR);
  $stmt->execute();
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $products;
}

function getProduct($invName){
  $db = acmeConnect();
  $sql = 'SELECT * FROM inventory WHERE invName = :invName';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':invName', $invName, PDO::PARAM_STR);
  $stmt->execute();
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $products;
}

function getProductBasics() {
  $db = acmeConnect();
  $sql = 'SELECT invName, invId FROM inventory ORDER BY invId ASC';
  
  $stmt = $db->prepare($sql);
  $stmt -> execute();
  $products = $stmt->fetchAll();
  $stmt -> closeCursor();
  return $products;
}

?>