<?php
/**
 * Gestionnaire de base de données.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class db
{
	/**
	 * Objet PDO.
	 *
	 * @var object
	 */
	public $connexion;

	/**
	 * lastInsertId des transactions.
	 *
	 * @var array
	 */
	public $lastInsertId;

	/**
	 * Tableau associatif lastInsertId => paramètres
	 * pour les transactions avec requêtes préparées.
	 *
	 * @var array
	 */
	public $lastInsertIdParams;

	/**
	 * Message d'erreur à afficher en cas d'échec d'une requête.
	 * La valeur de cette propriété ne doit jamais être testée pour savoir si
	 * une erreur est survenue. Pour cela il faut utiliser la valeur de retour
	 * des méthodes disponibles ou celle de la propriété $nbResult.
	 * 
	 * @var string
	 */
	public $msgError;

	/**
	 * Message à afficher en cas d'échec.
	 *
	 * @var string
	 */
	public $msgFailure;

	/**
	 * Nombre de lignes affectées par une requête.
	 *
	 * @var int|array
	 */
	public $nbResult;

	/**
	 * Paramètres des requêtes préparées.
	 * Alternative à l'argument du même nom pour la méthode execute().
	 *
	 * @var array
	 */
	public $params;

	/**
	 * Ensemble des requêtes effectuées.
	 *
	 * @var array
	 */
	public $queries;

	/**
	 * Tableau de résultat d'une requête.
	 *
	 * @var array
	 */
	public $queryResult;

	/**
	 * Requête SQL.
	 * Alternative à l'argument du même nom pour la méthode exec().
	 *
	 * @var string|array
	 */
	public $sql;

	/**
	 * Objet PDOStatement.
	 *
	 * @var object
	 */
	public $statement;



	/**
	 * Paramètre pour aider à déterminer l'emplacement
	 * (numéro de ligne dans un fichier) de la requête.
	 *
	 * @var integer
	 */
	private $_origin;

	/**
	 * Timestamp du début de la requête.
	 *
	 * @var integer
	 */
	private $_time;

	/**
	 * Indique si une transaction est en cours.
	 *
	 * @var integer
	 */
	private $_transactionActive;



	/**
	 * Connexion à la base de données.
	 *
	 * @param string $dsn
	 * @param string $user
	 * @param string $pass
	 * @return void
	 */
	public function __construct($dsn = CONF_DB_DSN, $user = CONF_DB_USER, $pass = CONF_DB_PASS)
	{
		try
		{
			// http://bugs.php.net/47224
			$options = (defined('PDO::MYSQL_ATTR_INIT_COMMAND'))
				? array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8')
				: NULL;

			$this->connexion = new PDO($dsn, $user, $pass, $options);
			$this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connexion->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);

			if ($options === NULL)
			{
				$this->exec('SET NAMES UTF8');
			}
		}
		catch (PDOException $e)
		{
			$this->_exception($e);
		}
	}

	/**
	 * Fermeture de la connexion à la base de données.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		$this->connexion = NULL;
	}

	/**
	 * Exécute la transaction courante.
	 *
	 * @return boolean
	 */
	public function commit()
	{
		try
		{
			if ($this->_transactionActive)
			{
				$this->connexion->commit();
				$this->_transactionActive = FALSE;
			}

			return TRUE;
		}
		catch (PDOException $e)
		{
			return $this->_exception($e);
		}
	}

	/**
	 * Exécute une requête simple, une transaction
	 * ou une transaction avec requête préparée.
	 *
	 * @param null|string|array $sql
	 * @param boolean $transaction
	 * @param integer $o
	 * @return boolean
	 */
	public function exec($sql = NULL, $transaction = TRUE, $o = 0)
	{
		$this->_init($o);

		if ($sql === NULL)
		{
			$sql =& $this->sql;
		}
		else
		{
			$this->sql =& $sql;
		}
		try
		{
			// Transactions.
			if (is_array($sql))
			{
				if ($transaction && $this->transaction() === FALSE)
				{
					return FALSE;
				}
				$this->nbResult = array();

				// Transaction avec requêtes préparées.
				if (is_array($sql[0]))
				{
					for ($i = 0, $count_i = count($sql); $i < $count_i; $i++)
					{
						$statement = $this->connexion->prepare($sql[$i]['sql']);
						$this->nbResult[$i] = array();
						for ($n = 0, $count_n = count($sql[$i]['params']); $n < $count_n; $n++)
						{
							$statement->execute($sql[$i]['params'][$n]);
							$this->nbResult[$i][$n] = $statement->rowCount();
							$this->lastInsertId[$i][$n] = $this->connexion->lastInsertId();
							$this->lastInsertIdParams[$this->connexion->lastInsertId()]
								= $sql[$i]['params'][$n];
						}
					}
				}

				// Transaction simple.
				else
				{
					for ($i = 0, $count = count($sql); $i < $count; $i++)
					{
						$this->nbResult[$i] = $this->connexion->exec($sql[$i]);
						$this->lastInsertId[$i] = $this->connexion->lastInsertId();
					}
				}
				if ($transaction && $this->commit() === FALSE)
				{
					return FALSE;
				}
			}

			// Requête simple.
			else
			{
				$this->nbResult = $this->connexion->exec($sql);
			}

			return $this->_end(TRUE);
		}
		catch (PDOException $e)
		{
			return $this->_exception($e);
		}
	}

	/**
	 * Exécute une requête préparée
	 * de mise à jour de la base de données.
	 *
	 * @param null|array $params
	 *	Paramètres de la requête préparée.
	 * @return boolean
	 */
	public function executeExec($params = NULL)
	{
		return $this->_execute($params, 'count', NULL);
	}

	/**
	 * Exécute une requête préparée
	 * de récupération de données.
	 *
	 * @param null|array $params
	 *	Paramètres de la requête préparée.
	 * @return boolean
	 */
	public function executeQuery($params = NULL, $fetch_style = PDO::FETCH_BOTH)
	{
		return $this->_execute($params, 'fetch', $fetch_style);
	}

	/**
	 * Retourne le nombre total de lignes affectées.
	 *
	 * @return integer
	 */
	public function nbResult()
	{
		if (is_array($this->nbResult))
		{
			$count = create_function(
				'&$arr = 0, $key = 0',
				'static $a = 0; if (is_int($arr)) { $a += $arr; } return $a;'
			);
			array_walk_recursive($this->nbResult, $count);
			return $count();
		}
		else
		{
			return $this->nbResult;
		}
	}

	/**
	 * Exécute une requête SQL.
	 *
	 * @param string $sql
	 *	Requête SQL.
	 * @param mixed $fetch_style
	 *	Méthode de récupération des données.
	 * @param integer $o
	 * @return boolean
	 */
	public function query($sql, $fetch_style = PDO::FETCH_BOTH, $o = 0)
	{
		$this->_init($o);

		$this->sql =& $sql;
		try
		{
			$this->statement = $this->connexion->query($sql);
			$this->_result($fetch_style);

			return $this->_end(TRUE);
		}
		catch (PDOException $e)
		{
			return $this->_exception($e);
		}
	}

	/**
	 * Prépare une requête préparée.
	 *
	 * @param string $sql
	 *	Requête SQL.
	 * @param integer $o
	 * @return boolean
	 */
	public function prepare($sql, $o = 0)
	{
		$this->_init($o);

		$this->sql =& $sql;
		try
		{
			$this->statement = $this->connexion->prepare($sql);

			return TRUE;
		}
		catch (PDOException $e)
		{
			return $this->_exception($e);
		}
	}

	/**
	 * Effectue un rollBack sur la transaction en cours.
	 *
	 * @return void
	 */
	public function rollBack()
	{
		try
		{
			if ($this->_transactionActive)
			{
				$this->connexion->rollBack();
				$this->_transactionActive = FALSE;
			}
		}
		catch (PDOException $e)
		{
			$this->_exception($e, TRUE, FALSE);
		}
	}

	/**
	 * Démarre une transaction.
	 *
	 * @return boolean
	 */
	public function transaction()
	{
		try
		{
			if (!$this->_transactionActive)
			{
				$this->connexion->beginTransaction();
				$this->_transactionActive = TRUE;
			}

			return TRUE;
		}
		catch (PDOException $e)
		{
			return $this->_exception($e);
		}
	}



	/**
	 * Exécute une requête préparée et récupère le résultat.
	 *
	 * @param null|array $params
	 *	Paramètres de la requête préparée.
	 * @param string $result
	 *	Type de résultat : 'count' ou 'fetch'.
	 * @param mixed $fetch_style
	 *	Méthode de récupération des données.
	 * @return boolean
	 */
	private function _execute($params, $result, $fetch_style)
	{
		$this->_origin++;

		try
		{
			if ($params === NULL)
			{
				foreach ($this->params as $k => &$v)
				{
					$this->statement->bindValue($k, $v);
				}
			}
			else
			{
				$this->params =& $params;
			}
			$this->statement->execute($params);
			switch ($result)
			{
				case 'count' :
					$this->nbResult = $this->statement->rowCount();
					break;

				case 'fetch' :
					$this->_result($fetch_style);
					break;
			}

			return $this->_end(TRUE);
		}
		catch (PDOException $e)
		{
			return $this->_exception($e);
		}
	}

	/**
	 * Formatage du résultat.
	 *
	 * @param mixed $fetch_style
	 *	Méthode de récupération des données.
	 * @return void
	 */
	private function _result($fetch_style)
	{
		if (is_int($fetch_style))
		{
			$this->queryResult = $this->statement->fetchAll($fetch_style);
			$this->nbResult = count($this->queryResult);
			return;
		}
		if (is_array($fetch_style))
		{
			$this->queryResult = array();

			// Tableau associatif entre les valeurs de deux colonnes.
			if (is_array($fetch_style['column']))
			{
				while ($row = $this->statement->fetch())
				{
					$this->queryResult[$row[$fetch_style['column'][0]]]
						= $row[$fetch_style['column'][1]];
				}
			}

			// Tableau associatif entre la valeur d'une colonne
			// et celles de toutes les colonnes.
			else
			{
				while ($row = $this->statement->fetch($fetch_style['fetch']))
				{
					$this->queryResult[$row[$fetch_style['column']]] = $row;
				}
			}

			$this->nbResult = count($this->queryResult);
			return;
		}
		if (is_string($fetch_style))
		{
			switch ($fetch_style)
			{
				case 'value' :
					$row = $this->statement->fetch();
					break;
				case 'row' :
					$row = $this->statement->fetchAll(PDO::FETCH_ASSOC);
					break;
			}
			if (isset($row[0]))
			{
				$this->queryResult = $row[0];
			}
			$this->nbResult = $this->statement->rowCount();
			return;
		}
	}

	/**
	 * Réinitialise les paramètres pour une prochaine requête.
	 *
	 * @param integer $o
	 * @return void
	 */
	private function _init($o = 0)
	{
		$this->_origin = $o;
		$this->_time = microtime(TRUE);
		$this->lastInsertId = array();
		$this->lastInsertIdParams = array();
		$this->nbResult = NULL;
		$this->queryResult = NULL;
		$this->msgError = $this->msgFailure . "\n" . 'Wrong number of affected rows.';
	}

	/**
	 * Gestion des exceptions.
	 *
	 * @param object $e
	 *	Objet PDOException.
	 * @param boolean $details
	 * @param boolean $rollback
	 * @return boolean
	 */
	private function _exception($e, $details = TRUE, $rollback = TRUE)
	{
		$this->msgError = $this->msgFailure . "\n" . $e->getMessage();
		if ($rollback)
		{
			$this->rollBack();
		}
		$additional = array(
			'sql' => $this->sql,
			'params' => $this->params
		);
		error::dbError($e, $additional, $details);

		return $this->_end(FALSE, $e);
	}

	/**
	 * Traitements de fin de requête.
	 *
	 * @param boolean $return
	 * @param null|object $e Objet PDOException
	 * @return boolean
	 */
	private function _end($return, $e = NULL)
	{
		// Temps d'exécution de la requête.
		$time = microtime(TRUE) - $this->_time;

		// Informations de débogage.
		$debug_backtrace = debug_backtrace();
		$r = ($return) ? 'SUCCESS' : 'FAILURE';
		$file = ($return)
			? $debug_backtrace[1 + $this->_origin]['file']
			: $debug_backtrace[2 + $this->_origin]['file'];
		$line = ($return)
			? $debug_backtrace[1 + $this->_origin]['line']
			: $debug_backtrace[2 + $this->_origin]['line'];
		$this->queries[] = array(
			'sql' => $this->sql,
			'params' => $this->params,
			'result' => $r,
			'nb_result' => $this->nbResult,
			'exception' => $e,
			'time' => $time,
			'file' => $file,
			'line' => $line
		);

		// Réinitialisation des propriétés de requête.
		$this->sql = NULL;
		$this->params = NULL;

		return $return;
	}
}
?>