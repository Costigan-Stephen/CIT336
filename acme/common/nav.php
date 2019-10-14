<!-- HEADER -->
<header><img src="/acme/images/site/logo.gif" class="logo" alt="Acme logo">
<a href="/acme/accounts/index.php?action=login"><div class="acct"><img src="/acme/images/site/account.gif" class="acct_icon" alt="Account Icon">My Account</div></a></header>

<?php 
// Build a navigation bar using the $categories array

// Get the array of categories
$categories = getCategories();

// // var_dump($categories);
// postVariable($categories);
$navList = '<ul>';
$navList .= "<li><a href='/acme/index.php' title='View the Acme home page' class='";
if($page=="Home"){
  $navList .= 'active';
}
$navList .= "'>Home</a></li>";

foreach ($categories as $category) {
  $navList .= "<li><a href='/acme/index.php?action=".urlencode($category['categoryName'])."' class='";
 if($page == $category){
    $navList .= 'active';
  }
 $navList .= "' title='View our $category[categoryName] product line'>$category[categoryName]</a></li>";
}
$navList .= '</ul>';
?>

<?php echo $navList; ?>
