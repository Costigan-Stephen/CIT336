<?php
$page = "Review Edit";
$header = " Reviews"; //Current Navigation
?> 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php'?>

<?php if(empty($message)){$message="";} getMessage($message); ?>


<h1 class="center">Modify your Review</h1>

<form name="processedForm" method="post" id="Form1" action="/acme/reviews/index.php" class="textblock">
    <fieldset class="margins noBorder">
        <label>Screen Name*:</label>
        <span class='bold larger'><?php echo $screenName ?></span><br>
        <label for="userReview">Review*:</label>
        <textarea id="userReview" name="userReview" title="Please enter a valid First name" class="commentarea" required><?php if(isset($userReview)){echo trim($userReview);} ?></textarea><br>
        <input id="Submit" type="submit" value="Change Review" name="Submit" class="submit">
        <input type="hidden" name="action" value='reviewEdit-Submit'>
    </fieldset>
</form>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php'?>