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

	/**
	 * 通过作者名获取
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function getByName($name){
		return $this->getOne(array('name' => $name));
	}
}
?>