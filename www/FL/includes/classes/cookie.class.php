<?php
/**
 * Gestionnaire de cookies.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class cookie
{
	/**
	 * Durée après laquelle le cookie expire, en secondes.
	 *
	 * @var integer
	 */
	public $expire;

	/**
	 * Contenu du cookie.
	 *
	 * @var array
	 */
	public $value = array();



	/**
	 * Nom du cookie.
	 *
	 * @var string
	 */
	private $_name;

	/**
	 * Chemin de la galerie.
	 *
	 * @var string
	 */
	private $_path;



	/**
	 * Initialisation et récupération du contenu du cookie.
	 *
	 * @param string $name
	 * @param integer $expire
	 * @param string $path
	 * @return void
	 */
	public function __construct($name, $expire = 31536000, $path = '')
	{
		$this->_name = $name;
		$this->expire = ($expire) ? time() + $expire : 0;
		$this->_path = ($path == '/') ? '/' : $path . '/';

		if (isset($_COOKIE[$name]) && utils::isSerializedArray($_COOKIE[$name]))
		{
			$this->value = $_COOKIE[$name] = unserialize($_COOKIE[$name]);
		}
	}

	/**
	 * Valeurs à ajouter au cookie.
	 *
	 * @param string $k
	 * @param string $v
	 * @return void
	 */
	public function add($k, $v)
	{
		$this->value[$k] = $v;
	}

	/**
	 * Valeurs à effacer du cookie.
	 *
	 * @param string $k
	 * @return void
	 */
	public function delete($k = NULL)
	{
		if ($k)
		{
			unset($this->value[$k]);
		}
		else
		{
			$this->value = array();
		}
	}

	/**
	 * Lecture d'une valeur contenue dans le cookie.
	 *
	 * @param string $k
	 * @return string|boolean
	 */
	public function read($k)
	{
		return array_key_exists($k, $this->value) ? $this->value[$k] : FALSE;
	}

	/**
	 * Écriture du cookie.
	 *
	 * @return boolean
	 */
	public function write()
	{
		// On écrit uniquement des tableaux.
		if (!is_array($this->value))
		{
			return;
		}

		// Sérialisation.
		$value = serialize($this->value);
		$value = ($value == 'i:1;') ? '' : $value;	// fix IE7.0 Vista

		// Domaine.
		$domain = preg_replace('`:\d+$`', '', $_SERVER['HTTP_HOST']);
		$domain = ($domain == 'localhost')
			? FALSE
			: $domain;
		$domain = (isset($_SERVER['HTTP_USER_AGENT'])
			&& stristr($_SERVER['HTTP_USER_AGENT'], 'opera')) // fix Opera
			? FALSE
			: $domain;

		// Écriture du cookie.
		return setcookie($this->_name, $value, $this->expire,
			$this->_path, $domain, FALSE, TRUE);
	}
}
?>