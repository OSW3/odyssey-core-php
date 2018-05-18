<?php

namespace Odyssey\Controller;

use Odyssey\Security\AuthentificationModel;
use Odyssey\Security\AuthorizationModel;

class Controller 
{
	const PATH_VIEWS = '../app/resources/views';

	/**
	 * Utils
	 */

	/** 
	 * Flashbag message
	 * @param string $message Message you want to display
	 * @param string $level message level (default, info, success, danger, warning)
	 */
	public function setFlashbag($message, $level = 'info'){

		$allowLevel = ['default', 'info', 'success', 'danger', 'warning'];

		if(!in_array($level, $allowLevel)){
			$level = 'info';
		}

		$_SESSION['setFlashbag'] = [
			'message' 	=> (!isset($message) || empty($message)) ? 'No message defined' : ucfirst($message),
			'level'	 	=> $level,
		];

		return;
	}

	public static function getFlashbag()
	{
		return isset($_SESSION['setFlashbag']) ? $_SESSION['setFlashbag'] : null;
	}

	public function getConfig($key)
	{
		$app = getApp();
		return $app->getConfig($key);
	}



	/**
	 * Rendering
	 */

	/**
	 * Rendering a template
	 * @param string $file of the view
	 * @param array  $data Data you want to transmit to the view
	 */
	public function render($file, array $data=array())
	{
		$app = getApp();

		if (!empty($file)) {

			$parts = explode(':', $file);
	
			// Component
			$component = isset($parts[0]) ? $parts[0] : null;
	
			if (null != $component) {
				$component = ucfirst(str_replace('Component', '', $component));
				$component .= "Component";
			}

			// View Directory
			$directory = isset($parts[1]) ? $parts[1] : null;


			// View File
			$file = isset($parts[2]) ? $parts[2] : null;

			if (null != $file) {
				$file = str_replace('.php', '', $file);
			}


			// View
			$view = (null != $directory) ? $directory.'/'.$file : $file;

			if (null != $component) {
				$view = '../../../src/'.$component.'/Views/'.$view;
			}

		}

		$engine = new \League\Plates\Engine(self::PATH_VIEWS);
		$engine->loadExtension(new \Odyssey\View\Plates\PlatesExtensions());
		$setFlashbag = (isset($_SESSION['setFlashbag']) && !empty($_SESSION['setFlashbag'])) ? (object) $_SESSION['setFlashbag'] : null;

		$engine->addData([
			'user' => $this->getUser(),
			'current_route' => $app->getCurrentRoute(),
			'site_name' => $app->getConfig('site_name'),
			'setFlashbag' => $setFlashbag,
		]);

		if(!empty($this->addDataViews) && is_array($this->addDataViews))
		{
			$engine->addData($this->addDataViews);
		}

		// Affiche le template
		echo $engine->render($view, $data);
		
		// Supprime les messages flash pour qu'ils n'apparaissent qu'une fois
		if(isset($_SESSION['setFlashbag'])) {
			unset($_SESSION['setFlashbag']);
		}
		die();
	}

	/**
	 * Render the 404
	 */
	public function renderNotFound()
	{
		header('HTTP/1.0 404 Not Found');

		$file = self::PATH_VIEWS.'/errors/404.php';
		if(file_exists($file)){
			// $this->render('errors/404');
			$this->render(':Errors:404');
		}
		else {
			die('404');
		}	
	}

	/**
	 * Render the 403
	 */
	public function renderForbidden()
	{
		header('HTTP/1.0 403 Forbidden');

		$file = self::PATH_VIEWS.'/errors/403.php';
		if(file_exists($file)){
			$this->render('errors/403');
		}
		else {
			die('403');
		}
	}

	/**
	 * Redner a JSON response
	 * @param mixed $data Data you want to transmit 
	 */
	public function renderJson($data)
	{
		header('Content-type: application/json');
		$json = json_encode($data, JSON_PRETTY_PRINT);
		if($json){
			die($json);
		}
		else {
			die('JSON encoding Error');
		}
	}



	/**
	 * User and Security Utils
	 */

	/**
	 * Get and Return user info
	 */
	public function getUser()
	{
		$authenticationModel = new AuthentificationModel();
		$user = $authenticationModel->getLoggedUser();
		return $user;
	}

	public static function getCurrentUser($field=null)
	{
		if (!isset($_SESSION['user'])) {
			return false;
		}

		if ($field != null) {
			if (isset($_SESSION['user'][$field])) {
				return $_SESSION['user'][$field];
			} else {
				return null;
			}
		}

		return $_SESSION['user'];
	}



	/**
	 * Route Utils
	 */

	/**
	 * Generate URL by a route name
	 * @param string $route route name
	 * @param array $params Route parameters
	 * @param boolean $absolute Absolute URL if true
	 * @return string URL of the route
	 */
	public static function url($route, $params=array(), $absolute=false)
	{
		$app = getApp();
		$params = (empty($params)) ? array() : $params;
		$router = $app->getRouter();
		$routeUrl = $router->generate($route, $params);
		$url = $routeUrl;

		if($absolute){
			$u = \League\Url\Url::createFromServer($_SERVER);
			$url = $u->getBaseUrl() . $routeUrl;
		}
		return $url;
	}

	/**
	 * Redirect to an URI
	 * @param string $uri
	 */
	public function redirect($uri)
	{
		header("Location: $uri");
		exit;	
	}

	/**
	 * Redirect to a route
	 * @param string $route Route name
	 * @param array $params Route parameters
	 */
	public function redirectToRoute($route, array $params = array())
	{
		$uri = $this->url($route, $params);
		$this->redirect($uri);
	}

	/**
	 * Return current route name
	 * @return string Route name
	 */
	public static function getCurrentRoute()
	{
		$app = getApp();
		return $app->getCurrentRoute();
	}



	/**
	 * Assets Utils
	 */

	/**
	 * Relative path of an asset
	 * @param string $file File name of the asset
	 * @return string Relative path of the asset
	 */
	public static function asset($file)
	{
		$app = getApp();
		$file = ltrim($file, '/');
		return $app->getBasePath() . '/assets/' . $file;
	}

	/**
	 * Relative path of an image
	 * @param string $file File name of the image
	 * @return string Relative path of the image
	 */
	public static function image($file)
	{
		return self::asset('images/'.$file);
	}

	/**
	 * Relative path of a stylesheet
	 * @param string $file File name of the stylesheet
	 * @return string Relative path of the stylesheet
	 */
	public static function css($file)
	{
		return self::asset('css/'.$file);
	}

	/**
	 * Relative path of a script
	 * @param string $file File name of the script
	 * @return string Relative path of the script
	 */
	public static function script($file)
	{
		return self::asset('js/'.$file);
	}

}