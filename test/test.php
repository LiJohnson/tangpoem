<?php

class A{
	private $p;
	function aa(){
		$this->p  = "pp";

		$test = "asdf";
		$this->b(function(){
			global $test;
			var_dump($this->p);
		});
	}
	function b($f){
		call_user_func($f);
	}
	function c(){}

}
$a = new A();$a->aa();
exit();

$str = '<xml><ToUserName><![CDATA[gh_0b265a7bce22]]></ToUserName><FromUserName><![CDATA[o95J-jjwMOqmj9DbFg-HhPNPIJmc]]></FromUserName><CreateTime>1413109928</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[.]]></Content><MsgId>6069260926613639150</MsgId>webwx_msg_cli_ver_0x1</xml>';
$GLOBALS["HTTP_RAW_POST_DATA"] = $str;
require '../weixin/index.php';