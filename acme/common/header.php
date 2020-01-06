<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php if(!empty($header)){echo $header;} else {echo 'Home';} ?> | ACME INC</title>

	<link rel="stylesheet" media="screen" href="/acme/css/style.css">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Stephen Costigan">
	<meta name="description" content="Acme site template">
	<meta name="robots" content="all">
</head>



<body>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/hidedelay.php'?>
	
    <nav><?php include $_SERVER['DOCUMENT_ROOT'] . '/acme/common/nav.php'?></nav>
<?php sleep(.5); //Slight delay for loading?> 