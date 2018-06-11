<?php

namespace Odyssey\Model;

/**
 * Le modèle de base à étendre
 */
abstract class Model 
{

	/** @var string $table Le nom de la table */
	protected $table;

	/** @var int $primaryKey Le nom de la clef primaire (défaut id) */
	protected $primaryKey = 'id';

	/** @var \PDO $dbh Connexion à la base de données */
	protected $dbh;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->setTableFromClassName();
		$this->dbh = ConnectionModel::getDbh();
	}

	/**
	 * Define the name of the table
	 * @return Odyssey\Model $this
	 */
	private function setTableFromClassName()
	{
		$app = getApp();

		if(empty($this->table)){
			$className = (new \ReflectionClass($this))->getShortName();
			$tableName = str_replace('Model', '', $className);
			$tableName = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $tableName)), '_');
		}
		else {
			$tableName = $this->table;
		}

		$this->table = $app->getConfig('db_table_prefix') . $tableName;

		return $this;
	}

	/**
	 * Redefine the name of the table 
	 * @param string $table Nom de la table
	 * @return Odyssey\Model $this
	 */
	public function setTable($table)
	{
		$this->table = $table;

		return $this;
	}

	/**
	 * Get the table name
	 * @return string
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * Définit le nom de la clef primaire
	 * @param string $primaryKey Nom de la clef primaire de la table
	 * @return Odyssey\Model $this
	 */
	public function setPrimaryKey($primaryKey)
	{
		$this->primaryKey = $primaryKey;

		return $this;
	}

	/**
	 * Retourne le nom de la clef primaire
	 * @return string Le nom de la clef primaire
	 */
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}



	/**
	 * Retrieve an item by his ID
     * @param integer id
     * @param boolean deepQuery
	 * @return mixed Query results
	 */
	public function find($params = array())
	{
		$id = $this->setId($params);
		$deepQuery = $this->setDeepQuery($params);

		$sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey .'  = :id LIMIT 1';
		$sth = $this->dbh->prepare($sql);
		$sth->bindValue(':id', $id);
		$sth->execute();

		if ($deepQuery) {
			return $this->deepQuery($sth->fetch());
		} else {
			return $sth->fetch();
		}
	}

    /**
     * Retrieve next item of an ID
     * @param integer id
     * @param boolean deepQuery
     * @return mixed Query results
     */
	public function findNext($params = array())
	{
		$id = $this->setId($params);
		$deepQuery = $this->setDeepQuery($params);

        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey .'  = (SELECT MIN(id) FROM ' . $this->table . ' WHERE id > :id ) LIMIT 1';
        $sth = $this->dbh->prepare($sql);
        $sth->bindValue(':id', $id);
        $sth->execute();

        $result = $sth->fetch();
        if(!$result) {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey .'  = (SELECT MIN(id) FROM ' . $this->table . ') LIMIT 1';
            $sth = $this->dbh->prepare($sql);
            $sth->execute();
            $result = $sth->fetch();
        }

		if ($deepQuery) {
			return $this->deepQuery($result);
		} else {
			return $result;
		}
    }

    /**
     * Retrieve previous item of an ID
     * @param integer id
     * @param boolean deepQuery
     * @return mixed Query results
     */
	public function findPrevious($params = array())
	{
		$id = $this->setId($params);
		$deepQuery = $this->setDeepQuery($params);

        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey .'  = (SELECT MAX(id) FROM ' . $this->table . ' WHERE id < :id ) LIMIT 1';
        $sth = $this->dbh->prepare($sql);
        $sth->bindValue(':id', $id);
        $sth->execute();

        $result = $sth->fetch();
        if(!$result) {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey .'  = (SELECT MAX(id) FROM ' . $this->table . ') LIMIT 1';
            $sth = $this->dbh->prepare($sql);
            $sth->execute();
            $result = $sth->fetch();
        }

		if ($deepQuery) {
			return $this->deepQuery($result);
		} else {
			return $result;
		}
    }

	/**
	 * Récupère toutes les lignes de la table
	 * @param $orderBy La colonne en fonction de laquelle trier
	 * @param $orderDir La direction du tri, ASC ou DESC
	 * @param $limit Le nombre maximum de résultat à récupérer
	 * @param $offset La position à partir de laquelle récupérer les résultats
     * @param boolean deepQuery
	 * @return array Query results
	 */
	public function findAll($params = array())
	{
		$orderBy  	= $this->setOrderBy($params);
		$orderDir 	= $this->setOrderDir($params);
		$limit 	  	= $this->setLimit($params);
		$offset   	= $this->setOffset($params);
		$deepQuery	= $this->setDeepQuery($params);
		
		$sql = 'SELECT * FROM ' . $this->table;

		if (!empty($orderBy))
		{
			$sql .= ' ORDER BY '.$orderBy.' '.$orderDir;
		}

		if($limit)
		{
			$sql .= ' LIMIT '.$limit;
			if($offset)
			{
				$sql .= ' OFFSET '.$offset;
			}
		}

		$sth = $this->dbh->prepare($sql);
		$sth->execute();

		if ($deepQuery) {
			return $this->deepQuery($sth->fetchAll());
		} else {
			return $sth->fetchAll();
		}
	}

	/**
	 * Retrieve an item by a specific column name
	 * @param string $column La colonne
	 * @param string $value La valeur à rechercher
     * @param boolean deepQuery
	 * @return array Query results
	 */
	public function findBy($params = array())
	{
		$column = $this->setColumn($params);
		$value = $this->setValue($params);
		$deepQuery = $this->setDeepQuery($params);

		$sql = 'SELECT * FROM ' . $this->table . ' WHERE `' . $column . '` = :value LIMIT 1';
		$sth = $this->dbh->prepare($sql);
		$sth->bindValue(':value', $value);
		$sth->execute();

		if ($deepQuery) {
			return $this->deepQuery($sth->fetch());
		} else {
			return $sth->fetch();
		}
	}

	/**
	 * Récupère toutes les lignes de la table en fonction d'une colonne et sa valeur
	 * @param $column La colonne
	 * @param $value La valeur à rechercher	 
	 * @param $orderBy La colonne en fonction de laquelle trier
	 * @param $orderDir La direction du tri, ASC ou DESC
	 * @param $limit Le nombre maximum de résultat à récupérer
	 * @param $offset La position à partir de laquelle récupérer les résultats
     * @param boolean deepQuery
	 * @return array Query results
	 */
	public function findAllBy($params = array())
	{
		$column 	= $this->setColumn($params);
		$value 		= $this->setValue($params);
		$orderBy  	= $this->setOrderBy($params);
		$orderDir 	= $this->setOrderDir($params);
		$limit 	  	= $this->setLimit($params);
		$offset   	= $this->setOffset($params);
		$deepQuery	= $this->setDeepQuery($params);

		$sql = 'SELECT * FROM ' . $this->table. ' WHERE `' . $column . '` = :value';

		if (!empty($orderBy))
		{
			$sql.= ' ORDER BY '.$orderBy.' '.$orderDir;
		}

		if($limit){
			$sql.= ' LIMIT '.$limit;
			if($offset)
			{
				$sql.= ' OFFSET '.$offset;
			}
		}

		$sth = $this->dbh->prepare($sql);
		$sth->bindValue(':value', $value);
		$sth->execute();

		if ($deepQuery) {
			return $this->deepQuery($sth->fetchAll());
		} else {
			return $sth->fetchAll();
		}
	}

	/**
	 * Effectue une recherche
	 * @param array $data Un tableau associatif des valeurs à rechercher
	 * @param string $operator La direction du tri, AND ou OR
	 * @param boolean $stripTags Active le strip_tags automatique sur toutes les valeurs
	 * @return mixed false si erreur, le résultat de la recherche sinon
	 */
	public function search(array $search, $params = array())
	{
		$operator = $this->setOperator($params);
		$stripTags = $this->setStripTags($params);

        $sql = 'SELECT * FROM ' . $this->table.' WHERE';
                
		foreach($search as $key => $value){
			$sql .= " `$key` LIKE :$key ";
			$sql .= $operator;
		}
		
		if($operator == 'OR') {
			$sql = substr($sql, 0, -3);
		}
		elseif($operator == 'AND') {
			$sql = substr($sql, 0, -4);
		}

		$sth = $this->dbh->prepare($sql);

		foreach($search as $key => $value){
			$value = ($stripTags) ? strip_tags($value) : $value;
			$sth->bindValue(':'.$key, '%'.$value.'%');
		}
		if(!$sth->execute()){
			return false;
		}		

		if ($deepQuery) {
			return $this->deepQuery($sth->fetchAll());
		} else {
			return $sth->fetchAll();
		}
	}


	/**
	 * Efface une ligne en fonction de son identifiant
	 * @param mixed $id L'identifiant de la ligne à effacer
	 * @return mixed La valeur de retour de la méthode execute()
	 */
	public function delete($id)
	{
		if (!is_numeric($id)){
			return false;
		}

		$sql = 'DELETE FROM ' . $this->table . ' WHERE ' . $this->primaryKey .' = :id LIMIT 1';
		$sth = $this->dbh->prepare($sql);
		$sth->bindValue(':id', $id);
		return $sth->execute();
	}

	/**
	 * Ajoute une ligne
	 * @param array $data Un tableau associatif de valeurs à insérer
	 * @param boolean $stripTags Active le strip_tags automatique sur toutes les valeurs
	 * @return mixed false si erreur, les données insérées mise à jour sinon
	 */
	public function insert($data, $stripTags = true)
	{
		$data = (array) $data;

		$colNames = array_keys($data);
		$colNamesEscapes = $this->escapeKeys($colNames);
		$colNamesString = implode(', ', $colNamesEscapes);

		$sql = 'INSERT INTO ' . $this->table . ' (' . $colNamesString . ') VALUES (';
		foreach($data as $key => $value){
			$sql .= ":$key, ";
		}
		// Supprime les caractères superflus en fin de requète
		$sql = substr($sql, 0, -2);
		$sql .= ')';

		$sth = $this->dbh->prepare($sql);
		foreach($data as $key => $value){
			if(is_int($value)){
				$sth->bindValue(':'.$key, $value, \PDO::PARAM_INT);
			}
			elseif(is_null($value)){
				$sth->bindValue(':'.$key, $value, \PDO::PARAM_NULL);
			}
			else {
				$sth->bindValue(':'.$key, ($stripTags) ? strip_tags($value) : $value, \PDO::PARAM_STR);
			}
		}

		if (!$sth->execute()){
			return false;
		}

		return $this->find([
			"id" => $this->lastInsertId(),
			"deepQuery" => false
		]);
	}

	/**
	 * Insert data if row don't exist
	 * @param array $input Un tableau associatif de valeurs à insérer
	 * @param array $onColumn Check if data exist with a specific column
	 * @param boolean $stripTags Active le strip_tags automatique sur toutes les valeurs
	 * @return mixed false si erreur, les données insérées mise à jour sinon
	 */
	public function smartInsert($input, $onColumn, $stripTags = true)
	{
		// Check if data exists
		$data = $this->findBy([
			"column" => $onColumn,
			"value" => $input->$onColumn
		]);

		// Insert if not exists
		if (!$data) {
			$data = $this->insert($input, $stripTags);
		}

		return $data;
	}

	/**
	 * Modifie une ligne en fonction d'un identifiant
	 * @param array $data Un tableau associatif de valeurs à insérer
	 * @param mixed $id L'identifiant de la ligne à modifier
	 * @param boolean $stripTags Active le strip_tags automatique sur toutes les valeurs
	 * @return mixed false si erreur, les données mises à jour sinon
	 */
	public function update($data, $id, $stripTags = true)
	{
		$data = (array) $data;

		if (!is_numeric($id)){
			return false;
		}
		
		$sql = 'UPDATE ' . $this->table . ' SET ';
		foreach($data as $key => $value){
			$sql .= "`$key` = :$key, ";
		}
		// Supprime les caractères superflus en fin de requète
		$sql = substr($sql, 0, -2);
		$sql .= ' WHERE ' . $this->primaryKey .' = :id';

		$sth = $this->dbh->prepare($sql);
		foreach($data as $key => $value){
			if(is_int($value)){
				$sth->bindValue(':'.$key, $value, \PDO::PARAM_INT);
			}
			elseif(is_null($value)){
				$sth->bindValue(':'.$key, $value, \PDO::PARAM_NULL);
			}
			else {
				$sth->bindValue(':'.$key, ($stripTags) ? strip_tags($value) : $value, \PDO::PARAM_STR);
			}
		}
		$sth->bindValue(':id', $id);

		if(!$sth->execute()){
			return false;
		}

		return $this->find([
			"id" => $id,
			"deepQuery" => false
		]);
	}

	/**
	 * Retourne l'identifiant de la dernière ligne insérée
	 * @return int L'identifiant
	 */
	protected function lastInsertId()
	{
		return $this->dbh->lastInsertId();
	}

	/**
	 * Echappe les clés d'un tableau pour les mots clés réservés par SQL
	 * @param array $datas Une tableau de clé
	 * @return Les clés échappées
	 */
	private function escapeKeys($datas)
	{
		return array_map(function($val){
			return '`'.$val.'`';
		}, $datas);
	}

	private function deepQuery($data)
	{
		$return_array = true;
		$oldTable = $this->getTable();

		if (!is_array($data) ) {
			$data = [$data];
			$return_array = false;
		}

		foreach ($data as $index => $item) {
			if (is_object($item)) {
				foreach ($item as $key => $value) {
					$re = "/_id$/i";
					if (preg_match($re, $key)) {
						$table = preg_replace($re, "", $key);
						if (null != $value) {
							$this->settable($table.'s');
							$data[$index]->$table = $this->find($value);;
						} else {
							$data[$index]->$table = null;
						}
	
						unset($item->$key);
					}
				}
			}
		}

		$this->setTable($oldTable);
		return $return_array ? $data : $data[0];
	}


	// Set Parameters

	private function setId($params)
	{
		$id = null;

		if (!is_array($params)) {
			$params = ["id" => $params];
		}

		if (isset($params['id'])) {
			$id = $params['id'];
		}

		if (!is_numeric($id)){
			return false;
		}

		return $id;
	}
	private function setColumn($params)
	{
		$column = '';

		if (isset($params['column'])) {
			$column = $params['column'];
		}

		if(empty($column)){
			return false;
		}

		return $column;
	}
	private function setValue($params)
	{
		$value = '';

		if (isset($params['value'])) {
			$value = $params['value'];
		}

		return $value;
	}
	private function setOrderBy($params)
	{
		$orderBy = '';

		if (isset($params['orderBy'])) {
			$orderBy = $params['orderBy'];
			
			if(!preg_match('#^[a-zA-Z0-9_$]+$#', $orderBy)) {
				die('Error: invalid orderBy param');
			}
		}

		return $orderBy;
	}
	private function setOrderDir($params)
	{
		$orderDir = 'ASC';

		if (isset($params['orderDir'])) {
			$orderDir = $params['orderDir'];
		}

		$orderDir = strtoupper($orderDir);

		if($orderDir != 'ASC' && $orderDir != 'DESC'){
			die('Error: invalid orderDir param');
		}

		return $orderDir;
	}
	private function setLimit($params)
	{
		$limit = null;

		if (isset($params['limit'])) {
			$limit = $params['limit'];
		}
		
		if ($limit && !is_int($limit)){
			die('Error: invalid limit param');
		}
		
		return $limit;
	}
	private function setOffset($params)
	{
		$offset = null;

		if (isset($params['offset'])) {
			$offset = $params['offset'];
		}
		
		if ($offset && !is_int($offset)){
			die('Error: invalid offset param');
		}

		return $offset;
	}
	private function setDeepQuery($params)
	{
		$deepQuery = true;

		if (isset($params['deepQuery'])) {
			$deepQuery = $params['deepQuery'];
		}

		if($deepQuery !== true && $deepQuery !== false){
			die('Error: invalid deepQuery param');
		}

		return $deepQuery;
	}
	private function setOperator($params)
	{
		$operator = 'OR';

		if (isset($params['operator'])) {
			$operator = $params['operator'];
		}

		$operator = strtoupper($operator);

		if($operator != 'OR' && $operator != 'AND'){
			die('Error: invalid operator param');
		}

		return $operator;
	}
	private function setStripTags($params)
	{
		$stripTags = true;

		if (isset($params['stripTags'])) {
			$stripTags = $params['stripTags'];
		}

		if($stripTags !== true && $stripTags !== false){
			die('Error: invalid stripTags param');
		}

		return $stripTags;
	}
}