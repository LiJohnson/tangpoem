<?php
class PoemDao extends BaseDao{
	public function __construct(){
		$this->setTable("poem");
		parent::__construct();
	}

}
?>