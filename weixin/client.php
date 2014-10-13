<?php

include dirname(__file__).'/../config.php';
include dirname(__file__).'/WeiXinClient.php';
include INCLUDE_PATH . "/function.php";
include CLASS_PATH . "/MyKV.php";
include ACTION_PATH  . '/WeiXinAction.php';

header("Content-Type:text/html; charset=utf-8");
//include CLASS_PATH . "/MyCurl.php";

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
	$poem = $weixinAction->textMessage($postData->Content);
	$content = array("【$poem[title] --$poem[name]】");
	$content[] = join($poem['content'],"\n");
	$content[] = "<a href='".getPoemURL($poem['poemId'])."' >详情</a>";
	return join($content , "\n");
})->onPushEven( 'subscribe' ,function($postData){
	return '欢迎关注<a href="http://webbm.sinaapp.com/" >唐诗三百首</a>，俗话说：“熟读唐诗三百首，不会作诗也会吟。”,我们将为您提供唐诗原文，朗读，翻译，赏析等报务;<a href="http://webbm.sinaapp.com/?action=cate" >更多请到</a>';
})->onPushEven( 'unsubscribe' , function($postData){
	return "就这样，走好";
} )->onNormalMessage(function( $postData ){
	return array('Content' => $postData->MsgType . ' => ' . $postData->Content .' => '. time());
});

include __DIR__.'/page.php';