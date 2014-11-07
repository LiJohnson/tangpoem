<?php
/**
 * 路由处理
 * @author lcs
 * @since 2014-10-24
 */
class Router{

	private $routeHandle;

	public function __construct( Closure $routeHandle = null ){
		if( $routeHandle == null ){
			$this->routeHandle = function( $route ,$param){
				$match = false;
				foreach ($route as $name) {
					$match = $match || isset($param[$name]);
				}
				return $match;
			};
		}else{
			$this->routeHandle = $routeHandle;
		}
	}
	/**
	 * @param  Closure $cb    [description]
	 * @param  [type]  $param [description]
	 * @return [type]         [description]
	 */
	private function getParam( Closure $cb ,$param , $match ){
		if( !is_array($match) ){ $match = array(); }

		$ref = new ReflectionFunction($cb);
		$params = array();
		$args  = $ref->getParameters();
		foreach ($args as $arg) {
			if( $arg->name == 'param' ){
				$params[] = $param;
			}else if( array_key_exists($arg->name ,  $match) ){
				$params[] = $match[$arg->name];
			}else{
				$params[] = null;
			}
		}
		return $params;
	}

	/**
	 * 接收一个请求
	 * @param  string|array  $route 表单名
	 * @param  Closure $cb   回调
	 * @param  [type]  $param 请求参数(表单)
	 * @return 
	 */
	private function request( $route , Closure $cb , $param ){
		if( !is_array($route) ){
			$route = preg_split('/,/', $route);
		}

		$match = call_user_func($this->routeHandle, $route , $param);
		
		if( $match ){
			$res = call_user_func_array($cb, $this->getParam($cb,$param , $match));
			if( $res === false ){
				exit();
			}
			if( preg_match('/json/', $_SERVER['HTTP_ACCEPT']) ){
				echo json_encode($res);
				exit();
			}
			if( is_string($res) ){
				echo $res;
				exit();
			}
		}
		return $this;
	}
	/**
	 * 接收一个(get|post)请求
	 * @param  string|array  $route 表单名
	 * @param  Closure $cb   回调
	 * @param  [type]  $param 请求参数(表单)
	 * @return 
	 */
	public function http($route , Closure $cb){
		return $this->request( $route , $cb , $_REQUEST );
	}
	/**
	 * 接收一个get请求
	 * @param  string|array  $route 表单名
	 * @param  Closure $cb   回调
	 * @return 
	 */
	public function get($route , Closure $cb){
		return $this->request( $route , $cb , $_GET );
	}
	/**
	 * 接收一个post请求
	 * @param  string|array  $route 表单名
	 * @param  Closure $cb   回调
	 * @return 
	 */
	public function post($route , Closure $cb){
		return $this->request( $route , $cb , $_POST );
	}

	/**
	 * [redirect description]
	 * @param  string $locatin [description]
	 * @return [type]          [description]
	 */
	public function redirect($locatin = ""){
		header('Location: ' . $locatin);
		exit();
	}
}

/**
 * 本网站路由
 * 利用rewrite进行路由
 * @author lcs
 */
class PoemRouter extends Router{
	public function __construct(){
		$requestPath = $this->requestPath();
		parent::__construct(function($routes,$param) use ($requestPath){
			$paths = preg_split('/\//', $requestPath);
			foreach ($routes as $route) {
				if( $requestPath === $route )return true;
				$m = PoemRouter::match( preg_split('/\//', $route) , $paths );
				if( $m ) return $m;
			}
			return false;
		});
	}

	/**
	 * 路由匹配，可匹配变量${var}
	 * @param  array $route 定义的路由
	 * @param  array $path  访问的路由
	 * @return [type]        [description]
	 */
	public static function match( $route , $path ){
		if( count($route) !== count($path) )return false;
		$data = array();

		foreach ($route as $i => $value) {
			if( preg_match('/^\$\{\w+\}$/', $value) ){
				$name = preg_replace('/(^\$\{)|(\}$)/','',$value);
				$data[$name] = $path[$i];
			}else if( $path[$i] == $value ){

			}else{
				return false;
			}
		}
		return $data;
	}

	/**
	 * 获取访问的路径
	 * @return [type] [description]
	 */
	private function requestPath(){
		$scriptName = $_SERVER['SCRIPT_NAME'];
		$baseUrl = preg_replace('/http:\/\/[^\/]+\/?/', '/', SITE_URL);
		$phpPath = substr(preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']), strlen($baseUrl));
		return $this->formatPath($phpPath);
	}

	/**
	 * 格式化访问的路径
	 * @param  [type] $path [description]
	 * @return [type]       [description]
	 */
	private function formatPath( $path){
		$path = trim($path, '/');
		return '/' . $path;
	}
}