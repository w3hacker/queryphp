<?php
define('ROUTER_DEFAULT_CONTROLLER', 'default');
define('ROUTER_DEFAULT_ACTION', 'index');
 
class Router {
  public $request_uri;
  public $routes;
  public $controller, $controller_name;
  public $action, $id;
  public $params;
  public $route_found = false;
  public $rules=array();
 
  public function __construct() {
	self::getPathInfo();
    $this->request_uri = $_SERVER['PATH_INFO'];
    $this->routes = array();
  }
 
  public function map($rule, $target=array(), $conditions=array()) {
    $this->routes[$rule] = new Route($rule, $this->request_uri, $target, $conditions);
	if($this->routes[$rule]->is_matched) $this->set_route($this->routes[$rule]);
	return $this;
  }
  public function RuleCheck($controller)
  {
    if(isset($this->rules[$controller])) return $this->rules[$controller]; else return false;
  }
  public function start()
  {
	 $paths = explode("/",trim($_SERVER['PATH_INFO'],'/'));
     $controller=array_shift($paths);
     if(!empty($controller)){
	   $rule=$this->RuleCheck($controller);	   
	 }else{
	   if(isset($_GET['controller']))
	   {
		 $controller=$_GET['controller'];
	     $this->controller =$_GET['controller']; 
		 unset($_GET['controller']);		 
	   }
	   if(isset($_GET['action']))
	   {
	     $this->action =$_GET['action']; 
		 unset($_GET['action']);
	   }else{
	     $this->action = ROUTER_DEFAULT_ACTION;
	   }
	 }
	 if($rule){
	     $this->map($rule['rule'],$rule['target'],$rule['conditions']);
		 if(!$this->routes[$rule]->is_matched){
			 $this->controller=$controller;
		     $this->action=array_shift($paths);
		 }else{
		   return $this;
		 }
	 }else{
		 if(empty($controller))
		 {
			 $this->controller = ROUTER_DEFAULT_CONTROLLER;
			 $this->action = ROUTER_DEFAULT_ACTION;
			 $this->id = null;	   
		 }else{
		   	 $this->controller=$controller;
		     if($this->action==null) $this->action=array_shift($paths);
		 }
	 }
	 if(is_numeric($paths[0])) { $this->id=array_shift($paths); $_GET['id']=$this->id; }
	for($i=0;$i<count($paths);$i++)
		 $_GET[$paths[$i]]=$paths[++$i];
	return $this;
  }
  public function setMaps($rules)
  {
    $this->rules=$rules;
	return $this;	
  }
  public function ruleMaps($rulename,$rule=null, $target=array(), $conditions=array())
  {
    if(is_array($rulename))
	{
	  foreach($rulename as $k=>$v)
	  {
	    $this->rules[$k]=$v;
	  }
	}else if($rule==null){
	  $this->rules[$rulename]=array("rule"=>"/".$rulename);
	}else{
	  $this->rules[$rulename]=array("rule"=>$rule,
		                            "target"=>$target,
		                            "conditions"=>$conditions=array());
	}
	return $this;
  }
  private function set_route($route) {
    $this->route_found = true;
    $params = $route->params;
    $this->controller = $params['controller']; unset($params['controller']);
    $this->action = $params['action']; unset($params['action']);
    $this->id = $params['id'];
	if(is_numeric($this->controller)&&$this->id==null){
	  $this->id=$this->controller;
	  $this->controller=null;
      $params['id']=$this->id;
	}
	if(is_numeric($this->action)&&$this->id==null){
	  $this->id=$this->action;
	  $this->action=null;
      $params['id']=$this->id;
	} 
    $this->params = array_merge($params, $_GET);
    $_GET=$this->params;
    if (empty($this->controller)) $this->controller = ROUTER_DEFAULT_CONTROLLER;
    if (empty($this->action)) $this->action = ROUTER_DEFAULT_ACTION;
    if (empty($this->id)) $this->id = null;
 
    $w = explode('_', $this->controller);
    foreach($w as $k => $v) $w[$k] = ucfirst($v);
    $this->controller_name = implode('', $w);
  }
  private function getPathInfo()
  {
        if(!empty($_SERVER['PATH_INFO'])){
            $pathInfo = $_SERVER['PATH_INFO'];
            if(0 === strpos($pathInfo,$_SERVER['SCRIPT_NAME']))
                $path = substr($pathInfo, strlen($_SERVER['SCRIPT_NAME']));
            else
                $path = $pathInfo;
        }elseif(!empty($_SERVER['ORIG_PATH_INFO'])) {
            $pathInfo = $_SERVER['ORIG_PATH_INFO'];
            if(0 === strpos($pathInfo, $_SERVER['SCRIPT_NAME']))
                $path = substr($pathInfo, strlen($_SERVER['SCRIPT_NAME']));
            else
                $path = $pathInfo;
        }elseif (!empty($_SERVER['REDIRECT_PATH_INFO'])){
            $path = $_SERVER['REDIRECT_PATH_INFO'];
        }elseif(!empty($_SERVER["REDIRECT_Url"])){
            $path = $_SERVER["REDIRECT_Url"];
            if(empty($_SERVER['QUERY_STRING']) || $_SERVER['QUERY_STRING'] == $_SERVER["REDIRECT_QUERY_STRING"])
            {
                $parsedUrl = parse_url($_SERVER["REQUEST_URI"]);
                if(!empty($parsedUrl['query'])) {
                    $_SERVER['QUERY_STRING'] = $parsedUrl['query'];
                    parse_str($parsedUrl['query'], $GET);
                    $_GET = array_merge($_GET, $GET);
                    reset($_GET);
                }else {
                    unset($_SERVER['QUERY_STRING']);
                }
                reset($_SERVER);
            }
        }elseif(!empty($_SERVER["REDIRECT_URL"])){
            $path = $_SERVER["REDIRECT_URL"];
            if(empty($_SERVER['QUERY_STRING']) || $_SERVER['QUERY_STRING'] == $_SERVER["REDIRECT_QUERY_STRING"])
            {
                $parsedUrl = parse_url($_SERVER["REQUEST_URI"]);
                if(!empty($parsedUrl['query'])) {
                    $_SERVER['QUERY_STRING'] = $parsedUrl['query'];
                    parse_str($parsedUrl['query'], $GET);
                    $_GET = array_merge($_GET, $GET);
                    reset($_GET);
                }else {
                    unset($_SERVER['QUERY_STRING']);
                }
                reset($_SERVER);
            }
        }
        $_SERVER['PATH_INFO'] = empty($path) ? '/' : $path;
      	return $this;
	} 
}
 
class Route {
  public $is_matched = false;
  public $params;
  public $url;
  private $conditions;
 
  function __construct($url, $request_uri, $target, $conditions) {
    $this->url = $url;
    $this->params = array();
    $this->conditions = $conditions;
    $p_names = array(); $p_values = array();
    preg_match_all('@:([\w]+)@', $url, $p_names, PREG_PATTERN_ORDER);
    $p_names = $p_names[0];
    $url_regex = preg_replace_callback('@:[\w]+@', array($this, 'regex_url'), $url);
    $url_regex .= '/?';
    if (preg_match('@^' . $url_regex . '@', $request_uri, $p_values)) {
      $sub=array_shift($p_values);
	  $sub=substr($request_uri,strlen($sub));  
      foreach($p_names as $index => $value) $this->params[substr($value,1)] = urldecode($p_values[$index]);
	  preg_replace('@(\w+)/([^,\/]+)@e', '$this->params[\'\\1\']="\\2";',$sub);
      foreach($target as $key => $value) $this->params[$key] = $value;
      $this->is_matched = true;
    } 
    unset($p_names); unset($p_values);
  }
 
  function regex_url($matches) {
    $key = str_replace(':', '', $matches[0]);
    if (array_key_exists($key, $this->conditions)) {
      return '('.$this->conditions[$key].')';
    } 
    else {
      return '([a-zA-Z0-9_\+\-%]+)';
    }
  }
}
?>