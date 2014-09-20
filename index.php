<?php 
include 'config.php';
include INCLUDE_PATH . '/function.php';
include ACTION_PATH . '/TangPoemAction.php';

$action = $_GET['action'] ? $_GET['action'] : 'info';

$action = preg_replace('/\-/', '_', $action);

$tangPoemAction = new TangPoemAction();
$data = call_user_func(array(&$tangPoemAction , $action) , array('action' => $action));

if($_GET['ajax'] || preg_match('/json/', $_SERVER['HTTP_ACCEPT'])){
	echo json_encode($data);exit;
}

if( is_array($data) ){
	$action = $data['action'] ? $data['action'] : $action;
}

$nav = array(
	array('action'=> 'info' , 'title' => '简介'),
	array('action'=> 'cate' , 'title' => '目录'),
	array('action' => 'about' , 'title' => '关于'),
	array('action' => 'more' , 'title' => '更多')
);

foreach ($nav as $item) {
	if( $action == $item['action'] ){
		$cur = $item;
		break;
	}
}
$cur = $cur ? $cur : $nav[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $cur['title']; ?> | 唐诗三百首</title>
	<?php baseJSCSS(); ?>
</head>
<body id="tang-poem" class="<?php echo $cur['action']; ?>" >
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
					<li><a class="btn btn-default"><i class="glyphicon glyphicon-font"></i></a></li>
				</ul>
			   
			</div>
		</nav>
	</header>

	<div class="container">
		<?php include BASE_PATH . "/$action.php";?>
	</div>
</body>
</html>