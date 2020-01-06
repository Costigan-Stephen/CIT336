<?php $page = "Products"; //Current Navigation ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php' ?>
<?php CheckForLoggedIn() ?>

<?php if(empty($message)){$message="";} getMessage($message); ?>

<form name="processedForm" method="post" id="Form1" action="/acme/products/index.php" class="textblock margins">
    <h1 class="center">Add Product</h1>
    <label for="invCategory">Category</label>
    <select id="invCategory" name="invCategory" class="dropdown stretchFull" <?php if(isset($invCategory)){echo "value='$invCategory'";} ?>>
    <option label="-" value="Choose a Category"></option>
    <?php
        // Get the array of categories
        $categories = getCategories();
        $invId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $prodInfo = getProductInfo($invId);
        $productList = '';
        foreach ($categories as $category) {
            $productList .= "<option value='" . strtolower($category['categoryName']);
            if (isset($invCategory)) {
                if ($category['categoryId'] === $prodInfo) {
                $productList .= ' selected ';
                }
            } elseif (isset($prodInfo['categoryId'])) {
                if ($category['categoryId'] === $prodInfo['categoryId']) {
                $productList .= ' selected ';
                }
            }

            $productList .= "'>" . $category['categoryName'] . "</option>";
        }

        echo $productList;
    ?>
    </select>
    <label for="invName">Product Name</label>
    <input type="text" id="invName" name="invName" <?php if(isset($invName)){echo "value='$invName'";} ?> required><br>
    <label for="invDescription">Product Description</label>
    <textarea id="invDescription" name="invDescription" class="commentarea" <?php if(isset($invDescription)){echo "value='$invDescription'";} ?>required></textarea><br>
    <label for="invImage">Product Image (URL)</label>
    <input type="text" id="invImage" name="invImage" <?php if(isset($invImage)){echo "value='$invImage'";} ?> required><br>
    <label for="invThumbnail">Product Thumbnail (URL)</label>
    <input type="text" id="invThumbnail" name="invThumbnail" <?php if(isset($invThumbnail)){echo "value='$invThumbnail'";} ?> required><br>
    <label for="invPrice">Product Price</label>
    <input type="text" id="invPrice" name="invPrice" <?php if(isset($invPrice)){echo "value='$invPrice'";} ?> required><br>
    <label for="invStock">Quantity in Stock</label>
    <input type="text" id="invStock" name="invStock" <?php if(isset($invStock)){echo "value='$invStock'";} ?> required><br>
    <label for="invSize">Item Size</label>
    <input type="text" id="invSize" name="invSize" <?php if(isset($invSize)){echo "value='$invSize'";} ?> required><br>
    <label for="invWeight">Item Weight</label>
    <input type="text" id="invWeight" name="invWeight" <?php if(isset($invWeight)){echo "value='$invWeight'";} ?> required><br>
    <label for="invLocation">Location</label>
    <input type="text" id="invLocation" name="invLocation" <?php if(isset($invLocation)){echo "value='$invLocation'";} ?> required><br>
    <label for="invVendor">Vendor</label>
    <input type="text" id="invVendor" name="invVendor" <?php if(isset($invVendor)){echo "value='$invVendor'";} ?> required><br>
    <label for="invStyle">Style</label>
    <input type="text" id="invStyle" name="invStyle" <?php if(isset($invStyle)){echo "value='$invStyle'";} ?> required><br>


    <input id="Submit" type="submit" value="Submit" name="Submit" class="submit">
    <input type="hidden" name="action" value="submitProd">
</form>

<?php buttonGenerate ('products', 'return', 'GO BACK'); ?>
<br>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php' ?>