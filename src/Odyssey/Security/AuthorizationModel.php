<?php

namespace Odyssey\Security;

use \Odyssey\Session\SessionModel;
use \Odyssey\Security\AuthentificationModel as Authentification;

/**
 * Gère l'accès aux pages en fonction des droits utilisateurs
 */
class AuthorizationModel
{

	/**
	 * Vérifie les droits d'accès de l'utilisateur en fonction de son rôle
	 * @param  string  	$role Le rôle pour lequel on souhaite vérifier les droits d'accès
	 * @return boolean 	true si droit d'accès, false sinon
	 */
	public function isGranted($roles)
	{
		$app = getApp();
		$rolesProperty = $app->getConfig('security_roles_property');
		$authentification = new Authentification();
		$loggedUser = $authentification->getLoggedUser();

		if (!is_array($roles)) {
			$roles = [$roles];
		}

		if (!$loggedUser){
			$this->redirectToLogin();
		}

		if (!empty($loggedUser[$rolesProperty]) && in_array($loggedUser[$rolesProperty], $roles)){
			return true;
		}

		return false;
	}

	/**
	 * Redirige vers la page de connexion
	 */
	public function redirectToLogin()
	{
		$app = getApp();

		$controller = new \Odyssey\Controller\Controller();
		$controller->redirectToRoute($app->getConfig('security_login_route_name'));
	}

}