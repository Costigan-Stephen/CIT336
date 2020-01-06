<?php $page="Images"; //Current Navigation?> 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php' ?>

<div> <?php if(empty($message)){$message="";} getMessage($message); ?> </div>

<div class="margins">
    <h2>Add New Product Image</h2>
    <?php
    if (isset($message)) {
    echo $message;
    } ?>

    <form action="/acme/uploads/" method="post" enctype="multipart/form-data" class="textblock">
        <label>Product</label><br>
        <?php echo $prodSelect; ?><br><br>
        <label>Upload Image:</label><br>
        <input type="file" name="file1"><br>
        <input type="submit" class="submit extraSpace" value="Upload">
        <input type="hidden" name="action" value="upload">
    </form>

    <h2>Existing Images</h2>
    <p class="notice">If deleting an image, delete the thumbnail too and vice versa.</p>
    <?php
    if (isset($imageDisplay)) {
        echo $imageDisplay;
    } 
    ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php' ?>