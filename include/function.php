<?php
/**
 * @author lcs 
 * @since 2014-10-11
 * @desc  一些工具方法
 */

/**
 * 公共的js和css
 * @return void
 */
function baseJSCSS(){
	?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

	<script src="http://gtbcode.sinaapp.com/load.php?type=js&load=jquery.js,jquery.plugin.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script>$.box = $.box3 || $.box;</script>
	

	<link rel="shutcut icon" href="<?php echo SITE_URL;?>/img/T.png"/>

	<link rel="stylesheet" href="<?php echo SITE_URL;?>/css/poem.css"/>
	<link rel="stylesheet" href="<?php echo SITE_URL;?>/css/myPlayer.css"/>
	<script src="<?php echo SITE_URL;?>/js/myPlayer.js" ></script>
	<script src="<?php echo SITE_URL;?>/js/poem.js" ></script>
	<?php
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

/**
 * 获取用户信息
 * @return [type] [description]
 */
function getUser(){
	return $_SESSION['user'];
}

/**
 * 是否已经登录
 * @return boolean [description]
 */
function isLogin(){
	return !!$_SESSION['user'];
}

/**
 * 检验是否admin用户
 * @return [type] [description]
 */
function checkAdmin(){
	if( !isLogin() ){
		return false;
	}
	$kv = new MyKV();
	$user = getUser();

	$adminId = $kv->get("adminId");

	if( !$adminId ){
		$adminId = array($user['id']);
		$kv->set("root" , $user['id']);
		//$adminId = $kv->get("adminId");
	}
	$adminId[] = $kv->get("root");
	return in_array( $user['id'] , $adminId );
}