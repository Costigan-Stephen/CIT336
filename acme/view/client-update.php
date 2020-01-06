<?php $page="Accounts"; //Current Navigation?> 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php' ?>
<?php CheckForLoggedIn() ?>
<?php
    $clientFirstname = $_SESSION['clientData']['clientFirstname'];
    $clientLastname = $_SESSION['clientData']['clientLastname'];
    $clientEmail = $_SESSION['clientData']['clientEmail'];
    // print_r ($_SESSION['clientData']);
?>
<div> <?php if(empty($message)){$message="";} getMessage($message); ?> </div>

<h1 class="center">UPDATE INFORMATION</h1>
<p style="text-align:center; margin:0; padding:0;" class="subtext">* Indicates required field</p>
<form name="processedForm" method="post" id="Form1" action="/acme/accounts/index.php" class="textblock">
    <fieldset class="margins">
        <label for="clientFirstname">First Name*:</label>
        <input type="text" id="clientFirstname" name="clientFirstname" title="Please enter a valid Last name" 
            <?php if(isset($clientFirstname)){ echo "value='$clientFirstname'";} ?> required><br>
        <label for="clientLastname">Last Name*:</label>
        <input type="text" id="clientLastname" name="clientLastname" title="Please enter a valid First name" 
            <?php if(isset($clientLastname)){echo "value='$clientLastname'";} ?> required><br>
        <label for="clientEmail">Email Address*:</label>
        <input type="email" id="clientEmail" name="clientEmail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder="xxxxx@xxx.xx" 
            <?php if(isset($clientEmail)){echo "value='$clientEmail'";} ?> required><br>
        
        <input id="Submit" type="submit" value="Submit" name="Submit" class="submit">
        <input type="hidden" name="action" value="accountupdate">
    </fieldset>
</form>

<?php buttonGenerate ('accounts', 'checklogin', 'Cancel', 'invert'); ?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php' ?>