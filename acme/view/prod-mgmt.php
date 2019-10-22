<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php'?>
    <form class="textblock" style="text-align:center;">

        <?php if (isset($message)) { echo $message; } ?>
        
        <h1>Product Management</h1>
        <p>Welcome to the product management page.  Please choose an option below:</p>
        <ul style="list-style-type:none; list-style:none; padding: 0;">
            <li><a href="javascript:" onclick="window.location.href='/acme/products/index.php?action=newCat'">Add a new Category</a></li>
            <li><a href="javascript:" onclick="window.location.href='/acme/products/index.php?action=newProd'">Add a new Product</a></li>
        </ul>
    </form>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php'?>
