<?php
include ACTION_PATH . '/BaseAction.php';
include CLASS_PATH . '/BaseDao.php';
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

	public function __construct(){
		$this->poemDao = new PoemDao();
		$this->authorDao = new AuthorDao();
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

	public function author(){
			return 'author';
		}
}
?>