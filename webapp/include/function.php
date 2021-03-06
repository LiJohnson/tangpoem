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
<!--
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
-->
	<script src="http://gtbcode.sinaapp.com/load.php?type=js&load=jquery.js,jquery.plugin.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script>$.box = $.box3 || $.box;</script>
	

	<link rel="shutcut icon" href="<?php resource('/img/32.png');?>"/>
	<link rel="stylesheet"   href="<?php resource('/css/poem.css'); ?>"/>
	<link rel="stylesheet"   href="<?php resource('/css/myPlayer.css'); ?>"/>
	<script src="<?php resource('/js/myPlayer.js'); ?>" ></script>
	<script src="<?php resource('/js/poem.js'); ?>" ></script>
	<?php
	echo '<script>window.siteUrl = "'. SITE_URL .'";</script>';
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

/**
 * google 跟踪代码
 * @return [type] [description]
 */
function googleAnalytics(){
	if( IS_LOCAL )return;
	?>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-43614813-4', 'auto');
	  ga('send', 'pageview');

	</script>
	<?php
}

/**
 * google ad
 * @return void
 */
function googleAD(){
	if( IS_LOCAL )return;
	?>
	<div class='row' >
		
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- poem -->
<ins class="adsbygoogle"
     style="display:inline-block;width:100%;height:90px"
     data-ad-client="ca-pub-6329536529674735"
     data-ad-slot="1478174006"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
	</div>
	<?php
}

/**
 * 获取本网站下的一个链接
 * @param  [type] $path [description]
 * @return [type]       [description]
 */
function getUrl($path){
	return SITE_URL . $path;
}

/**
 * 输出一个资源的URL
 * @param  [type] $item [description]
 * @return [type]       [description]
 */
function resource( $item ){
	echo getUrl($item);
}

/**
 * 获取一首诗的链接
 * @param  int $id poemId
 * @return string  url
 */
function getPoemURL($id){
	if( REWRITE_ON !== true)
		return getAction("detail&poemId=" . $id);
	return getAction('poem/'.$id);
	
}

/**
 * 获取一个请求url
 * @param  [type] $action [description]
 * @return [type]         [description]
 */
function getAction($action , $param = '' ){
	if( REWRITE_ON !== true ){
		if( $param ) $param = '&' . $param;
		return getUrl('?action=' . $action . $param );
	}

	if( $param ) $param = '?' . $param;
	return getUrl('/' . $action .  $param );
}

/**
 * 获取一首诗的图片
 * @param  object $poem 
 * @return string       url
 */
function getPoemImage($poem){
	$text = substr($poem['content'][0], 0,3);
	return getUrl('/weixin/font.php?text=' . urlencode($text) . '&');
}

/**
 * 发送邮件
 * @param  [type] $title [description]
 * @param  [type] $body  [description]
 * @return [type]        [description]
 */
function sendMail($from , $body){
	//c20524f14
	if( !class_exists('PHPMailer') ){
		require INCLUDE_PATH . "/PHPMailer/PHPMailerAutoload.php";
	}
	
	$kv = new MyKV();
	$mail = new PHPMailer();

	$mail->SMTPDebug = 0;
	$mail->isSMTP();
	$mail->Host = 'smtp.163.com';
	$mail->Port = 25;
	$mail->SMTPAuth = true;
	$mail->CharSet = 'utf-8';

	$mail->Username = $kv->get('email');
	$mail->Password = $kv->get('emailPass');
	$mail->setFrom($kv->get('email'), 'tangPoem');

	$mail->addAddress($kv->get('sendTo'), 'ahah');

	$mail->Subject = '意见反馈【唐诗三百首】';
	$mail->Body = $body . "\r\nfrom : " . $from . "\r\n\r\n" . $_POST['url'];
	return $mail->send();
}

/**
 * 格式化一个数字
 * @param  int $num 
 * @return 
 */
function formatNum( $num ){
	$num *= 1;
	$num = $num ? $num : 0;
	if( $num < 1000 ){
		return $num;
	}else if( $num < 10000 ){
		return number_format($num/1000,2) . 'K';
	}else{
		return number_format($num/10000,2) . 'W';
	}
}