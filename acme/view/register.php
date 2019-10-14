<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php' ?>

<?php
if (isset($message)) {
 echo $message;
}
?>
<form name="processedForm" method="post" id="Form1" action="/acme/accounts/index.php">
    <fieldset>
        <h3>First Name:</h3>
        <input type="text" id="clientFirstname" name="clientFirstname" title="Please enter a valid Last name"><br>
        <h3>Last Name:</h3>
        <input type="text" id="clientLastname" name="clientLastname" title="Please enter a valid First name"><br>
        <h3>Email Address:</h3>
        <input type="email" id="clientEmail" name="clientEmail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder="xxxxx@xxx.xx" required><br>
        <h3>Password:</h3>
        Passwords must be at least 8 characters and contain at least 1 number, 1 capital letter and 1 special character
        <input type="password" id="clientPassword" name="clientPassword" title="Password"><br>
        <a href="javascript:" onclick="passwordShow();" id="showhide">Show Password</a>
        <input id="Submit" type="submit" value="Submit" name="Submit" class="submit">
        <input type="hidden" name="action" value="register">
        <br><br>
        <h3>Already have an account?</h3>
        <button onclick="window.location.href='/acme/accounts/index.php?action=login'" class="button">Login</button>
    </fieldset>
</form>



<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php' ?>