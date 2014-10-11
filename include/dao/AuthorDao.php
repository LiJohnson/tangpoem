<?php
/**
 * @author lcs
 * @since 2014-09-19
 * @desc author
 */
class AuthorDao extends BaseDao{
	public function __construct(){
		$this->setTable("author");
		parent::__construct();
	}

	/**
	 * 获取作者
	 * @return [type] [description]
	 */
	public function getAll(){
		return $this->getModelList(array() , 'ORDER BY CONVERT(name using gbk)');
	}
}
?>