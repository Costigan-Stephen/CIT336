<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php' ?>



<form name="processedForm" method="get" id="Form1" action="action_page.php">
    <fieldset>
        <!-- <h3>Email Address:</h3> -->
        <input type="email" name="clientEmail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder="Email Address" required><br>
        <!-- <h3>Password:</h3> -->
        <input type="text" id="password" name="clientPassword" title="Password" placeholder="Password" required><br>

        <input id="login" type="submit" value="login" name="Submit" class="submit"><br><br>
        Forgot Password? <a href="#">Send reset email</a>
        <button onclick="window.location.href='/acme/accounts/index.php?action=signup'" class="button">Create an Account</button>
    </fieldset>
</form>



<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php' ?>