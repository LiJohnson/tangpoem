<?php

include dirname(__file__).'/../config.php';
include dirname(__file__).'/WeiXinV2.php';
include INCLUDE_PATH . "/function.php";
include CLASS_PATH . "/MyKV.php";
//include CLASS_PATH . "/MyCurl.php";


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

$w = new WeiXinV2("weixin");
$w->valid($_GET);

$w->on('text',function($postData){
	return  'new text' . '<a href="http://webbm.sinaapp.com/" >网页版</a>';
})->onPushEven( 'subscribe' ,function($postData){
	return '欢迎关注，我们的<a href="http://webbm.sinaapp.com/" >网页版</a>';
})->onPushEven( 'unsubscribe' , function($postData){
	return "就这样，走好";
} )->onNormalMessage(function( $postData ){
	return array('Content' => $postData->MsgType . ' => ' . $postData->Content .' => '. time());
});
?>
<title>
	WEI XIN
</title>
<h1 style='text-align: center;margin-top: 10%;' >HI WEIXIN</h1>
<img src="<?php resource('/img/8cm.jpg');?>" style="display: block;margin: 0px auto;" />

