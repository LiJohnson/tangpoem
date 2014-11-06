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
				return trim($content);
			}
		}
		return '';//$poem['title'] . '---' . $poem['name'];
	}

	/**
	 * 以图文信息返回
	 * @link http://t.cn/R75vsoY
	 * @param  array $poems 
	 * @return array 
	 */
	private function viewPoemNews($poems,$key){
		$count = count($poems);
		$message = array( 'MsgType' => 'news' , 'ArticleCount' => ( $count >= 10 ? 10 : $count+1 ) , 'Articles' => array() );

		$item = array();
		$item['Title'] = "搜索`$key`,共找到".$count."首";
		$item['Description'] = '唐诗三百首';
		$item['PicUrl'] = 'http://ww1.sinaimg.cn/large/5e22416bgw1ekj73ma4tfj207g0b774j.jpg';
		$item['Url'] = getUrl ('/?action=cate&key='.$key);

		$message['Articles'][]= array('item' => $item );

		foreach ($poems as $poem) {
			$item = array();
			$item['Description'] = $this->getMatchContent($poem,$key);
			$item['Title'] =  $poem['title'] . ' ('.$poem['name'].')' . " " . $item['Description'];
			$item['Url'] = getPoemURL($poem['poemId']);
			$item['PicUrl'] = getPoemImage($poem);
			$message['Articles'][]= array('item' => $item );

			if( count($message['Articles']) == 9 && $count > 9 ){
				$item = array();
				$item['Title'] =  '更多';
				$item['Url'] = getUrl ('/?action=cate&key='.$key);
				$message['Articles'][]= array('item' => $item );

				return $message;
			}
		}
		return $message;
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
			return $this->viewPoemNews($poems ,$key);
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