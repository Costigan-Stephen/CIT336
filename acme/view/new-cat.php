<?php $page="Products"; //Current Navigation?> 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php'?>
<?php CheckForLoggedIn() ?>

<?php if(empty($message)){$message="";} getMessage($message); ?>

<form name="processedForm" method="post" id="Form1" action="/acme/products/index.php" class="textblock margins">
    <h1 class="center">Add Category</h1>
        <label for="categoryName">New Category Name</label>
        <input type="text" id="categoryName" name="categoryName"><br>
        <input id="Submit" type="submit" value="Submit" name="Submit" class="submit">
        <input type="hidden" name="action" value="submitCat">
</form>
<?php buttonGenerate ('products', 'return', 'GO BACK'); ?>
<br>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php'?>
