<?php if(isset($prodInfo['invName'])){ 
      $page = "Modify $prodInfo[invName] ";} 
      elseif(isset($invName)) { $page = $invName; } else { $page = "Products"; }?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php'?>
<?php CheckForLoggedIn() ?>



<form name="processedForm" method="post" id="Form1" action="/acme/products/index.php" class="textblock margins">
<?php if(empty($message)){$message="";} getMessage($message); ?>
    <h1 class="center">
        <?php if(isset($prodInfo['invName'])){ 
            echo "Modify $prodInfo[invName] ";
        } elseif(isset($invName)) { 
            echo $invName; 
        }?>
    </h1>
    <label for="invCategory">Category</label>
    <select id="invCategory" name="invCategory" class="dropdown stretchFull">
        <?php
            $invId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            $prodInfo = getProductInfo($invId);
            $categories = getCategories();
            $catList = "";
            $catList .= "<option>Choose a Category</option>";
            foreach ($categories as $category) {
                $catList .= "<option value='$category[categoryID]'";
                if(isset($catType)){
                    if($category['categoryID'] === $catType){
                    $catList .= ' selected ';
                    }
                } elseif(isset($prodInfo['categoryId'])){
                if($category['categoryID'] === $prodInfo['categoryId']){
                    $catList .= ' selected ';
                }
            }
            $catList .= ">$category[categoryName]</option>";
            }
            echo $catList;
        ?>
    </select>
    <label for="invName">Product Name</label>
    <input type="text" id="invName" name="invName" 
        <?php if(isset($invName)){ echo "value='$invName'"; } elseif(isset($prodInfo['invName'])) { echo "value='$prodInfo[invName]'"; }?> required><br>
    <label for="invDescription">Product Description</label>
    <textarea id="invDescription" name="invDescription" class="commentarea" required><?php if(isset($invDescription)){ echo $invDescription; } elseif(isset($prodInfo['invDescription'])) { echo $prodInfo['invDescription']; }?></textarea><br>
    <label for="invImage">Product Image (URL)</label>
    <input type="text" id="invImage" name="invImage" 
        <?php if(isset($invImage)){ echo "value='$invImage'"; } elseif(isset($prodInfo['invImage'])) { echo "value='$prodInfo[invImage]'"; }?> required><br>
    <label for="invThumbnail">Product Thumbnail (URL)</label>
    <input type="text" id="invThumbnail" name="invThumbnail" 
        <?php if(isset($invThumbnail)){ echo "value='$invThumbnail'"; } elseif(isset($prodInfo['invThumbnail'])) { echo "value='$prodInfo[invThumbnail]'"; }?> required><br>
    <label for="invPrice">Product Price</label>
    <input type="text" id="invPrice" name="invPrice" 
        <?php if(isset($invPrice)){ echo "value='$invPrice'"; } elseif(isset($prodInfo['invPrice'])) { echo "value='$prodInfo[invPrice]'"; }?> required><br>
    <label for="invStock">Quantity in Stock</label>
    <input type="text" id="invStock" name="invStock" 
        <?php if(isset($invStock)){ echo "value='$invStock'"; } elseif(isset($prodInfo['invStock'])) { echo "value='$prodInfo[invStock]'"; }?> required><br>
    <label for="invSize">Item Size</label>
    <input type="text" id="invSize" name="invSize" 
        <?php if(isset($invSize)){ echo "value='$invSize'"; } elseif(isset($prodInfo['invSize'])) { echo "value='$prodInfo[invSize]'"; }?> required><br>
    <label for="invWeight">Item Weight</label>
    <input type="text" id="invWeight" name="invWeight" 
        <?php if(isset($invWeight)){ echo "value='$invWeight'"; } elseif(isset($prodInfo['invWeight'])) { echo "value='$prodInfo[invWeight]'"; }?> required><br>
    <label for="invLocation">Location</label>
    <input type="text" id="invLocation" name="invLocation" 
        <?php if(isset($invLocation)){ echo "value='$invLocation'"; } elseif(isset($prodInfo['invLocation'])) { echo "value='$prodInfo[invLocation]'"; }?> required><br>
    <label for="invVendor">Vendor</label>
    <input type="text" id="invVendor" name="invVendor" 
        <?php if(isset($invVendor)){ echo "value='$invVendor'"; } elseif(isset($prodInfo['invVendor'])) { echo "value='$prodInfo[invVendor]'"; }?> required><br>
    <label for="invStyle">Style</label>
    <input type="text" id="invStyle" name="invStyle" 
        <?php if(isset($invStyle)){ echo "value='$invStyle'"; } elseif(isset($prodInfo['invStyle'])) { echo "value='$prodInfo[invStyle]'"; }?>   required><br>


    <input id="Submit" type="submit" value="Update Product" name="Submit" class="submit">
    <input type="hidden" name="action" value="updateProd">
    <input type="hidden" name="invId" value="<?php if(isset($prodInfo['invId'])){ echo $prodInfo['invId'];} elseif(isset($invId)){ echo $invId; } ?>">
</form>
<?php buttonGenerate ('products', 'return', 'GO BACK'); ?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php' ?>