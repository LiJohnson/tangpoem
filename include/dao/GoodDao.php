<?php
/**
 * 诗句喜欢数
 * @author lcs
 * @since 2014-10-27
 */
class GoodDao extends BaseDao{
	public function __construct(){
		$this->setTable("good");
		parent::__construct();
	}

	/**
	 * 对一句诗进行加一
	 * @param int $poemId 
	 * @param int $index 
	 */	
	public function add( $poemId , $index ){
		$con = compact('poemId' , 'index');
		$good = $this->getOne( $con );
		if( !$good ){
			$good = $this->save( $con , 'goodId' );
		}

		return $this->update(array('count' => $good['count']+1) , 'goodId = ' . $good['goodId'] );
	}

	/**
	 * 获得一首诗的喜欢统计
	 * @param  int $poemId 
	 * @return 
	 */
	public function get( $poemId ){
		$goods = $this->getList(array('goodId' => $goodId));
		if( !is_array($goods) ){
			return array('poemId' => $poemId);
		}

		foreach ($goods as $good) {
			$result[$good['index']] = $good['count'];
		}
		$result['poemId'] = $poemId;

		return $result;
	}
}
?>