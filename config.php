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
}else{

	define("SITE_URL" , 'http://lcs.com/sae/webbm/2');

	define("MY_DB_HOST" , 'lcs.com');
	define("MY_DB_NAME" , 'poem');
	define("MY_DB_USER" , 'lcs');
	define("MY_DB_PASS" , 'lcs');

}
?>