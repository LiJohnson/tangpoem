<?php

include dirname(__file__).'/../config.php';
include dirname(__file__).'/WeiXinClient.php';
include INCLUDE_PATH . "/function.php";
include CLASS_PATH . "/MyKV.php";
include ACTION_PATH  . '/WeiXinAction.php';

header("Content-Type:text/html; charset=utf-8");

$weixinAction = new WeiXinAction();

function llog( $data = null ){
	$kv = new MyKV();
	if( $data == null ){
		return $kv->get("weixin");
	}else{
		try{
			$kv->set("weixin",$data);
		}
		catch (Exception $e){
			$kv->set("weixin",$data."");
		}
	}
}


if( isset($_GET['log']) ){
	 var_dump(llog());
	 exit();
}

$w = new WeiXinClient(""/*TOKEN*/);
$w->valid($_GET);

$w->on('text',function($postData) use($weixinAction){
	return $weixinAction->textMessage($postData->Content);
})->on('image voice',function($postData){
	//测试超链接
	//return "共找到8首\n1、<a href='http://lcs.com/sae/webbm/2/?action=detail&poemId=79'>兵车s\n (杜甫) 生女犹得嫁比邻，生男埋没随百草。</a>\n  2、<a href='http://lcs.com/sae/webbm/2/?action=detail&poemId=62'>八月十五夜赠张功曹 (韩愈) 洞庭连天九疑高，蛟龙出没猩鼯号。</a>\n  3、<a href='http://lcs.com/sae/webbm/2/?action=detail&poemId=64'>石鼓歌 (韩愈) 日销月铄就埋没，六年西顾空吟哦。</a>\n  4、<a href='http://lcs.com/sae/webbm/2/?action=detail&poemId=48'>庐山谣寄卢侍御虚舟 (李白) 闲窥石镜清我心，谢公行处苍苔没。</a>\n  5、<a href='http://lcs.com/sae/webbm/2/?action=detail&poemId=128'>饯别王十一南游 (刘长卿) 飞鸟没何处，青山空向人。</a>\n  6、<a href='http://lcs.com/sae/webbm/2/?action=detail&poemId=249'>塞下曲·其二 (卢纶) 平明寻白羽，没在石棱中。</a>\n  7、<a href='http://lcs.com/sae/webbm/2/?action=detail&poemId=35'>塞下曲·其二 (王昌龄) 平沙日未没，黯黯见临洮。</a>\n  8、<a href='http://lcs.com/sae/webbm/2/?action=detail&poemId=143'>没蕃故人 (张藉) 前年戌月支，城下没全师。</a>\n";
})->onPushEven( 'subscribe' ,function($postData){
	return '欢迎关注<a href="http://webbm.sinaapp.com/" >唐诗三百首</a>，俗话说：“熟读唐诗三百首，不会作诗也会吟。”,我们将为您提供唐诗原文，朗读，翻译，赏析等服务;' . "\n\n" . $weixinAction->textMessage('help');
})->onPushEven( 'unsubscribe' , function($postData){
	return "就这样，走好";
} )->onNormalMessage(function( $postData ){
	return array('Content' => $postData->MsgType . ' => ' . $postData->Content .' => '. time());
});

include __DIR__.'/page.php';