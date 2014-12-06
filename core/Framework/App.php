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
	 * Boot the app
	 * @return
	 */
	public function boot()
	{
		if ($this->config['debug'])
		{
			// Initialize Whoops
			$this->initWhoops();
		} else {
			error_reporting(0);
		}

		// Check if the user is trying to access / route
		if (sizeof($this->uriArray) < 1)
		{
			// Let the default controller@index handle the request
			if ($this->controllerExists($this->config['defaultController']))
			{
				$this->handOverToController($this->config['defaultController'], 'index');
				return;
			} else {
				echo $this->handleNotFound();
				return;
			}
		}

		if (sizeof($this->uriArray) < 2)
		{
			if ($this->controllerExists($this->uriArray[0]))
			{
				$this->handOverToController($this->uriArray[0], 'index');
				return;
			} else {
				echo $this->handleNotFound();
				return;
			}
		}

		if (sizeof($this->uriArray) < 3)
		{
			if ($this->controllerExists($this->uriArray[0]))
			{
				$this->handOverToController($this->uriArray[0], $this->uriArray[1]);
				return;
			} else {
				echo $this->handleNotFound();
				return;
			}
		}

		$controllerName = $this->uriArray[0];
		$actionName = $this->uriArray[1];
		array_shift($this->uriArray);
		array_shift($this->uriArray);
		$params = $this->uriArray;
		if ($this->controllerExists($controllerName))
		{
			$this->handOverToController($controllerName, $actionName, $params);
		} else {
			echo $this->handleNotFound();
			return;
		}
	}

	private function handOverToController( $controllerName, $action, $params = [] )
	{
		$controllerInstance = new \Home;
		echo call_user_func_array([$controllerInstance, $action], $params);
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
