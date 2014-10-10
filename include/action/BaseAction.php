<?php 
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