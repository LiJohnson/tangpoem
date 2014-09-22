<?php
function baseJSCSS(){
	?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

	<script src="http://gtbcode.sinaapp.com/load.php?type=js&load=jquery.js,jquery.plugin.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script>$.box = $.box3 || $.box;</script>
	<?php

	echo '<link rel="stylesheet" href="'. SITE_URL .'/css/poem.css'.'"/>';
	echo '<link rel="stylesheet" href="'. SITE_URL .'/css/myPlayer.css'.'"/>';
	echo '<script src="'.SITE_URL .'/js/myPlayer.js'.'" ></script>';
	echo '<script src="'.SITE_URL .'/js/poem.js'.'" ></script>';
}

/**
 * 序列化
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function ser($data){
	if( is_array($data) || is_object($data) ){
		return serialize(serialize($data));
	}
	return $data;
}

/**
 * 反序列化
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function unser($data){
	return unserialize(unserialize($data));
}
?>