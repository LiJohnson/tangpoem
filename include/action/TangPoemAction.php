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
		$data['poems'] = $this->poemDao->searchPoem( $_GET['author']  , $_GET['type'] , $_GET['key']);
		$data['authors'] = $this->authorDao->getAll();
		return $data;
	}

	public function detail($data){
		return $this->poemDao->getById($_GET['poemId']);
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