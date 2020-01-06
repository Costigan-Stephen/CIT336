<?php if(isset($prodInfo['invName'])){ 
      $page = "Delete $prodInfo[invName] ";} 
      elseif(isset($invName)) { $page = $invName; } else { $page = "Products"; }?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php'?>
<?php CheckForLoggedIn() ?>



<form name="processedForm" method="post" id="Form1" action="/acme/products/index.php" class="textblock margins">
<?php if(empty($message)){$message="";} getMessage($message); ?>
    <h1 class="center">
        <?php if(isset($prodInfo['invName'])){ 
            echo "Delete $prodInfo[invName] ";
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
    <textarea id="invDescription" name="invDescription" class="commentarea" required>
        <?php if(isset($invDescription)){ echo $invDescription; } elseif(isset($prodInfo['invDescription'])) { echo $prodInfo['invDescription']; }?>
    </textarea><br>
    <p>Confirm Product Deletion. The delete is permanent.</p>
    <input id="Submit" type="submit" value="Delete Product" name="Submit" class="submit">
    <input type="hidden" name="action" value="deleteProd">
    <input type="hidden" name="invId" value="<?php if(isset($prodInfo['invId'])){ echo $prodInfo['invId'];} elseif(isset($invId)){ echo $invId; } ?>">
</form>
<?php buttonGenerate ('products', 'return', 'GO BACK'); ?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php' ?>