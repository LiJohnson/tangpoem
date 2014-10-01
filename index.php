<?php 
include 'config.php';
include INCLUDE_PATH . '/function.php';
include ACTION_PATH . '/TangPoemAction.php';

$action = $_GET['action'] ? $_GET['action'] : 'info';

$page = $action = preg_replace('/\-/', '_', $action);

$tangPoemAction = new TangPoemAction();
$data = call_user_func(array(&$tangPoemAction , $action) , array('action' => $action));

if($_GET['ajax'] || preg_match('/json/', $_SERVER['HTTP_ACCEPT'])){
	echo json_encode($data);exit;
}

if( is_array($data) && $data['page'] ){
	$page = $data['page'];
}

$nav = array(
	array('action'=> 'info' , 'title' => '简介'),
	array('action'=> 'cate' , 'title' => '目录'),
	array('action' => 'about' , 'title' => '关于'),
	array('action' => 'classic' , 'title' => '经典'),
	array('action' => 'test' , 'title' => '测验')
);

foreach ($nav as $item) {
	if( $action == $item['action'] ){
		$cur = $item;
		break;
	}
}
$cur = $cur ? $cur : $nav[0];
$title = is_array($data) && $data['title'] ? $data['title'] : $cur['title'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?> | 唐诗三百首</title>
	<?php baseJSCSS(); ?>
</head>
<body id="tang-poem" class="<?php echo $action; ?>" >
	<header>
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<ul class="nav navbar-nav navbar-left">
					<?php
					foreach ($nav as $item) {
						$cur = $action == $item['action'] ? 'class=active' : '';
						echo "<li $cur ><a href='?action=$item[action]'>$item[title]</a></li>";
					}
					?>
				</ul>

				<ul class="nav navbar-nav navbar-right" >
					<li class="hide" ><a class="btn btn-default"><i class="glyphicon glyphicon-font"></i></a></li>
					<?php 
						$user = getUser();

						if( $user ){
					?>
						<li><a href="http://weibo.com/<?=$user[id]?>" target='_blank' ><?=$user[name]?></a></li>
						<li><a href="?action=logout">退出</a></li>
					<?php }else{ ?>
						<li><a href="?action=wbAuth">微博登录</a></li>
					<?php } ?>
				</ul>
			   
			</div>
		</nav>
	</header>

	<div class="container">
		<?php include BASE_PATH . "/$page.php"; ?>
	</div>
</body>
</html>