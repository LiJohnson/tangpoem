<?php
session_start();

include ACTION_PATH . '/BaseAction.php';
include CLASS_PATH . '/BaseDao.php';
include DAO_PATH . '/PoemDao.php';
include DAO_PATH . '/AuthorDao.php';
include DAO_PATH . '/GoodDao.php';
include CLASS_PATH . '/MyLogin.php';
/**
 * @author lcs
 * @since 2014-09-20
 * @desc 网站前台action
 */
class TangPoemAction extends BaseAction{
	
	private $poemDao;
	private $goodDao;
	private $author;

	public function __construct(){
		$this->poemDao = new PoemDao();
		$this->authorDao = new AuthorDao();
		$this->goodDao = new GoodDao();
	}

	/**
	 * 简介页面
	 * @return [type] [description]
	 */
	public function info( $data ){
		return $data;
	}

	/**
	 * 目录页面
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

	/**
	 * 诗歌页面
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function detail($data){
		$poem = $this->poemDao->getById($_GET['poemId']);
		if( $poem['poemId'] ){
			$poem['next'] = $this->poemDao->getNext($poem['poemId']);
			$poem['prev'] = $this->poemDao->getPrev($poem['poemId']);
			$poem['good'] = $this->goodDao->get($poem['poemId']);
		}else{
			return $this->redirect(getAction('info'));
		}
		return $poem;
	}

	public function daily(){
		$poem = $this->poemDao->daily();
		$poem['url'] = getPoemURL( $poem['poemId'] );
	//	$poem['page'] = 'detail';
		return $poem;
	}

	/**
	 * 关于页面
	 * @return [type] [description]
	 */
	public function about(){
		return "about";
	}

	/**
	 * good
	 * @return [type] [description]
	 */
	public function good(){
		return array('result' => $this->goodDao->add( $_POST['poemId'] , $_POST['index'] ) );
	}

	/**
	 * 微信页面
	 * @return [type] [description]
	 */
	public function wechat(){
		return array('page' => '../weixin/page');
	}

	/**
	 * 意见反馈
	 * @return [type] [description]
	 */
	public function feedback(){
		$res = false;
		if( $_POST['email'] && $_POST['content'] ){
			$res = sendMail($_POST['email'] , $_POST['content']);
		}
		return array('result' => $res);
	}

	/**
	 * 微博授权
	 * @return [type] [description]
	 */
	public function wbAuth(){
		$login = new MyLogin();
		$login->setDebug();
		$login->login();
		$_SESSION['user'] = $login->getUserInfo();
		return $this->redirect(SITE_URL);
	}

	/**
	 * 退出
	 * @return [type] [description]
	 */
	public function logout(){
		$login = new MyLogin();
		$login->logout();
		return $this->redirect(SITE_URL);
	}

	/**
	 * 404页面
	 * @return [type] [description]
	 */
	public function notFound(){
		return array("page" => "404");
	}

	/**
	 * [stat description]
	 * @return [type] [description]
	 */
	public function stat(){
		$data = array();
		$text = array();
		$kv = new MyKV();
		$stat = $kv->get('stat');
		foreach ( $stat['data'] as $key ) {
			$data[] = array($key['char'] , $key['count']);
			$text[] = $key['char'];
		}
		return array('data' => $data , 'text' =>$text);
	}
}