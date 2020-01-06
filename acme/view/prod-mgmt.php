<?php $page = "Products"; //Current Navigation ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php'?>
<?php CheckForLoggedIn() ?>

<div> <?php if(empty($message)){$message="";} getMessage($message); ?> </div>
    <div class="textblock margins center">
        <?php
            if (isset($categoryList)) { 
                echo '<h2>Products By Category</h2>'; 
                echo '<p>Choose a category to see those products</p>'; 
                echo $categoryList; 
            }
        ?>
        <noscript>
            <p><strong>JavaScript Must Be Enabled to Use this Page.</strong></p>
        </noscript>
        <table id="productsDisplay" class="prodtable margins"></table>

        <?php buttonGenerate ('products', 'newCat', 'Add a new Category'); ?>
        <?php buttonGenerate ('products', 'newProd', 'Add a new Product'); ?>
        <?php buttonGenerate ('uploads', 'defaultView', 'Upload Images'); ?>

        <?php buttonGenerate ('accounts', 'checklogin', 'Back to Admin', 'invert'); ?>
</div>
<script src="../js/products.js"></script>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php'?>
