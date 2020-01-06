<?php
/*
PRODUCT REVIEWS MODEL
*/
function storeReviews($invId, $clientId, $reviewText) {
    $db = acmeConnect();
    $sql = 'INSERT INTO reviews (invId, clientId, reviewText) VALUES (:invId, :clientId, :reviewText)';
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':invId', $invId, PDO::PARAM_INT);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_STR);
    $stmt->bindValue(':reviewText', $reviewText, PDO::PARAM_STR);
    $stmt->execute();
    
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}
  
// Get Reviews from reviews table
function getReviews($clientId="") {
    $db = acmeConnect();

    if($clientId == ""){ //All clients
        // $sql = 'SELECT reviewId, invId, clientId, reviewDate, reviewText FROM reviews';
        $sql = 'SELECT reviewId, inventory.invId, clients.clientId, reviewDate, reviewText, clients.clientFirstname, clients.clientLastname, inventory.invName 
                FROM reviews INNER JOIN clients ON reviews.clientId = clients.clientId INNER JOIN inventory ON reviews.invId = inventory.invId ORDER BY reviewDate DESC';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':clientId', $clientId, PDO::PARAM_STR);
    }else{ //Specific Client
        $sql = 'SELECT reviewId, inventory.invId, clients.clientId, reviewDate, reviewText, clients.clientFirstname, clients.clientLastname, inventory.invName 
                FROM reviews INNER JOIN clients ON reviews.clientId = clients.clientId INNER JOIN inventory ON reviews.invId = inventory.invId ORDER BY reviewDate DESC';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':clientId', $clientId, PDO::PARAM_STR);
    }

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $imageArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $imageArray;
}
  
// Delete review
function deleteReviews($id) {
    $db = acmeConnect();
    $sql = 'DELETE FROM reviews WHERE reviewId = :reviewId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':reviewId', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
}

//Update previous review
function updateReviews($reviewText, $reviewId){
    $db = acmeConnect();

    $sql = 'UPDATE reviews SET reviewText = :reviewText WHERE reviewId = :reviewId';

    $stmt = $db->prepare($sql);
    
    $stmt->bindValue(':reviewText', $reviewText, PDO::PARAM_STR);
    $stmt->bindValue(':reviewId', $reviewId, PDO::PARAM_INT);

    $stmt->execute();
    $updateResult = $stmt->rowCount();
    $stmt->closeCursor();

    return $updateResult;
}

?>