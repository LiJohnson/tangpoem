<?php
function baseJSCSS(){
	?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="http://gtbcode.sinaapp.com/load.php?type=js&load=jquery.js,jquery.plugin.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<?php

	echo '<link rel="stylesheet" href="'. SITE_URL .'/css/poem.css'.'"/>';
	echo '<script src="'.SITE_URL .'/js/poem.js'.'" ></script>';
}
?>