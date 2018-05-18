<?php

namespace Odyssey\Router\AltoRouter;

class Matcher 
{

	/**
	 * Cherche une correspondance entre l'URL et les routes, et appelle la méthode appropriée
	 */
	public function match()
	{
		$router = getApp()->getRouter();
		$match = $router->match();

		if ($match){

			$parts = explode(':', $match['target']);

			// Component
			$component = isset($parts[0]) ? $parts[0] : null;

			if (null != $component) {
				$component = ucfirst(str_replace('Component', '', $component));
				$component .= "Component";
			}

			// Controller
			$controller = isset($parts[1]) ? $parts[1] : null;

			if (null != $controller) {
				$controller = ucfirst(str_replace('Controller', '', $controller));
				$controller .= "Controller";
			}

			$controller = $component.'\\Controller\\'.$controller;

			// Action
			$action = isset($parts[2]) ? $parts[2] : null;

			if (null != $action) {
				$action = ucfirst(str_replace('Action', '', $action));
				$action .= "Action";
			}

			call_user_func_array(array(new $controller(), $action), $match['params']);
			
		}
		//404
		else {
			$controller = new \Odyssey\Controller\Controller();
			$controller->renderNotFound();
		}

	}

}