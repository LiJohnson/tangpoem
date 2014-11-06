<?php

/**
 * @author lcs 
 * @since 2014-10-12
 * 微信API
 */
class WeiXinClient{
	private $token;
	private $appId;
	private $appSecret;

	public function WeiXinClient( $token , $appId = null , $appSecret = null ){
		$this->token = $token;
		$this->appId = $appId;
		$this->appSecret = $appSecret;
	}

	/**
	 * 验证消息真实性 
	 * [http://mp.weixin.qq.com/wiki/index.php?title=%E9%AA%8C%E8%AF%81%E6%B6%88%E6%81%AF%E7%9C%9F%E5%AE%9E%E6%80%A7]
	 * @param  array $data 
	 * @return 
	 */
	public function valid ( $data ){
		if( !$data['echostr'] )return false;

		$signature = $data['signature'];

		$tmp = array($this->token, $data['timestamp'], $data['nonce']);
		sort($tmp , SORT_STRING);
		$tmp = implode( $tmp );
		$tmp = sha1( $tmp );
		echo $tmp ==  $data['signature'] ? $data['echostr'] : 'false';
		exit;
	}

	/**
	 * 获取 postData
	 * @return [type] [description]
	 */
	public function getPostData(){
		$postString = $GLOBALS["HTTP_RAW_POST_DATA"];
		$postData = simplexml_load_string( $postString  ,'SimpleXMLElement', LIBXML_NOCDATA);
		
		return $postData;
	}

	/**
	 * 格式化一变量，如果是String的话，就以空格拆分
	 * @param  string/array $type 
	 * @return array
	 */
	private function formatType($type){
		if( is_string($type) ){
			$type = preg_split('/\\s/', $type);
		}
		return is_array($type) ? $type : array();
	}

	/**
	 * 处理特定的消息
	 * @param  [type]  $messageType [description]
	 * @param  Closure $callback    [description]
	 * @return [type]               [description]
	 */
	public function on( $messageType  , Closure $callback ){
		$postData = $this->getPostData();

		$messageType = $this->formatType( $messageType );

		foreach ($messageType as $type) {
			
			if( $postData->MsgType != $type )continue;

			$reply = call_user_func($callback , $postData);
			if( $reply ){
				$this->reply($reply , $postData);
				exit();
			}
		}

		return $this;
	}

	/**
	 * 接收普通消息 / 接收语音识别结果
	 * @param  Closure $callback [description]
	 * @return [type]            [description]
	 */
	public function onNormalMessage( Closure $callback ){
		$messageType = array('text' , 'image' , 'voice' , 'video' , 'location' , 'link');
		return $this->on( $messageType , $callback );
	}

	/**
	 * 接收事件推送
	 * @param  Closure $callback [description]
	 * @return [type]            [description]
	 */
	public function onPushEven( $eventType = array() , Closure $callback  ){
		$eventType = $this->formatType( $eventType );
		return $this->on( 'event' , function($postData) use($callback,$eventType) {
			if( count( $eventType ) == 0 ){
				return call_user_func($callback , $postData);
			}
			foreach ($eventType as $event) {
				if( $postData->Event == $event ){
					return call_user_func($callback , $postData);
				}
			}
		});
	}

	/**
	 * 回复
	 * @param  [type] $replyData [description]
	 * @param  [type] $postData  [description]
	 * @return [type]            [description]
	 */
	private function reply ($replyData , $postData){
		if( is_string($replyData) ){
			$replyData = array( 'Content' => $replyData  );
		}
		$message = array();
		$message['ToUserName'] = $postData->FromUserName;
		$message['FromUserName'] = $postData->ToUserName;
		$message['CreateTime'] = time();
		$message['MsgType'] = 'text';
		//$message['Content'] = '汪 汪 汪';
		echo $this->arrayToXml( array_merge($message ,$replyData ) );
	}

	/**
	 * 将array转为XML
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function arrayToXml($data , $isChild = false ){
		
		if( !is_array($data) )return '' ;

		$xml = $isChild ? '' : '<xml>';	
		foreach ($data as $key => $value) {
			$xml .= is_numeric($key) ? '' : "<$key>";
			if( is_string($value) || is_object($value) ){
				$xml .= sprintf("<![CDATA[%s]]>",$value) ;
			}else if( is_array($value) ){
				$xml .= $this->arrayToXml( $value , true );
			}else{
				$xml .= $value;
			}
			$xml .= is_numeric($key) ? '' : "</$key>";
		}
		$xml .= $isChild ? '' : '</xml>';

		return $xml;
	}
}