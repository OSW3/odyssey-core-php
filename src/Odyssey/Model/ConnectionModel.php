<?php

namespace Odyssey\Model;

use \PDO;
use \PDOException;

/**
 * Gère la connexion à la base de données (Singleton Pattern)
 */
class ConnectionModel
{

	private static $dbh;

	/**
	 * Crée une connexion ou la retourne si présente
	 */
	public static function getDbh()
	{
		if(!self::$dbh){
			self::setNewDbh();
		}
		return self::$dbh;
	}

	/**
	 * Crée une nouvelle connexion à la base
	 */
	public static function setNewDbh()
	{
		$app = getApp();

		switch ($app->getConfig('fetch_mode'))
		{
			case 'object':
				$fetch_mode = PDO::FETCH_OBJ;
				break;

			case 'assoc':
				$fetch_mode = PDO::FETCH_ASSOC;
				break;

			case 'array':
			default:
				$fetch_mode = PDO::FETCH_ARRAY;
				break;
		}
		
		try {
			self::$dbh = new PDO('mysql:host='.$app->getConfig('db_host').';dbname='.$app->getConfig('db_name'), $app->getConfig('db_user'), $app->getConfig('db_pass'), array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
				PDO::ATTR_DEFAULT_FETCH_MODE => $fetch_mode,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
			));
		} catch (PDOException $e) {
			echo 'Erreur de connexion : ' . $e->getMessage();
		}
	}

}