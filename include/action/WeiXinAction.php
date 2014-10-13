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

	/**
	 * 判断是否为作者名字
	 * @param  [type]  $text [description]
	 * @return boolean       [description]
	 */
	private function isAuthor( $text ){
		return $this->authorDao->getByName($text);
	}

	/**
	 * 处理用户消息
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	public function textMessage($text){
		$text = preg_split('/\s/', $text);
		
		if( $text[0] == '诗人' ){
			return $this->viewAuthor($this->authorDao->searchAuthor($text[1]));
		}
		if( $text[0] == 'help' || $text[0] == '帮助' ){
			return $this->viewHelp();
		}

		$name = $this->isAuthor($text[0]) ? $text[0] : false;
		$poems = $this->poemDao->searchPoem($name , false , $text[0] , 'rand()' );
		
		$poem = count($poems) ? $poems[0] : $this->poemDao->getRand();
		return $this->viewPoem($poem);
	}

	private function viewPoem($poem){
		$content = array("【$poem[title] --$poem[name]】");
		$content[] = join($poem['content'],"\n");
		$content[] = "<a href='".getPoemURL($poem['poemId'])."' >详情</a>";
		return join($content , "\n");
	}
	private function viewAuthor($authorList){
		if( count($authorList) == 0 ){
			return "没有要的";
		}
		$content = '';
		foreach ($authorList as $author) {
			$content .= '<a href="'.SITE_URL.'/?action=cate&name='.$author['name'].'">' . $author['name'] . '</a> ';
		}
		return $content;
	}

	private function viewHelp(){
		$content[] = '发送[help]或[帮助],可查看帮助文档';
		$content[] = '发送[诗人 xxx],可查找相关的诗人';
		$content[] = '发送[xxx],返回相应的唐诗';
		$content[] = '*以上的【xxx】表示任意内容';
		return join($content, "\n");
	}
}

$commands = array(

);