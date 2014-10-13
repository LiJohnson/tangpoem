<?php
session_start();

include ACTION_PATH . '/BaseAction.php';
include CLASS_PATH . '/BaseDao.php';
include DAO_PATH . '/PoemDao.php';
include DAO_PATH . '/AuthorDao.php';
/**
 * @author lcs
 * @since 2014-10-13
 * @desc 微信action
 */
class WeiXinAction extends BaseAction{
	
	private $poemDao;
	private $authorDao;

	public function __construct(){
		$this->poemDao = new PoemDao();
		$this->authorDao = new AuthorDao();
	}

	private function isAuthor( $text ){
		return $this->authorDao->getByName($text);
	}

	public function textMessage($text){
		if($text == "1"){
			return $this->poemDao->getRand();
		}
		$name = $this->isAuthor($text) ? $text : false;
		$poems = $this->poemDao->searchPoem($name , false , $text , 'rand()' );
		if( count($poems) ){
			return $poems[0];
		}
		return $this->poemDao->getRand();
	}
}