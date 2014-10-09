<?php 
define("BASE_PATH" , dirname(__file__));
define("INCLUDE_PATH" , BASE_PATH . '/include');
define("ADMIN_PATH" , BASE_PATH . '/admin');
define("CLASS_PATH" , INCLUDE_PATH . '/class');
define("DAO_PATH" , INCLUDE_PATH . '/dao');
define("ACTION_PATH" , INCLUDE_PATH . '/action');
define("UPLOAD_PATH" , BASE_PATH . '/upload');

if( defined('SAE_TMP_PATH') ){
	define("SITE_URL" , 'http://webbm.sinaapp.com');
	define("MY_DB_NAME" , false);
	//webbm
	define( "WB_AKEY" , '713047838' );
	define( "WB_SKEY" , 'edb01a4f899c7455436f3605571ac6a1' );
	define('WB_CALLBACL_URL', 'http://webbm.sinaapp.com/?action=wbAuth');
}else{

	define("SITE_URL" , 'http://lcs.com/sae/webbm/2');

	define("MY_DB_HOST" , 'lcs.com');
	define("MY_DB_NAME" , 'poem');
	define("MY_DB_USER" , 'lcs');
	define("MY_DB_PASS" , 'lcs');

	//dev
	define( "WB_AKEY" , '3600693014' );
	define( "WB_SKEY" , '22325d36c32bc46cb553e87afc1b01be' );
	//define('WB_CALLBACL_URL', 'http://127.0.0.1:81/sae/webbm/2/?action=wbAuth');

}


