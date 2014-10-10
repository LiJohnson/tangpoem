<?php
include ACTION_PATH . '/BaseAction.php';
include CLASS_PATH . '/BaseDao.php';
include CLASS_PATH . '/MyKV.php';
include DAO_PATH . '/PoemDao.php';
include DAO_PATH . '/AuthorDao.php';

/**
 * @author lcs
 * @date 2014-09-19
 * @desc 后台action
 */
class AdminAction extends BaseAction{
	private $poemDao ;
	private $authorDao;
	private $kv;

	public function __construct(){
		$this->poemDao = new PoemDao();
		$this->authorDao = new AuthorDao();
		$this->kv = new MyKV();
	}

	/**
	 * 诗句页面
	 * @return [type] [description]
	 */
	public function poem(){
		$authors = array();
		$types = array();

		foreach ($this->authorDao->getAll() as $author) {
			$authors[] = array('key' => $author['authorId'] , 'value' => $author['name']);
		}
		foreach ($this->poemDao->getAllType() as $poem) {
			$types[] = array( 'key' => $poem['type'] , 'value' => $poem['type'] );
		}

		return array('types' => $types , 'authors' => $authors) ;
	}

	/**
	 * 工具页面
	 * @return [type] [description]
	 */
	public function tool(){
		$keys = array('comment' => '' , 'login' => '');
		foreach ( $keys as $key => $val) {
			if( isset($_POST[$key]) ){
				$this->kv->set($key , $_POST[$key]);
			}
			$keys[$key] = $this->kv->get($key);
		}
		return $keys;
	}


	public function loadPoemList(){
		return $this->poemDao->getAll($_POST['key'] , $_POST['type']);
	}

	public function loadPoem(){
		return $this->poemDao->getById($_POST['poemId'] ? $_POST['poemId'] : $_GET['poemId'] );
	}

	public function addPoem(){
		return $this->poemDao->addPoem();
	}

	public function updatePoem(){
		return array('result' => $this->poemDao->updatePoem($_POST) , 'poemId' => $_POST['poemId']);
	}

	public function deletePoem(){
		return $this->poemDao->delete('poemId = ' . $_POST['poemId']);
	}

	/**
	 * 文件上传（诗的音频）
	 * @return [type] [description]
	 */
	public function upload(){
		include CLASS_PATH . "/MyFileSystem.php";
		$fs = new MyFileSystem( defined("SAE_TMP_PATH")  ? 'wp' : UPLOAD_PATH );

		if( !defined("SAE_TMP_PATH")  ){
			$_FILES['file']['name'] = iconv("UTF-8","gb2312", $_POST['name']);
		}
		
		
		if($fs->upload("/tang-poem")) {
			$url = defined("SAE_TMP_PATH") ? 'http://shit.com' : SITE_URL . "/upload/tang-poem/";
			return array( "url" => $url  . $_POST['name']);
		}
		return false;
	}

	public function author(){
		return 'author';
	}

	/**
	 * 管理用户
	 * @return [type] [description]
	 */
	public function user(){
		if( isset($_POST['adminId']) ){
			$this->kv->set('adminId' , $_POST['adminId']);
		}
		$adminId = $this->kv->get('adminId');
		$adminId = $adminId && count($adminId) ? $adminId : array('');
		
		if( !in_array($this->kv->get("root") , $adminId ) ){
			$adminId[] = $this->kv->get("root");
		}

		return array('adminId' => $adminId);
	}
}
?>