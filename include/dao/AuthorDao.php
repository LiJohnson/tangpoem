<?php
/**
 * @author lcs
 * @date 2014-09-19
 * @desc author
 */
class AuthorDao extends BaseDao{
	public function __construct(){
		$this->setTable("author");
		parent::__construct();
	}

	public function getAll(){
		return $this->getModelList(array() , 'ORDER BY CONVERT(name using gbk)');
	}
}
?>