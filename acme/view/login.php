<?php $page="Accounts"; //Current Navigation?> 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php' ?>

<?php if(empty($message)){$message="";} getMessage($message); ?>

<h1 class="center">ACME ACCOUNT LOGIN</h1>
<p class="subtext center">* Indicates required field</p>
<form name="processedForm" method="post" id="Form1" action="/acme/accounts/index.php" class="textblock">
    <fieldset class="margins">
        <!-- <h3>Email Address:</h3> -->
        <input type="email" id="clientEmail" name="clientEmail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder="Email Address" <?php if(isset($clientEmail)){echo "value='$clientEmail'";}  ?> required><br>
        <!-- <h3>Password:</h3> -->
        <input type="password" id="clientPassword" name="clientPassword" title="Password" placeholder="Password" required><br>
        <a href="javascript:" onclick="passwordShow();" id="showhide">Show Password</a> &nbsp;
        
        <a href="javascript:alert('a message has been sent to your email with instructions');">Forgot Password</a> &nbsp;
        <a href="javascript:alert('Must be at least 8 characters and contain at least 1 number, 1 capital letter and 1 special character.');">Login Help</a>

        <input id="login" type="submit" value="login" name="Submit" class="submit">
        <input type="hidden" name="action" value="loginsubmit">
    </fieldset>
</form>

<?php buttonGenerate ('accounts', 'register', 'Create an Account', 'extraSpace'); ?>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php' ?>