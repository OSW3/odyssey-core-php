<?php

namespace Odyssey\Security;

use Odyssey\Security\StringUtils;
use Odyssey\Model\UsersModel;

class AuthentificationModel
{
	const ROLE_USER = "USER";
	const ROLE_ADMIN = "ADMIN";

	/**
	 * Vérifie qu'une combinaison d'email/username et mot de passe (en clair) sont présents en bdd et valides
	 * @param  string $email Le pseudo ou l'email à test
	 * @param  string $plainPassword Le mot de passe en clair à tester
	 * @return int  0 si invalide, l'identifiant de l'utilisateur si valide
	 */
	public function isValidLoginInfo($email, $plainPassword)
	{

		$app = getApp();
		$securityPasswordProperty = $app->getConfig('security_password_property');
		$securityIdProperty = $app->getConfig('security_id_property');

		$usersModel = new UsersModel();
		$email = strip_tags(trim($email));
		$foundUser = $usersModel->findByEmail($email);


		if(!$foundUser){
			return 0;
		}

		if(password_verify($plainPassword, $foundUser->securityPasswordProperty)){
			return (int) $foundUser->securityIdProperty;
		}

		return 0;
	}

	/**
	 * Connecte un utilisateur
	 * @param  array $user Le tableau contenant les données utilisateur
	 */
	public function logUserIn($user)
	{
		$app = getApp();

		// Retire le mot de passe de la session
		unset($user[$app->getConfig('security_password_property')]);

		$_SESSION['user'] = $user;
	}

	/**
	 * Déconnecte un utilisateur
	 */
	public function logUserOut()
	{
		unset($_SESSION['user']);
	}

	/**
	 * Retourne les données présente en session sur l'utilisateur connecté
	 * @return mixed Le tableau des données utilisateur, null si non présent
	 */
	public function getLoggedUser()
	{
		return (isset($_SESSION['user'])) ? $_SESSION['user'] : null;
	}
	

	/**
	 * Utilise les données utilisateurs présentes en base pour mettre à jour les données en session
	 * @return boolean
	 */
	public function refreshUser()
	{
		$app = getApp();
		$usersModel = new UsersModel();
		$userFromSession = $this->getLoggedUser();
		if ($userFromSession){
			$userFromDb = $usersModel->find($userFromSession[$app->getConfig('security_id_property')]);
			if($userFromDb){
				$this->logUserIn($userFromDb);
				return true;
			}
		}

		return false;
	}

	/**
	 * Créer un hash simple d'un mot de passe en utilisant l'algorithme par défaut
	 * @param  string $plainPassword Le mot de passe en clair à hasher
	 * @return string Le mot de passé hashé ou false si une erreur survient
	 */
	public function hashPassword($plainPassword)
	{
		return password_hash($plainPassword, PASSWORD_DEFAULT);
	}
}