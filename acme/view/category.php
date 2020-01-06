<?php 
$page=$categoryName;
$header = $page." Products"; //Current Navigation
?> 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php'?>

<?php if(empty($message)){$message="";} getMessage($message); ?>

<div class="categories">
    <h1><?php echo $categoryName; ?> Products</h1>
    <?php if(isset($prodDisplay)){ echo $prodDisplay; } ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php'?>
