<?php
/**
 * @author lcs
 * @since 2014-09-19
 * @desc 诗Dao
 */
class PoemDao extends BaseDao{

	private $selectSql = 'SELECT poem.poemId , poem.content, poem.title , poem.type , author.name FROM poem LEFT JOIN author ON poem.authorId = author.authorId WHERE 1=1 ';

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

	/**
	 * 获取诗歌类型
	 * @param  boolean $type 类型
	 * @return array        	
	 */
	public function getAllType( $type = false ){
		$where = '';
		if( $type ){
			$where = "WHERE type LIKE '$type'";
		}
		return $this->getData("SELECT type FROM poem  $where GROUP BY type");
	}

	/**
	 * 查找诗歌
	 * @param  boolean $key  关键字,可为诗句,标题,作者
	 * @param  boolean $type 类型
	 * @return array
	 */
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

	/**
	 * 获取一首诗
	 * @param  int $id id
	 * @param  string $cur 'next':获取$id的下一首,'prev':获取$id的上一首
	 * @return 
	 */
	public function getById( $id , $cur = false ){
		$sql = "SELECT poem.* , author.name FROM poem LEFT JOIN author ON poem.authorId = author.authorId WHERE ";
		
		if( $cur == 'next' ){
			$sql .= ' poemId > ' . $id . ' ORDER BY poemId ASC';
		}else if( $cur == 'prev' ){
			$sql .= ' poemId < ' . $id . ' ORDER BY poemId DeSC';

		}else{
			$sql .= ' poemId = ' . $id ;
		}
		return $id > 0 ? $this->unserPoem($this->getLine($sql)) : false;
	}

	/**
	 * 获取下一首诗
	 * @param  int $id
	 * @return [type]     [description]
	 */
	public function getNext( $id ){
		return $this->getById($id , 'next');
	}

	/**
	 * 获取上一首诗
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function getPrev( $id ){
		return $this->getById($id , 'prev');
	}

	/**
	 * 添加一首诗
	 */
	public function addPoem(){
		return $this->save(array(
			'title' => ' new one',
			'content' => ser(array())
			),'poemId');
	}

	/**
	 * 更新一首诗
	 * @param  array $param 
	 * @return [type]        
	 */
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

	/**
	 * 查找
	 * @param  string $author  作者
	 * @param  string $type    类型
	 * @param  string $key     关键字,可为诗句,标题,作者	
	 * @param  string $orderBy 排序
	 * @return array
	 */
	public function searchPoem( $author , $type , $key , $orderBy = 'name'){
		$sql = $this->selectSql;
		if( $author ){
			$sql .= " AND author.name LIKE '". $author . "'";
		}
		if( $type ){
			$sql .= " AND poem.type LIKE '%". $type ."%'";
		}
		if( $key ){
			$sql .= " AND (poem.title LIKE '%". $key ."%' OR author.name LIKE '%".$key."%' OR poem.content LIKE '%".$key."%' ) " ;
		}

		$sql .= ' ORDER BY CONVERT('.$orderBy.' USING GBK) ';
		
		return $this->unserPoem($this->getData($sql));
	}

	/**
	 * 随机返回一首诗
	 * @return [type]      [description]
	 */
	public function getRand(){
		return $this->unserPoem($this->getLine( $this->selectSql . " ORDER BY rand()"));
	}
}
?>