<?php 
include '../config.php';
include INCLUDE_PATH . '/function.php';
include ACTION_PATH . '/AdminAction.php';

$page = $_GET['page'] ? $_GET['page'] : ($_GET['action'] ? $_GET['action'] : 'poem');
$page = preg_replace('/\-/', '_', $page);

$action = new AdminAction();
$data = call_user_func(array(&$action , $page));

if($_GET['ajax'] || preg_match('/json/', $_SERVER['HTTP_ACCEPT'])){
	echo json_encode($data);exit;
}

if( is_array($data) ){
	$page = $data['page'] ? $data['page'] : $page;
	extract($data);
}

$nav = array(
	array('page'=> 'poem' , 'title' => '诗'),
	array('page'=> 'author' , 'title' => '作者'),
	array('page'=> 'tool' , 'title' => '工具')
	);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $page ;?> | admin</title>
	<?php baseJSCSS(); ?>
</head>
<body id="admin">
	<header>
		<nav class="collapse navbar-collapse bs-navbar-collapse" >
			<b class="navbar-brand btn" >后台</b>
			<ul class="nav navbar-nav">
				<?php
				foreach ($nav as $n) {
					echo "<li class=".( $page == $n['page'] ? 'active' : '' )." ><a href='?page=$n[page]' >$n[title]</a></li>";
				}
				?>
			</ul>
		</nav>
	</header>
	<?php
	include ADMIN_PATH . '/' . $page . '.php';
	?>
</body>
</html>