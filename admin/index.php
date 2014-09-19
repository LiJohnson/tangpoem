<?php 
include '../config.php';
include INCLUDE_PATH . '/function.php';
include CLASS_PATH . '/BaseDao.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>admin</title>
	<?php baseJSCSS(); ?>
</head>
<body>
	<header>
		<nav class="collapse navbar-collapse bs-navbar-collapse" >
			<b class="navbar-brand btn" >后台</b>
			<ul class="nav navbar-nav">
				<li><a href="?page=poem">诗</a></li>
				<li><a href="?page=author">作者</a></li>
			</ul>
		</nav>
	</header>
	<?php 
	$page = $_GET['page'] == 'author' ? 'author' : 'poem';
	include ADMIN_PATH . '/' . $page . '.php';
	?>
</body>
</html>