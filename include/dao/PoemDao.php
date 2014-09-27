<?php
/**
 * @author lcs
 * @date 2014-09-19
 * @desc 诗Dao
 */
class PoemDao extends BaseDao{

	private $selectSql = 'SELECT poem.poemId , poem.content, poem.title , author.name FROM poem LEFT JOIN author ON poem.authorId = author.authorId WHERE 1=1 ';

	public function __construct(){
		$this->setTable("poem");
		parent::__construct();
	}

	/**
	 * 反序列化诗对象
	 * @param  array $list [description]
	 * @return array       [description]
	 */
	private function unserPoem($list){
		if( !is_array($list)  )return $list;

		if( $list['content'] ) {
			$list['content'] = unser($list['content']);
			if( $list['info'] ){
				$list['info'] = unser($list['info']);
			}
			return $list;
		}

		foreach ($list as $k => $poem) {
			$list[$k]['content'] = unser($poem['content']);
			if( $poem['info'] ){
				$list[$k]['info'] = unser($poem['info']);
			}
		}
		return $list;
	}

	public function getAllType( $type = false ){
		$where = '';
		if( $type ){
			$where = "WHERE type LIKE '$type'";
		}
		return $this->getData("SELECT type FROM poem  $where GROUP BY type");
	}

	public function getAll( $key = false , $type = false ){
		$sql = $this->selectSql;
		if( $key ){
			$sql .= " AND ( poem.content LIKE '%$key%' OR poem.title LIKE '%$key%' OR author.name LIKE '%$key%') ";
		}
		if( $type && $type != 'all' && $this->getAllType($type) ){
			$sql .= " AND poem.type LIKE '$type'";
		}
		return $this->unserPoem($this->getData($sql));
	}

	public function getById( $id ){
		$sql = "SELECT poem.* , author.name FROM poem LEFT JOIN author ON poem.authorId = author.authorId WHERE poemId = " . $id;
		//echo $sql;
		return $id > 0 ? $this->unserPoem($this->getLine($sql)) : false;
	}

	public function addPoem(){
		return $this->save(array(
			'title' => ' new one',
			'content' => ser(array())
			),'poemId');
	}

	public function updatePoem($param){
		if( !$this->getById($param['poemId']) )return false;

		return $this->update(array(
			'title' => $param['title'],
			'content' => ser( preg_split('/\\n/', $param['content']) ),
			'audio' => $param['audio'],
			'info' => ser(array(
				'note' => $param['note'],
				'comment' => $param['comment'],
				'url' => $param['url'],
				'rhymed' => $param['rhymed'],
				'audioIndex' => $param['audioIndex']
				)),
			'time' => date("Y-m-d G:i:s")
			), 'poemId = ' . $param['poemId']);	
	}

	public function searchPoem( $author , $type , $key ){
		$sql = $this->selectSql;
		if( $author ){
			$sql .= " AND author.name LIKE '". $author . "'";
		}
		if( $type && count($type)){
			foreach ($type as $val) {
				$sql .= " AND poem.type LIKE '%". $val ."%'";	
			}
		}
		if( $key ){
			$sql .= " AND (poem.title LIKE '%". $key ."%' OR poem.content LIKE '%".$key."%' ) " ;
		}
		return $this->unserPoem($this->getData($sql));
	}
}
?>