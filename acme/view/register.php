<?php $page="Accounts"; //Current Navigation?> 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php' ?>

<div> <?php if(empty($message)){$message="";} getMessage($message); ?> </div>

<h1 class="center">ACME REGISTRATION</h1>
<p class="subtext center">* Indicates required field</p>
<form name="processedForm" method="post" id="Form1" action="/acme/accounts/index.php" class="textblock">
    <fieldset class="margins">
        <label for="clientFirstname">First Name*:</label>
        <input type="text" id="clientFirstname" name="clientFirstname" title="Please enter a valid Last name" <?php if(isset($clientFirstname)){echo "value='$clientFirstname'";}  ?>
required><br>
        <label for="clientLastname">Last Name*:</label>
        <input type="text" id="clientLastname" name="clientLastname" title="Please enter a valid First name" <?php if(isset($clientLastname)){echo "value='$clientLastname'";}  ?>
required><br>
        <label for="clientEmail">Email Address*:</label>
        <input type="email" id="clientEmail" name="clientEmail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder="xxxxx@xxx.xx" <?php if(isset($clientEmail)){echo "value='$clientEmail'";}  ?>required><br>
        <label for="clientPassword">Password*:</label>
        <span class="subtext">Must be at least 8 characters and contain at least 1 number, 1 capital letter and 1 special character</span>
        <input type="password" id="clientPassword" name="clientPassword" title="Password" pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required><br>
        <a href="javascript:" onclick="passwordShow();" id="showhide">Show Password</a>
        <input id="Submit" type="submit" value="Submit" name="Submit" class="submit">
        <input type="hidden" name="action" value="registersubmit">
    </fieldset>
</form>

<h3 class="center">Already have an account?</h3>
<?php buttonGenerate ('accounts', 'login', 'Login' , 'extraSpace'); ?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php' ?>