<?php 
define("BASE_PATH" , dirname(__file__));
define("INCLUDE_PATH" , BASE_PATH . '/include');
define("ADMIN_PATH" , BASE_PATH . '/admin');
define("CLASS_PATH" , INCLUDE_PATH . '/class');
define("DAO_PATH" , INCLUDE_PATH . '/dao');
define("ACTION_PATH" , INCLUDE_PATH . '/action');
define("UPLOAD_PATH" , BASE_PATH . '/upload');
define("IS_LOCAL" , !defined('SAE_TMP_PATH') );

//webbm
define( "WB_AKEY" , '713047838' );
define( "WB_SKEY" , 'edb01a4f899c7455436f3605571ac6a1' );

if( !IS_LOCAL ){
	define("SITE_URL" , 'http://webbm.sinaapp.com');
	define("MY_DB_NAME" , false);
}else{

	define("SITE_URL" , 'http://lcs.com/sae/webbm/2');

	define("MY_DB_HOST" , 'lcs.com');
	define("MY_DB_NAME" , 'poem');
	define("MY_DB_USER" , 'lcs');
	define("MY_DB_PASS" , 'lcs');

	define("MY_KV_FILE" , dirname(__file__).'/data.kv');
}