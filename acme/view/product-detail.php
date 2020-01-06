<?php
$page = $categoryName;
$header = $invName." Products"; //Current Navigation
?> 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php'?>

<?php if(empty($message)){$message="";} getMessage($message); ?>

<div class="categories">
    <?php 
    if (isset($prodDisplay)){ echo $prodDisplay; } 
    if (isset($imageDisplay)) { echo $imageDisplay; }
    ?>
</div>
<h1 class="center headingSection">Customer Reviews</h1>
<?php 
    if( (isset($_SESSION['loggedin'])) && (isset($_SESSION['clientData'])) ){
      $screenName = substr( $_SESSION['clientData']['clientFirstname'], 0, 1 ).$_SESSION['clientData']['clientLastname'];

      $display  = '<form name="processedForm" method="post" id="Form1" action="/acme/reviews/index.php" class="textblock">';
        $display .= '<fieldset class="margins noBorder">';
          $display .= '<label>Screen Name*:</label>';
          $display .= '<span class="bold larger">'.$screenName.'</span><br>';
          $display .= '<label for="userReview">Review*:</label>';
          $display .= '<textarea id="userReview" name="userReview" title="Please enter a valid First name" class="commentarea" required>';
          if(isset($userReview)){$display .= $userReview;}
          $display .= '</textarea>';
          $display .= '<input id="Submit" type="submit" value="Submit Review" name="Submit" class="submit">';
          $display .= '<input type="hidden" name="action" value="reviewSubmit">';
        $display .= '</fieldset>';
      $display .= '</form>';
      echo $display;
    } else {
      echo "<span class='center'>You must be logged in to leave a review!<a href='/acme/accounts/index.php?action=checkLogin'>Click here to log-in</a></span>";
    }
?>   

<?php if (isset($reviews)) { echo $reviews; } ?>
<br>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php'?> 