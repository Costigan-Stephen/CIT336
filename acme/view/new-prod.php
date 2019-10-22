<?php $page = "Products"; //Current Navigation
?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php' ?>

<?php if (isset($message)) { echo $message; } ?>

<form name="processedForm" method="post" id="Form1" action="/acme/products/index.php" class="textblock">
    <h1 class="center">Add Product</h1>
    <label for="invCategory">Category</label>
    <select id="invCategory" name="invCategory" class="dropdown" style="width: 100%;">
        <option label="" value="Choose a Category"></option>
        <?php
        echo productList();
        ?>
    </select>
    <label for="invName">Product Name</label>
    <input type="text" id="invName" name="invName"><br>
    <label for="invDescription">Product Description</label>
    <textarea id="invDescription" name="invDescription" class="commentarea"></textarea><br>
    <label for="invImage">Product Image (URL)</label>
    <input type="text" id="invImage" name="invImage"><br>
    <label for="invThumbnail">Product Thumbnail (URL)</label>
    <input type="text" id="invThumbnail" name="invThumbnail"><br>
    <label for="invPrice">Product Price</label>
    <input type="text" id="invPrice" name="invPrice"><br>
    <label for="invStock">Quantity in Stock</label>
    <input type="text" id="invStock" name="invStock"><br>
    <label for="invSize">Item Size</label>
    <input type="text" id="invSize" name="invSize"><br>
    <label for="invWeight">Item Weight</label>
    <input type="text" id="invWeight" name="invWeight"><br>
    <label for="invLocation">Location</label>
    <input type="text" id="invLocation" name="invLocation"><br>
    <label for="invVendor">Vendor</label>
    <input type="text" id="invVendor" name="invVendor"><br>
    <label for="invStyle">Style</label>
    <input type="text" id="invStyle" name="invStyle"><br>


    <input id="Submit" type="submit" value="Submit" name="Submit" class="submit">
    <input type="hidden" name="action" value="submitProd">
</form><br>

<form class="textblock" action="/acme/products/index.php">
    <!-- <button onclick="window.location.href='/acme/accounts/index.php?action=login'" class="button">Login</button> -->
    <input id="login" type="submit" value="GO BACK" name="Submit" class="button full">
    <input type="hidden" name="action" value="return">
</form>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php' ?>