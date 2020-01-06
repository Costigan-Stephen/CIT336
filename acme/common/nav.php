<!-- HEADER -->
<header><img src="/acme/images/site/logo.gif" class="logo" alt="Acme logo">

    <div class="acct">
        
        <?php   $linkImg = "<a href='/acme/accounts/index.php?action=checklogin'><img src='/acme/images/site/account.gif' class='acct_icon' alt='Account Icon'></a>".add_spaces(1) ;
            if(isset($_SESSION['loggedin'])){
                $user = "<a href='/acme/accounts/index.php?action=checklogin' class='welcome'>Welcome ".$_SESSION['clientData']['clientFirstname']."</a>".add_spaces(1);
                echo $user.$linkImg."<a href='/acme/accounts/index.php?action=logout' class='acct_nav'>Log Out</a>";
            }else{ 
                echo $linkImg."<a href='/acme/accounts/index.php?action=checklogin' class='acct_nav'>My Account</a>";
            }
        ?>
                   
    </div>
    
</header>

<?php if(empty($page)){$page="";} echo navBar($page); ?>

