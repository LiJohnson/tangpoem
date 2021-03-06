<?php 
include __DIR__ . '/config.php';
include INCLUDE_PATH . '/function.php';
include ACTION_PATH . '/TangPoemAction.php';
include CLASS_PATH . '/MyKV.php';
header("Content-Type:text/html; charset=utf-8");
$kv = new MyKV();

$action = $action ? $action : ($_GET['action'] ? $_GET['action'] : 'info');

$page = $action = preg_replace('/\-/', '_', $action);

$tangPoemAction = new TangPoemAction();

if( !method_exists($tangPoemAction , $action)  ){
	header('HTTP/1.1 404 Not Found');
	header("status: 404 Not Found");
	$action="notFound";
}

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
	array('action'=> 'cate' , 'title' => '诗集'),
	array('action' => 'wechat' , 'title' => '微信'),
	array('action' => 'stat' , 'title' => '统计'),
	array('action' => 'about' , 'title' => '关于')
	//array('action' => 'classic' , 'title' => '经典'),
	
);

foreach ($nav as $item) {
	if( $action == $item['action'] ){
		$cur = $item;
		break;
	}
}
$cur = $cur ? $cur : $nav[0];
$title = is_array($data) && $data['title'] ? $data['title'] . ' | ' . $data['name'] : $cur['title'];
?>


<?php
$description = '《唐诗三百首》是一部流传很广的唐诗选集。唐朝（618年—907年）二百九十年间，是中国诗歌发展的黄金时代，云蒸霞蔚，名家辈出，唐诗数量多达五万首。孙琴安《唐诗选本六百种提要·自序》指出，“唐诗选本经大量散佚，至今尚存三百余种。当中最流行而家传户晓的，要算《唐诗三百首》。”《唐诗三百首》选诗范围相当广泛，收录了77家诗，共311首，在数量以杜甫诗数多，有38首、王维诗29首、李白诗27首、李商隐诗22首。是仿《诗经》三百篇（共311篇）之作，从前是家弦户诵的儿童诗教启蒙书，所以比较浅显，读者容易接受，俗话说：“熟读唐诗三百首，不会作诗也会吟。”（原序作：“熟读唐诗三百首，不会吟诗也会吟。”）是中小学生接触中国古典诗歌最好的入门书籍。';
if($content){
	$description = $data['title'] . ' : ' . $data['name'] . ' ; ' . join($data['content'],'') . ' | ' . $description;
}
?>

<!DOCTYPE html>
<html lang="zh" xmlns:wb="http://open.weibo.com/wb">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="唐诗三百首,唐诗,唐诗精选,古诗三百首,唐诗朗读,古诗朗读,在线朗读,分句朗读,续句朗读" />
	<meta name="description" content="<?php echo $description;?>" />

	<title><?php echo $title; ?> | 唐诗三百首</title>
	<?php baseJSCSS(); ?>
	<script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=713047838" type="text/javascript" charset="utf-8"></script>
	
</head>
<body id="tang-poem"  >
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
					<a class="navbar-brand" href="<?=getUrl('/')?>">
						<img src="<?=resource('/img/32.png')?>" class="logo"/>
						唐诗三百首
					</a>
				</div>
				
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-left">
						<?php
						foreach ($nav as $item) {
							$cur = $action == $item['action'] ? 'class=active' : '';
							echo "<li $cur ><a href='".getAction($item['action'])."'>$item[title]</a></li>";
						}
						?>
					</ul>

					<ul class="nav navbar-nav navbar-right" >
						<li>
							<form action="<?=getAction('cate')?>"  class="navbar-form navbar-right poem-search <?=($_GET['key'] ? 'focus' : '') ?>" role="search">
								<input type="hidden" name="action" value="cate" >
								<div class="form-group has-feedback">
									<input type="search" name="key" value="<?=$_GET['key'] ?>" placeholder="标题 作者 内容" class="form-control" autocomplete="false" >
									<i class="btn glyphicon glyphicon-search form-control-feedback"></i>
								</div>
							</form>
						</li>
						<li class="hide" ><a class="btn btn-default"><i class="glyphicon glyphicon-font"></i></a></li>
						<?php 
							$user = getUser();

							if( $user ){
						?>
							<li><a href="http://weibo.com/<?=$user[id]?>" target='_blank' ><?=$user[name]?></a></li>
							
							<?php
							if( checkAdmin() ){
								?>
								<li><a href="<?=getUrl('/admin/')?>" >后台</a></li>
								<?php
							}
							?>

							<li><a href="<?=getAction('logout')?>">退出</a></li>
						<?php }else{ ?>
							<li><a href="<?=getAction('wbAuth')?>">微博登录</a></li>
						<?php } ?>
					</ul>
					</div>
			</div>
		</nav>
	</header>
	
	<div class="container <?php echo $action; ?>" >
		
	<?php
		googleAD();
	?>
		<?php include BASE_PATH . "/view/$page.php";  // var_dump($_SESSION); ?>
	</div>
	<?php
		googleAnalytics();
	?>
	<form class="hide feedback" role="form">
		<div class="form-group">
			<label class="control-label" for="email">邮箱</label>
			<input type="email" check-type="email" name="email" class="form-control" id="email" placeholder="联系邮箱">
		</div>
		<div class="form-group">
			<label class="control-label" for="content">内容</label>
			<textarea class="form-control" check-len="1" name="content" id="content" placeholder="建议/反馈内容" rows="5" ></textarea>
		</div>
	</form>
	<?php include 'footer.php'; ?>
</body>
</html>