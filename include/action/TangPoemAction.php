<?php
session_start();

include ACTION_PATH . '/BaseAction.php';
include CLASS_PATH . '/BaseDao.php';
include DAO_PATH . '/PoemDao.php';
include DAO_PATH . '/AuthorDao.php';
include CLASS_PATH . '/MyLogin.php';
/**
 * @author lcs
 * @date 2014-09-20
 * @desc 网站前台action
 */
class TangPoemAction extends BaseAction{
	
	private $poemDao;
	private $author;

	public function __construct(){
		$this->poemDao = new PoemDao();
		$this->authorDao = new AuthorDao();
	}

	/**
	 * [index description]
	 * @return [type] [description]
	 */
	public function info( $data ){
		return $data;
	}

	/**
	 * [index description]
	 * @return [type] [description]
	 */
	public function cate( $data ){
		$data['groupBy'] = $_GET['groupBy'] ? $_GET['groupBy'] : 'type';
		$data['types'] = array('五言古诗','五言乐府','五言绝句','五言律诗','七言古诗','七言乐府','七言绝句','七言律诗');
		$data['poems'] = $this->poemDao->searchPoem( $_GET['name']  , $_GET['type'] , $_GET['key'],$data['groupBy']);
		$data['groupByName'] = $data['groupBy'] == 'name' ? 'active checked' : '';
		$data['groupByType'] = $data['groupBy'] == 'type' ? 'active checked' : '';
		return $data;
	}

	public function detail($data){
		$poem = $this->poemDao->getById($_GET['poemId']);
		if( $poem['poemId'] ){
			$poem['next'] = $this->poemDao->getNext($poem['poemId']);
			$poem['prev'] = $this->poemDao->getPrev($poem['poemId']);
		}
		return $poem;
	}

	/**
	 * [index description]
	 * @return [type] [description]
	 */
	public function about(){
		return "about";
	}

	/**
	 * [index description]
	 * @return [type] [description]
	 */
	public function classic(){
		return array('page' => 'about');
	}

	/**
	 * [index description]
	 * @return [type] [description]
	 */
	public function test(){
		return array('page' => 'about');
	}

	public function wbAuth(){
		$login = new MyLogin();
		$login->login();
		$_SESSION['user'] = $login->getUserInfo();
		header('Location: ' . SITE_URL);
		die();
	}

	public function logout(){
		$login = new MyLogin();
		$login->logout();
		header('Location: ' . SITE_URL);
		die();
	}
}