<?php
/**
 * @author lcs 
 * @since  2014-10-11
 * @desc action父类
 */
class BaseAction{

	/**
	 * 访问重定向
	 * @param  [type] $localtion [description]
	 * @return [type]            [description]
	 */
	public function redirect($localtion){
		header("Location: " . $localtion);
		die();
	}
}
?>