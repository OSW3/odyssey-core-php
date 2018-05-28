<?php

namespace Odyssey;

class App 
{
	const APP_DIR = './../app/';
	const SRC_DIR = './../src/';
	const WEB_DIR = './../web/';

	protected $config;
	protected $routes;
	protected $router;
	protected $basePath;

	/**
	 * Setup the app
	 */
	public function __construct()
	{
		session_start();

		$this->setConfig();
		$this->setRouting();

		if ('dev' === $this->getConfig('env')) 
		{
			error_reporting(E_ALL);
			ini_set("display_errors", 1);
		}
	}


	// -- Config --------------------------------
	
	/**
	 * Set the configuration
	 * 
	 * @param array $config 
	 */
	private function setConfig()
	{
		// Config directory
		$dir = self::APP_DIR.'config/config/';

		// Config files
		$config_prod = $dir.'config.php';
		$config_dev  = $dir.'config_dev.php';
		$config_test = $dir.'config_test.php';

		// New config is an empty array
		$config = [];

		// Define default config
		$default = [
			'db_host' => 'localhost',
			'db_user' => 'root',
			'db_pass' => '',
			'db_name' => '',
			'db_table_prefix' => '',

			'security_user_table' => 'users',
			'security_id_property' => 'id',
			'security_username_property' => 'username',
			'security_email_property' => 'email',
			'security_password_property' => 'password',
			'security_roles_property' => 'role',
			'security_login_route_name' => 'login',

			'site_name'	=> '',
			'env' => 'dev',

			'routes' => [],
		];

		// Override default config
		if (!file_exists($config_prod)) {
			echo '<p>The configuration file is not found at <code>app/config/config/config.php</code>.</p>';
			die();
		}
		include_once $config_prod;
		$this->config = array_merge($default, $config);

		// Override config for the environnement : dev
		if (file_exists($config_dev)) {
			include_once $config_dev;
			$this->config = array_merge($this->config, $config);
		}

		// Override config for the environnement : test
		if (file_exists($config_test)) {
			include_once $config_test;
			$this->config = array_merge($this->config, $config);
		}
	}

	/**
	 * Retrieve and return the configuration data
	 * 
	 * @param string $key The configuration key
	 * @return mixed The configuration value
	 */
	public function getConfig( $key )
	{
		return (isset($this->config[$key])) ? $this->config[$key] : null;
	}


	// -- Routes --------------------------------

	/**
	 * Set the routing
	 * 
	 * @param array $routes 
	 */
	private function setRouting()
	{
		// Routes directory
		$dir = self::APP_DIR.'config/routes/';

		// Instance of AltoRouter
		$this->router = new \AltoRouter();

		// Config files
		$routes_file = $dir.'routes.php';

		// Define routes
		$this->routes = [];

		// Add front routes
		if (!file_exists($routes_file)) {
			echo '<p>The routes file is not found at <code>app/config/routes/routes.php</code>.</p>';
			die();
		}
		include_once $routes_file;
		$this->routes = array_merge($this->routes, $routes);

		// Add additionnal routes
		if (is_array($this->getConfig('routes'))) {
			foreach ($this->getConfig('routes') as $file) {
				$file = $dir.$file.'.php';

				if (file_exists($file)) {
					include_once $file;
					$this->routes = array_merge($this->routes, $routes);
				}
			}
		}

		// Parse and add routes for AlterRouter
		$routes = [];
		foreach ($this->routes as $route) 
		{
			array_push($routes, [
				isset($route[3]) ? $route[3] : "GET",
				isset($route[1]) ? $route[1] : null,
				isset($route[2]) ? $route[2] : null,
				isset($route[0]) ? $route[0] : null
			]);
		}
		$this->basePath = (empty($_SERVER['BASE'])) ? '' : $_SERVER['BASE'];
		$this->router->setBasePath($this->basePath);
		$this->router->addRoutes($routes);
	}

	/**
	 * Get the router
	 * 
	 * @return \AltoRouter
	 */
	public function getRouter()
	{
		return $this->router;
	}

	/**
	 * Get routes for JavaScript
	 * 
	 * @return array Array of routes
	 */
	public function jsRoutes()
	{
		return $this->routes;
	}

	/**
	 * Retourne le nom de la route actuelle
	 * @return mixed Le nom de la route actuelle depuis \AltoRouter ou le false
	 */
	public function getCurrentRoute()
	{
		$route = $this->getRouter()->match();
		
		if($route){
			return $route['name'];
		}
		else {
			return false;
		}
	}


	// -- Misc ----------------------------------

	/**
	 * Start the app
	 */
	public function run()
	{
		$matcher = new \Odyssey\Router\AltoRouter\Matcher($this->router);
		$matcher->match();
	}


	/**
	 * Retourne la base path
	 * @return string La base path
	 */
	public function getBasePath()
	{
		return $this->basePath;
	}
}