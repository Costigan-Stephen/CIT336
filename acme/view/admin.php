<?php $page="Home"; //Current Navigation?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/header.php'?>
<?php CheckForLoggedIn() ?>

<?php if(empty($message)){$message="";} getMessage($message); ?>    
    <div class="textblock margins" style="text-align:left;">
        <h1 class="center"><?php displayData('', "", 'clientFirstname'); ?> <?php displayData('', "", 'clientLastname'); ?></h1>
        <p>You are logged in.</p>
        <p><?php displayData(8, "First Name:", 'clientFirstname'); ?></p>
        <p><?php displayData(8, "Last Name:", 'clientLastname'); ?></p>
        <p><?php displayData(8, "User Email:", 'clientEmail'); ?></p>

        <?php buttonGenerate ('accounts', 'update', 'Update Information'); ?>
        <?php buttonGenerate ('accounts', 'password', 'Change Password'); ?>

        <?php $access = accessLevel(2);
            if($access > 0){
            echo "<h3 class='center '>Admin Tools</h3>";
            buttonGenerate ('products', 'prodMgmt', 'Product Management'); } ?>
        <br>
        </div>
        <?php 
            buttonGenerate ('accounts', 'logout', 'Log Out', 'invert'); 
            if (isset($reviews)) { 
                echo "<h1 class='center stretchFull headingSection'>Manage your Product Reviews</h1>";
                echo $reviews; 
            }
        ?>
        <br>
          
    
<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/footer.php'?>
