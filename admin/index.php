<?php 
include '../config.php';
include INCLUDE_PATH . '/function.php';
include ACTION_PATH . '/AdminAction.php';

$page = $_GET['page'] == 'author' ? 'author' : 'poem';
$action = new AdminAction();
$data = call_user_func(array(&$action , $page));

if( is_array($data) ){
	$page = $data['page'] ? $data['page'] : $page;
}

$nav = array(
	array('page'=> 'poem' , 'title' => '诗'),
	array('page'=> 'author' , 'title' => '作者')
	);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>admin</title>
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