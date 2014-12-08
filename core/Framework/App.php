<?php namespace Core\Framework;

class App
{
	/**
	 * The URI string
	 * @var String
	 */
	protected $uri;

	/**
	 * Url chunks in the form of an array
	 * @var array
	 */
	protected $uriArray;

	/**
	 * config.php file in app/config directory
	 * @var array
	 */
	protected $config;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->uri = $_SERVER['REQUEST_URI'];
		$this->uriArray = explode('/', filter_var(rtrim($_SERVER['REQUEST_URI'], '/'), FILTER_SANITIZE_URL));
		array_shift($this->uriArray);

		$this->config = require(APP_PATH . 'app/config/config.php');
	}

	/**
	 * Run the app
	 * @return
	 */
	public function run()
	{
		if ($this->config['debug'])
		{
			// Initialize Whoops
			$this->initWhoops();
		} else {
			error_reporting(0);
		}

		if ( isset($this->uriArray[0]) )
		{
			$controller = $this->uriArray[0];

			if ( isset($this->uriArray[1]) )
			{
				$action = $this->uriArray[1];
			} else {
				$action = 'index';
			}

		} else {
			$controller = $this->config['defaultController'];
			$action     = 'index';
		}

		if( sizeof($this->uriArray) > 2 )
		{
			array_shift($this->uriArray);
			array_shift($this->uriArray);
			$params = $this->uriArray;
		} else {
			$params = [];
		}

		$this->handOverToController($controller, $action, $params);
	}

	private function handOverToController( $controllerName, $action, $params = [] )
	{
		if ( $this->controllerExists( $controllerName ) )
		{
			$controllerClassName = ucfirst($controllerName);
			$properNamespacedClass = '\\' . $controllerClassName;
			$controllerInstance = new $properNamespacedClass();
			$response = call_user_func_array([$controllerInstance, $action], $params);
			if ( is_array($response) || is_object($response) )
			{
				echo json_encode($response);
			} else {
				echo $response;
			}
		} else {
			$this->handleNotFound();
		}
	}

	private function initWhoops()
	{
		$whoops = new \Whoops\Run;
		$handler = new \Whoops\Handler\PrettyPageHandler;

		$whoops->pushHandler($handler)->register();
		return $this;
	}

	public function handleNotFound()
	{
		throw new \Exception("404 Error Occured");
		return;
	}

	private function controllerExists( $controllerName )
	{
		if ( file_exists(APP_PATH . 'app/controllers/' . $controllerName . '.php') )
		{
			return true;
		} else {
			return false;
		}
	}
}
