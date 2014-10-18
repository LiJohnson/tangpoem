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

		if( strlen($text[0]) < 1 || $text[0] == '?' || $text[0] == '？' || $text[0] == 'help' || $text[0] == '帮助' ){
			return $this->viewHelp();
		}
		
		return $this->viewPoem($text[0]);
	}

	/**
	 * 获取关键字所在的诗句
	 * @param  [type] $poem [description]
	 * @param  [type] $key  [description]
	 * @return [type]       [description]
	 */
	private function getMatchContent( $poem , $key ){
		foreach ($poem['content'] as $content) {
			//var_dump(strpos( $content , $key));
			if( $content && strpos( $content , $key) !== false ){
				return "\n".trim($content);
			}
		}
		return '';
	}

	/**
	 * 诗歌内容
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	private function viewPoem( $key ){
		$name  = $this->isAuthor($key) ? $key : false;
		$poems = $this->poemDao->searchPoem($name , false , $key );

		$content = array();
		$count = count($poems);
		if( $count > 1 ){
			$content[] = "共找到".$count."首";
			foreach ($poems as $i => $poem) {
				$content[] = ($i+1) . "、<a href='".getPoemURL($poem['poemId'])."'>$poem[title] ($poem[name]) ".  ( $count < 10 ? $this->getMatchContent($poem,$key) : '')."</a>  ";
			}
		}else{
			if( $count == 1 ){
				$poem = $poems[0];
			}else{
				$content[] = "都不知道你要找什么,随便来一首吧";
				$poem = $this->poemDao->getRand();
			}
			$content[] = "【$poem[title] --$poem[name]】";
			$content[] = join($poem['content'],"\n");
			$content[] = "<a href='".getPoemURL($poem['poemId'])."' >详情</a>";
		}

		return join($content , "\n");
	}

	/**
	 * 回复诗人内容
	 * @param  [type] $authorList [description]
	 * @return [type]             [description]
	 */
	private function viewAuthor($authorList){
		if( count($authorList) == 0 ){
			return "没有要的诗人";
		}
		$content = '';
		foreach ($authorList as $author) {
			$content .= '<a href="'.SITE_URL.'/?action=cate&name='.$author['name'].'">' . $author['name'] . '</a> ';
		}
		return $content;
	}

	/**
	 * 帮助信息
	 * @return [type] [description]
	 */
	private function viewHelp(){
		$content[] = '发送[help]或[帮助],可查看帮助文档';
		$content[] = '发送[诗人 xxx],可查找相关的诗人';
		$content[] = '发送[xxx],返回相应的唐诗';
		$content[] = '*以上的【xxx】表示任意内容';
		return join($content, "\n");
	}
}