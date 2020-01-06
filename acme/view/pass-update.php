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

<h1 class="center;">PASSWORD UPDATE</h1>
<p class="center;">Warning: You are about to change your password, if you do not want to complete this action, press cancel below!</p>
<p style="text-align:center; margin:0; padding:0;" class="subtext">* Indicates required field</p>
<form name="processedForm" method="post" id="Form1" action="/acme/accounts/index.php" class="textblock margins">
    <fieldset class="margins">
        <label for="clientPassword">Current Password*:</label>
        <input type="password" id="clientPassword" name="clientPassword" title="Password" placeholder="Password" required><br>

        <span class="subtext">Must be at least 8 characters and contain at least 1 number, 1 capital letter and 1 special character</span>
        <label for="clientPasswordNew">New Password*:</label>
        <input type="password" id="clientPasswordNew" name="clientPasswordNew" title="Password" placeholder="Password" required><br>

        <label for="clientPasswordVerify">Verify New Password*:</label>
        <input type="password" id="clientPasswordVerify" name="clientPasswordVerify" title="Password" placeholder="Password" required><br>
        
        <input id="Submit" type="submit" value="Submit" name="Submit" class="submit">
        <input type="hidden" name="action" value="passwordupdate">
    </fieldset>
</form>

<?php buttonGenerate ('accounts', 'checklogin', 'Cancel', 'invert'); ?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php' ?>