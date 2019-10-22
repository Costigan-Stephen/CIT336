<?php $page="Products"; //Current Navigation?> 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php'?>

<?php if (isset($message)) { echo $message; } ?>

<form name="processedForm" method="post" id="Form1" action="/acme/products/index.php" class="textblock">
    <h1 class="center">Add Category</h1>
        <label for="categoryName">New Category Name</label>
        <input type="text" id="categoryName" name="categoryName"><br>
        <input id="Submit" type="submit" value="Submit" name="Submit" class="submit">
        <input type="hidden" name="action" value="submitCat">
</form><br>
<form class="textblock" action="/acme/products/index.php">
        <!-- <button onclick="window.location.href='/acme/accounts/index.php?action=login'" class="button">Login</button> -->
        <input id="login" type="submit" value="GO BACK" name="Submit" class="button full">
        <input type="hidden" name="action" value="return">
</form>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php'?>
