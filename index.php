<?php
	require_once("models/NWManagedObjectContext.php");
	spl_autoload_register('apiAutoload');
	function apiAutoload($classname)
	{
	    if (preg_match('/[a-zA-Z]+Controller$/', $classname)) {
	    	if (file_exists(__DIR__ . '/controllers/' . $classname . '.php')) {
	        	include __DIR__ . '/controllers/' . $classname . '.php';
	        	return true;
	    	}
	        return false;
	    } elseif (preg_match('/[a-zA-Z]+Model$/', $classname)) {
	        if (file_exists(__DIR__ . '/models/' . $classname . '.php')) {
		        include __DIR__ . '/models/' . $classname . '.php';
		        return true;
	    	}
	    	return false;
	    } elseif (preg_match('/[a-zA-Z]+View$/', $classname)) {
	        if (file_exists(__DIR__ . '/views/' . $classname . '.php')) {
		        include __DIR__ . '/views/' . $classname . '.php';
		        return true;
	    	}
	    	return false;
	    }
	}

	class Request {
	    public $url_elements;
	    public $verb;
	    public $parameters;
	 
	    public function __construct() {
	        $this->verb = $_SERVER['REQUEST_METHOD'];
	        $this->url_elements = explode('/', $_SERVER['PATH_INFO']);
	        $this->parseIncomingParams();
	        // initialise json as default format
	        $this->format = 'json';
	        if(isset($this->parameters['format'])) {
	            $this->format = $this->parameters['format'];
	        }
	        return true;
	    }
	 
	    public function parseIncomingParams() {
	        $parameters = array();
	 
	        // first of all, pull the GET vars
	        if (isset($_SERVER['QUERY_STRING'])) {
	            parse_str($_SERVER['QUERY_STRING'], $parameters);
	        }
	 
	        // now how about PUT/POST bodies? These override what we got from GET
	        $body = file_get_contents("php://input");
	        $content_type = false;
	        if(isset($_SERVER['CONTENT_TYPE'])) {
	            $content_type = $_SERVER['CONTENT_TYPE'];
	        }
	        switch($content_type) {
	            case "application/json":
	                $body_params = json_decode($body);
	                if($body_params) {
	                    foreach($body_params as $param_name => $param_value) {
	                        $parameters[$param_name] = $param_value;
	                    }
	                }
	                $this->format = "json";
	                break;
	            case "application/x-www-form-urlencoded":
	                parse_str($body, $postvars);
	                foreach($postvars as $field => $value) {
	                    $parameters[$field] = $value;
	                }
	                $this->format = "html";
	                break;
	            default:
	                // we could parse other supported formats here
	                break;
	        }
	        $this->parameters = $parameters;
	    }
	}
	$request = new Request();
	$controller_name = ucfirst($request->url_elements[1]) . 'Controller';
	// echo $controller_name;
	if (class_exists($controller_name)) {
	    $controller = new $controller_name();
	    $action_name = strtolower($request->verb) . 'Action';
	    $result = $controller->$action_name($request);
	    // print_r($result);
	    // echo json_encode($result);

	    $view_name = ucfirst($request->format) . 'View';
	    // echo $view_name;
	    if(class_exists($view_name)) {
	        $view = new $view_name();
	        $view->render($result);
	    }
	    else {
	    	header("HTTP/1.0 400 Invalid Request Format");
	    }
	}
	else {
		header("HTTP/1.0 404 Resource Not Found");
	}

?>