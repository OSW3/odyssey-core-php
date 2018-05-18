<?php

namespace Odyssey;

class App 
{
	protected $config;
	protected $router;
	protected $basePath;

	/**
	 * Setup the app
	 */
	public function __construct()
	{
		global $config, $routes;

		session_start();

		$this->setConfig( $config );
		$this->setRouting( $routes );

		if ($this->getConfig('mode') === 'dev') 
		{
			error_reporting(E_ALL);
			ini_set("display_errors", 1);
		}
	}

	/**
	 * Set the routing
	 * 
	 * @param array $routes 
	 */
	private function setRouting( $routes )
	{
		$ar_routes = array();

		$this->router = new \AltoRouter();

		foreach ($routes as $route) 
		{
			array_push($ar_routes, [
				isset($route[3]) ? $route[3] : "GET",
				isset($route[1]) ? $route[1] : null,
				isset($route[2]) ? $route[2] : null,
				isset($route[0]) ? $route[0] : null
			]);
		}

		$this->basePath = (empty($_SERVER['BASE'])) ? '' : $_SERVER['BASE'];
		$this->router->setBasePath($this->basePath);
		$this->router->addRoutes($ar_routes);
	}

	/**
	 * Set the configuration
	 * 
	 * @param array $config 
	 */
	private function setConfig( $config )
	{
		$this->config = array_merge([
			'db_host' => 'localhost',
			'db_user' => 'root',
			'db_pass' => '',
			'db_name' => '',
			'db_table_prefix' => '',
			'env' => 'dev',
			'security_user_table' => 'users',
			'security_id_property' => 'id',
			'security_username_property' => 'username',
			'security_email_property' => 'email',
			'security_password_property' => 'password',
			'security_roles_property' => 'role',
			'security_login_route_name' => 'login',
			'site_name'	=> '',
		], $config);
	}

	/**
	 * Retrieve and return the configuration data
	 * 
	 * @param string $key The configuration key
	 * @return mixed The configuration value
	 */
	public function getConfig($key)
	{
		return (isset($this->config[$key])) ? $this->config[$key] : null;
	}

	/**
	 * Start the app
	 */
	public function run()
	{
		$matcher = new \Odyssey\Router\AltoRouter\Matcher($this->router);
		$matcher->match();
	}

	/**
	 * Get the router
	 * 
	 * @return \AltoRouter Le routeur
	 */
	public function getRouter()
	{
		return $this->router;
	}

	public function jsRoutes()
	{
		global $routes;
		return $routes;
	}

	/**
	 * Retourne la base path
	 * @return  string La base path
	 */
	public function getBasePath()
	{
		return $this->basePath;
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
}