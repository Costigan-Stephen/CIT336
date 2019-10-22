<?php $page="Accounts"; //Current Navigation?> 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php' ?>

<?php if (isset($message)) { echo $message; } ?>

<form name="processedForm" method="post" id="Form1" action="/acme/accounts/index.php" class="textblock">
    <fieldset>
        <label for="clientFirstname">First Name:</label>
        <input type="text" id="clientFirstname" name="clientFirstname" title="Please enter a valid Last name"><br>
        <label for="clientLastname">Last Name:</label>
        <input type="text" id="clientLastname" name="clientLastname" title="Please enter a valid First name"><br>
        <label for="clientEmail">Email Address:</label>
        <input type="email" id="clientEmail" name="clientEmail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder="xxxxx@xxx.xx" required><br>
        <label for="clientPassword">Password:</label>
        Must be at least 8 characters and contain at least 1 number, 1 capital letter and 1 special character
        <input type="password" id="clientPassword" name="clientPassword" title="Password"><br>
        <a href="javascript:" onclick="passwordShow();" id="showhide">Show Password</a>
        <input id="Submit" type="submit" value="Submit" name="Submit" class="submit">
        <input type="hidden" name="action" value="registersubmit">
    </fieldset>
</form>
<form class="textblock" action="/acme/accounts/index.php">
        <h3 style="text-align:center;">Already have an account?</h3>
        <!-- <button onclick="window.location.href='/acme/accounts/index.php?action=login'" class="button">Login</button> -->
        <input id="login" type="submit" value="Login" name="Submit" class="button full">
        <input type="hidden" name="action" value="login">
</form>



<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php' ?>