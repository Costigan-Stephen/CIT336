<?php $page="Products"; //Current Navigation?> 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php' ?>

<?php if (isset($message)) { echo $message; } ?>

<form name="processedForm" method="post" id="Form1" action="/acme/accounts/index.php" class="textblock">
    <fieldset>
        <!-- <h3>Email Address:</h3> -->
        <input type="email" id="clientEmail" name="clientEmail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder="Email Address" required><br>
        <!-- <h3>Password:</h3> -->
        <input type="password" id="clientPassword" name="clientPassword" title="Password" placeholder="Password" required><br>

        <a href="javascript:alert('a message has been sent to your email with instructions');">Forgot Password</a>
        <input id="login" type="submit" value="login" name="Submit" class="submit">
        <input type="hidden" name="action" value="loginsubmit">
    </fieldset>
</form>
<form class="textblock" action="/acme/accounts/index.php">
        
        <input id="submit" type="submit" value="Create an Account" name="Submit" class="button full">
        <input type="hidden" name="action" value="register">
</form>



<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php' ?>