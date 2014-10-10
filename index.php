<?php 
include 'config.php';
include INCLUDE_PATH . '/function.php';
include ACTION_PATH . '/TangPoemAction.php';
include CLASS_PATH . '/MyKV.php';
$kv = new MyKV();

$action = $_GET['action'] ? $_GET['action'] : 'info';

$page = $action = preg_replace('/\-/', '_', $action);

$tangPoemAction = new TangPoemAction();

$data = call_user_func(array(&$tangPoemAction , $action) , array('action' => $action));

if( is_array($data) )extract($data);


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


<?php
$description = '《唐诗三百首》是一部流传很广的唐诗选集。唐朝（618年—907年）二百九十年间，是中国诗歌发展的黄金时代，云蒸霞蔚，名家辈出，唐诗数量多达五万首。孙琴安《唐诗选本六百种提要·自序》指出，“唐诗选本经大量散佚，至今尚存三百余种。当中最流行而家传户晓的，要算《唐诗三百首》。”《唐诗三百首》选诗范围相当广泛，收录了77家诗，共311首，在数量以杜甫诗数多，有38首、王维诗29首、李白诗27首、李商隐诗22首。是仿《诗经》三百篇（共311篇）之作，从前是家弦户诵的儿童诗教启蒙书，所以比较浅显，读者容易接受，俗话说：“熟读唐诗三百首，不会作诗也会吟。”（原序作：“熟读唐诗三百首，不会吟诗也会吟。”）是中小学生接触中国古典诗歌最好的入门书籍。';
if($content){
	$description = $data['title'] . ' : ' . $data['name'] . ' ; ' . join($data['content'],'') . ' | ' . $description;
}
?>

<!DOCTYPE html>
<html lang="en" xmlns:wb="http://open.weibo.com/wb">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?> | 唐诗三百首</title>
	<?php baseJSCSS(); ?>

	<meta name="keywords" content="唐诗三百首,唐诗,唐诗精选,古诗三百首,唐诗朗读,古诗朗读,在线朗读,分句朗读,续句,朗读" />
	<meta name="description" content="<?php echo $description;?>" />

</head>
<body id="tang-poem" class="<?php echo $action; ?>" >
	<header>
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">唐诗三百首</a>
				</div>
				
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
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
							
							<?php
							if( checkAdmin() ){
								?>
								<li><a href="admin/" >后台</a></li>
								<?php
							}
							?>

							<li><a href="?action=logout">退出</a></li>
						<?php }else{ ?>
							<li><a href="?action=wbAuth">微博登录</a></li>
						<?php } ?>
					</ul>
					</div>
			</div>
		</nav>
	</header>

	<div class="container">
		<?php include BASE_PATH . "/$page.php";  // var_dump($_SESSION); ?>
	</div>
</body>
</html>