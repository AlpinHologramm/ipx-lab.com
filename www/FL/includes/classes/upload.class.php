<?php
/**
 * Scan le répertoire des albums à la recherche de nouvelles images ou
 * d'images modifiées, et met à jour la base de données en conséquence.
 * Effectue toutes les opérations relatives à l'ajout de nouvelles images
 * (renommage des fichiers, récupération des métadonnées, notification
 * par e-mail, mise à jour des statistiques des catégories, ajout d'un mot
 * de passe sur les catégories ajoutées dans des catégories protégées).
 *
 * Un rapport détaillé est généré au fil du déroulement du scan, permettant
 * de faire connaître à l'utilisateur les opérations effectuées et les
 * éventuelles erreurs rencontrées.
 *
 * Les propriétés publiques au préfixe 'get' correspondent à des
 * informations disponibles après le scan.
 * Les propriétés publiques au préfixe 'set' correspondent à des
 * options à définir avant le scan.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class upload
{
	/**
	 * Indique si le scan a pu être initialisé avec succès.
	 *
	 * @var boolean
	 */
	public $getInit = FALSE;

	/**
	 * Identifiants des groupes qui seront notifiés par courriel.
	 *
	 * @var array
	 */
	public $getNotifyGroups = array();

	/**
	 * Rapport détaillé du scan.
	 *
	 * @var array
	 */
	public $getReport = array();

	/**
	 * Indique si le temps d'exécution maximum a été dépassé.
	 *
	 * @var boolean
	 */
	public $getTimeExceeded = FALSE;

	/**
	 * Indique si l'on doit forcer le scan de chaque répertoire,
	 * c'est à dire ne pas tenir compte de la date de dernière modification.
	 *
	 * @var boolean
	 */
	public $setForcedScan = FALSE;

	/**
	 * Indique si le scan doit se faire en mode HTTP,
	 * c'est à dire ne scanner que le répertoire indiqué
	 * et ne récupérer que les images indiquées.
	 *
	 * @var boolean
	 */
	public $setHttp = FALSE;

	/**
	 * Images à ajouter lors du scan en mode HTTP, accompagnées
	 * des informations éventuelles de ces images (titre, description...).
	 *
	 * @var array
	 */
	public $setHttpImages = array();

	/**
	 * Répertoire temporaire où se trouvent les images originales
	 * ajoutées en mode HTTP, utilisé pour récupérer les métadonnées
	 * lorsque les images sont redimensionnées.
	 *
	 * @var string
	 */
	public $setHttpOriginalDir;

	/**
	 * Limite de poids de chaque fichier (en octets).
	 * 0 pour aucune limite.
	 *
	 * @var integer
	 */
	public $setLimitFileSize = 0;

	/**
	 * Indique si l'on doit notifier les nouvelles images
	 * par e-mail aux membres autorisés.
	 *
	 * @var boolean
	 */
	public $setMailAlert = TRUE;

	/**
	 * Identifiants des groupes à exclure de la notification.
	 *
	 * @var array
	 */
	public $setNotifyGroupsExclude = array();

	/**
	 * Indique si l'on doit publier les images.
	 *
	 * @var boolean
	 */
	public $setPublishImages = TRUE;

	/**
	 * Indique si l'on doit ajouter au rapport les fichiers
	 * qui ne sont pas des images valides.
	 *
	 * @var boolean
	 */
	public $setReportAllFiles = FALSE;

	/**
	 * Mode simulation (uniquement pour tests et débogage).
	 * Si TRUE, ne renomme pas les fichiers, n'exécute pas
	 * la transaction et n'envoi pas d'e-mail.
	 *
	 * @var boolean
	 */
	public $setSimulate = FALSE;

	/**
	 * Indique si l'on doit démarrer une transaction.
	 *
	 * @var boolean
	 */
	public $setTransaction = TRUE;

	/**
	 * Indique si l'on doit mettre à jour les images existantes, c'est à dire
	 * vérifier si les informations utiles des images sont différentes de
	 * celles enregistrées dans la base de données
	 * (ce qui peut augmenter considérablement la durée du scan).
	 *
	 * @var boolean
	 */
	public $setUpdateImages = FALSE;

	/**
	 * Indique si l'on doit choisir une nouvelle vignette pour
	 * les catégories dont on a ajouté de nouvelles images.
	 *
	 * @var boolean
	 */
	public $setUpdateThumbId = FALSE;

	/**
	 * Identifiant de l'utilisateur qui a déclenché le scan.
	 *
	 * @var integer
	 */
	public $setUserId = 1;

	/**
	 * Login de l'utilisateur qui a déclenché le scan.
	 *
	 * @var string
	 */
	public $setUserLogin;



	/**
	 * Chemin du répertoire des albums.
	 *
	 * @var string
	 */
	private $_albumsPath;

	/**
	 * Tableau des fabriquants et modèles d'appareil
	 * associés aux images leurs correspondant.
	 *
	 * @var array
	 */
	private $_camerasImages = array();

	/**
	 * Tableau établissant la correspondance entre le nom de répertoire
	 * original de chaque catégorie et le nom du répertoire renommé
	 * par _renameFile().
	 *
	 * @var array
	 */
	private $_catNames = array();

	/**
	 * Informations utiles des catégories enregistrées dans la base de données.
	 *
	 * @var array
	 */
	private $_dbCategories;

	/**
	 * Tableau contenant les fichiers ou répertoires renommés qu'il faut
	 * ignorer, ceci afin d'éviter les duplications lorsqu'un fichier renommé
	 * se retrouve une nouvelle fois scanné à cause de son nouveau nom.
	 *
	 * @var array
	 */
	private $_filesRename = array();

	/**
	 * Tableau des mots-clés trouvés dans les images,
	 * associés aux images comportant ces mots-clés.
	 *
	 * @var array
	 */
	private $_keywordsImages = array();

	/**
	 * Tableau contenant les albums dont il ne faut pas ajouter ou mettre à jour
	 * la date de dernière modification du répertoire.
	 * Ceci est utile pour forcer à re-scanner le répertoire la prochaine fois
	 * lorsqu'un message à propos d'une des images de cet album figure dans
	 * le rapport (de façon à ce que ce message soit visible à l'administrateur
	 * lors de chaque scan tant qu'il n'aura pas corrigé le problème).
	 *
	 * @var array
	 */
	private $_noFilemtime = array();

	/**
	 * Tableau contenant toutes les requêtes préparées en "INSERT" ou "UPDATE".
	 *
	 * @var array
	 */
	private $_sql;

	/**
	 * Nombre d'albums enfants activés que contient une catégorie.
	 *
	 * @var array
	 */
	private $_subActiveAlbs = array();

	/**
	 * Nombre de catégories enfants activées que contient une catégorie.
	 *
	 * @var array
	 */
	private $_subActiveCats = array();

	/**
	 * Nombre d'albums enfants désactivés que contient une catégorie.
	 *
	 * @var array
	 */
	private $_subDeactiveAlbs = array();

	/**
	 * Nombre de catégories enfants désactivées que contient une catégorie.
	 *
	 * @var array
	 */
	private $_subDeactiveCats = array();

	/**
	 * Date et heure du début du scan, qui sert de temps de contrôle
	 * pour $_timeLimit.
	 *
	 * @var integer
	 */
	private $_timeControl;

	/**
	 * Durée maximum d'exécution du scan.
	 *
	 * @var integer
	 */
	private $_timeLimit;

	/**
	 * Date et heure du début du scan, au format SQL "DATETIME".
	 *
	 * @var string
	 */
	private $_timeSQL;

	/**
	 * Chemin associé à l'identifiant de chaque image
	 * vérifiée avec l'option de mise à jour des images.
	 *
	 * @var array
	 */
	private $_updateImagesPath = array();



	/**
	 * Initialisation des paramètres de scan.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->_albumsPath = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR;

		// Contrôle du temps d'exécution.
		$this->_timeSQL = date('Y-m-d H:i:s', time());
		$this->_timeControl = time();
		$this->_timeLimit = 8;

		// Calcule du temps maximum d'exécution du script.
		if (function_exists('ini_get'))
		{
			$max = intval(ini_get('max_execution_time'));
			if (is_int($max))
			{
				$this->_timeLimit = ($max > 10) ? ceil($max / 2) - 2 : 8;
			}
		}

		// On initialise le tableau des requêtes préparées.
		utils::$db->sql = array();

		$this->_sql = array(
			'insert_images' => array(),
			'update_images' => array(),
			'insert_categories' => array(),
			'update_insert_categories' => array(),
			'update_categories' => array(),
			'update_categories_filemtime' => array()
		);

		$this->_sql['insert_images']['sql'] =
			'INSERT INTO ' . CONF_DB_PREF . 'images (
			cat_id, user_id, image_path, image_url, image_height, image_width,
			image_filesize, image_exif, image_iptc, image_xmp, image_rotation,
			image_name, image_desc, image_adddt, image_crtdt, image_lat,
			image_long, image_status)
			VALUES (:cat_id, :user_id, :image_path, :image_url, :image_height,
			:image_width, :image_filesize, :image_exif, :image_iptc, :image_xmp,
			:image_rotation, :image_name, :image_desc,
			:image_adddt, :image_crtdt, :image_lat, :image_long, :image_status)';
		$this->_sql['insert_images']['params'] = array();

		$this->_sql['update_images']['sql'] =
			'UPDATE ' . CONF_DB_PREF . 'images
			SET image_path = :image_path, image_name = :image_name,
			image_width = :image_width, image_height = :image_height,
			image_filesize = :image_filesize, image_url = :image_url,
			image_desc = :image_desc, image_crtdt = :image_crtdt,
			image_lat = :image_lat, image_long = :image_long
			WHERE image_id = :image_id';
		$this->_sql['update_images']['params'] = array();

		$this->_sql['insert_categories']['sql'] =
			'INSERT INTO ' . CONF_DB_PREF . 'categories (
			user_id, thumb_id, parent_id, cat_path, cat_name, cat_url, cat_a_size,
			cat_a_subalbs, cat_a_subcats, cat_a_albums, cat_a_images, cat_d_size,
			cat_d_subalbs, cat_d_subcats, cat_d_albums, cat_d_images,
			cat_crtdt, cat_lastadddt, cat_filemtime, cat_password, cat_status)
			VALUES (:user_id, :thumb_id, 1, :cat_path, :cat_name, :cat_url, :cat_a_size,
			:cat_a_subalbs, :cat_a_subcats, :cat_a_albums, :cat_a_images, :cat_d_size,
			:cat_d_subalbs, :cat_d_subcats, :cat_d_albums, :cat_d_images,
			:cat_crtdt, :cat_lastadddt, :cat_filemtime, :cat_password, :cat_status)';
		$this->_sql['insert_categories']['params'] = array();

		$this->_sql['update_insert_categories']['sql'] =
			'UPDATE ' . CONF_DB_PREF . 'categories
			SET parent_id = :parent_id, cat_position = cat_id
			WHERE cat_path = :cat_path';
		$this->_sql['update_insert_categories']['params'] = array();

		$this->_sql['update_categories_filemtime']['sql'] =
			'UPDATE ' . CONF_DB_PREF . 'categories
			SET cat_filemtime = :cat_filemtime WHERE cat_path = :cat_path';
		$this->_sql['update_categories_filemtime']['params'] = array();

		// Début de la transaction.
		if ($this->setTransaction && utils::$db->transaction() === FALSE)
		{
			return;
		}

		// On récupère les informations utiles de toutes les 
		// catégories enregistrées dans la base de données.
		$sql = 'SELECT cat.cat_id,
					   cat.thumb_id,
					   cat.cat_path,
					   cat.cat_a_size,
					   cat.cat_a_images,
					   cat.cat_d_images,
					   cat.cat_crtdt,
					   cat.cat_filemtime,
					   cat.cat_password,
					   img.image_path
				  FROM ' . CONF_DB_PREF . 'categories AS cat
			 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
					ON cat.thumb_id = img.image_id
			  ORDER BY cat.cat_name';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_path');
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			return;
		}
		$this->_dbCategories = utils::$db->queryResult;

		// Initialisation du tableau de rapport.
		$this->getReport['alb_add'] = array();
		$this->getReport['alb_update'] = array();
		$this->getReport['camera_add'] = 0;
		$this->getReport['cat_reject'] = array();
		$this->getReport['errors'] = array();
		$this->getReport['img_add'] = 0;
		$this->getReport['img_reject'] = array();
		$this->getReport['img_update'] = 0;
		$this->getReport['tag_add'] = 0;

		// Initialisation réussie.
		$this->getInit = TRUE;
	}

	/**
	 * Scan récursif du répertoire des albums
	 * ou d'un répertoire de catégorie.
	 *
	 * @param string $dir
	 *	Chemin du répertoire à scanner, relatif au répertoire des albums.
	 * @return mixed
	 */
	public function getAlbums($dir = '')
	{
		if (!$this->getInit)
		{
			return;
		}

		$cat_infos = array(
			'a_albums' => 0,
			'd_albums' => 0,
			'a_images' => 0,
			'd_images' => 0,
			'a_size' => 0,
			'd_size' => 0
		);

		$sub_dir = FALSE;

		// Si le mode HTTP n'est pas activé,
		// on parcours le répertoire s'il n'est pas un album,
		if (!$this->setHttp
		&& (array_key_exists($dir, $this->_dbCategories) &&
		$this->_dbCategories[$dir]['cat_filemtime'] !== NULL) === FALSE

		// Et s'il ne contient pas un répertoire de vignettes.
		// (uniquement pour compatibilité avec iGalerie 1.0)
		&& ($dir !== '' &&
		is_dir($this->_albumsPath . '/' . $dir . '/' . CONF_THUMBS_DIR)) === FALSE)
		{
			if (($res = opendir($this->_albumsPath . '/' . $dir)) === FALSE)
			{
				if ($dir === '')
				{
					$dir = __('répertoire des albums');
				}
				$this->getReport['errors'][] = array(
					$dir,
					__('impossible d\'accéder au répertoire')
				);
				return FALSE;
			}
			$dir = ($dir === '') ? '' : $dir . '/';

			while (($ent = readdir($res)) !== FALSE)
			{
				// Contrôle du temps d'exécution.
				if ((time() - $this->_timeControl) > $this->_timeLimit)
				{
					$this->getTimeExceeded = TRUE;
					break;
				}

				// Si c'est un répertoire.
				if (is_dir($this->_albumsPath . '/' . $dir . $ent)
				&& $ent !== '.' && $ent !== '..'

				// Et si ce n'est pas un répertoire de vignettes.
				// (uniquement pour compatibilité avec iGalerie 1.0)
				&& $ent !== CONF_THUMBS_DIR

				// Et s'il ne contient aucun caractère invalide.
				&& !strstr($ent, '?')

				// Et s'il ne figure pas parmi les répertoires renommés.
				&& !in_array($dir . $ent, $this->_filesRename)

				// Et si le nom est correct.
				&& ($ent = $this->_checkDirName($ent, $dir)) !== FALSE)
				{
					// On scan le répertoire.
					if (($images_infos = $this->getAlbums($dir . $ent)) !== FALSE)
					{
						$cat_infos['a_albums'] += $images_infos['a_albums'];
						$cat_infos['d_albums'] += $images_infos['d_albums'];
						$cat_infos['a_images'] += $images_infos['a_images'];
						$cat_infos['d_images'] += $images_infos['d_images'];
						$cat_infos['a_size'] += $images_infos['a_size'];
						$cat_infos['d_size'] += $images_infos['d_size'];
					}

					$sub_dir = TRUE;
				}
			}
			closedir($res);
		}
		else
		{
			$dir = ($dir === '') ? '' : $dir . '/';
		}

		// Récupération des images de l'album.
		// On suppose que c'est un album lorsqu'il n'y a aucun sous-répertoire.
		if ($dir !== '' && $sub_dir === FALSE
		&& ($image_infos = $this->_getImages($dir)) !== FALSE)
		{
			$cat_infos = $image_infos;
			$cat_infos['a_albums'] = 0;
			$cat_infos['d_albums'] = 0;
			$cat_infos['filemtime'] = filemtime($this->_albumsPath . '/' . $dir);

			// Si l'album n'existe pas en base de données,
			// on incrémente le compteur du nombre d'albums enfants.
			if (!isset($this->_dbCategories[substr($dir, 0, -1)]))
			{
				// Publié.
				if ($this->setPublishImages)
				{
					// Albums.
					$cat_infos['a_albums'] = 1;

					// Sous-albums.
					if (isset($this->_subActiveAlbs[dirname($dir)]))
					{
						$this->_subActiveAlbs[dirname($dir)]++;
					}
					else
					{
						$this->_subActiveAlbs[dirname($dir)] = 1;
					}
				}

				// Non publié.
				else
				{
					// Albums.
					$cat_infos['d_albums'] = 1;

					// Sous-albums.
					if (isset($this->_subDeactiveAlbs[dirname($dir)]))
					{
						$this->_subDeactiveAlbs[dirname($dir)]++;
					}
					else
					{
						$this->_subDeactiveAlbs[dirname($dir)] = 1;
					}
				}
			}

			// Si l'album ne contient aucune image activée
			// et qu'au moins une image a été ajoutée,
			// on incrémente le nombre d'albums activés,
			// et on décrémente le nombre d'albums désactivés
			// pour les catégories parentes.
			else if ($this->_dbCategories[substr($dir, 0, -1)]['cat_a_images'] == 0
			&& $cat_infos['a_images'] > 0)
			{
				// Albums.
				$cat_infos['a_albums'] = 1;
				$cat_infos['d_albums'] = -1;

				// Sous-albums.
				if (isset($this->_subActiveAlbs[dirname($dir)]))
				{
					$this->_subActiveAlbs[dirname($dir)]++;
				}
				else
				{
					$this->_subActiveAlbs[dirname($dir)] = 1;
				}
				if (isset($this->_subDeactiveAlbs[dirname($dir)]))
				{
					$this->_subDeactiveAlbs[dirname($dir)]--;
				}
				else
				{
					$this->_subDeactiveAlbs[dirname($dir)] = -1;
				}
			}
		}

		// Préparation des requêtes pour les catégories.
		if (!$this->setHttp && ($cat_infos['a_size'] !== 0 || $cat_infos['d_size'] !== 0))
		{
			$cat_dir = ($dir === '') ? '.' : substr($dir, 0, -1);
			if (isset($this->_dbCategories[$cat_dir]))
			{
				$this->_updateCategory($cat_dir, $cat_infos);
			}
			else
			{
				$this->_insertCategory($cat_dir, $cat_infos);
			}
			if ($dir !== '')
			{
				return $cat_infos;
			}
		}

		// En mode HTTP, on update les informations pour les catégories parentes.
		if ($this->setHttp)
		{
			$cat_dir = substr($dir, 0, -1);
			while ($cat_dir != '')
			{
				$cat_infos['filemtime'] = ($cat_dir == substr($dir, 0, -1)
					&& isset($cat_infos['filemtime']))
					? $cat_infos['filemtime']
					: 0;
				$this->_updateCategory($cat_dir, $cat_infos);
				$cat_dir = ($cat_dir == '.') ? '' : dirname($cat_dir);
			}
		}

		// On met à jour la base de données si nécessaire.
		if ($this->setHttp || $dir === '')
		{
			if ($cat_infos['a_size'] !== 0 || $cat_infos['d_size'] !== 0
			|| count($this->_sql['update_categories_filemtime']['params']) > 0)
			{
				return $this->_updateDB($cat_infos);
			}
		}
	}



	/**
	 * Vérifie et renomme si nécessaire le nom d'un répertoire.
	 *
	 * @param string $f
	 *	Nom du répertoire à vérifier.
	 * @param string $dir
	 *	Chemin du répertoire parent de $f.
	 * @return boolean|string
	 */
	private function _checkDirName($f, $dir)
	{
		if (strlen($dir . $f) > 255)
		{
			$this->getReport['cat_reject'][] = array(
				$f,
				__('nom trop long')
			);
			return FALSE;
		}
		$new_f = $this->_renameFile($f, $dir, '`([^-_a-z0-9])`i', 'dir');
		$this->_catNames[$new_f] = $f;
		return $new_f;
	}

	/**
	 * Vérifie et renomme si nécessaire le nom d'un fichier.
	 *
	 * @param string $f
	 *	Nom du fichier à vérifier.
	 * @param string $dir
	 *	Chemin du répertoire parent de $f.
	 * @return boolean|string
	 */
	private function _checkFileName($f, $dir)
	{
		// Vérification de la longueur du nom.
		if (strlen($dir . $f) > 255)
		{
			$this->getReport['img_reject'][] = array(
				$f,
				$dir,
				__('nom trop long')
			);
			return FALSE;
		}

		// Vérification de l'extension.
		if (!preg_match('`\.(jpe?g|gif|png)$`i', $f))
		{
			$this->getReport['img_reject'][] = array(
				$f,
				$dir,
				__('extension incorrecte')
			);
			return FALSE;
		}

		// On renomme le fichier.
		return $this->_renameFile($f, $dir, '`([^-_a-z0-9.])`i', 'file');
	}

	/**
	 * Récupère les nouvelles informations des images d'un album.
	 *
	 * @param string $dir
	 *	Chemin du répertoire à scanner.
	 * @return boolean|array
	 */
	private function _getImages($dir)
	{
		$filemtime = date('Y-m-d H:i:s', filemtime($this->_albumsPath . '/' . $dir));

		// Si l'on est pas en scan forcé,
		// et si la date de dernière modification du répertoire n'a pas changée
		// par rapport à celle enregistrée lors du précédent scan, ou bien
		// si c'est une catégorie on ne va pas plus loin.
		if (!$this->setForcedScan
		&& (isset($this->_dbCategories[substr($dir, 0, -1)])
		&& ($filemtime == $this->_dbCategories[substr($dir, 0, -1)]['cat_filemtime']
		|| $this->_dbCategories[substr($dir, 0, -1)]['cat_filemtime'] === NULL)))
		{
			return FALSE;
		}

		$cat_infos['a_images'] = 0;
		$cat_infos['d_images'] = 0;
		$cat_infos['a_size'] = 0;
		$cat_infos['d_size'] = 0;

		// Récupération du chemin de toutes les images de l'album
		// enregistrées dans la base de données.
		$db_infos = array();
		if (isset($this->_dbCategories[substr($dir, 0, -1)]))
		{
			$sql_select = ($this->setUpdateImages)
				? ',image_id, image_width, image_height, image_filesize,
				    image_status, image_crtdt, image_name, image_desc,
					image_lat, image_long'
				: '';
			$sql = 'SELECT image_path' . $sql_select . '
					  FROM ' . CONF_DB_PREF . 'images
				     WHERE image_path LIKE "' . $dir  . '%"';
			$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_path');
			if (utils::$db->query($sql, $fetch_style) === FALSE)
			{
				$this->getReport['errors'][] = array($dir, __('requête SQL échouée'));
				return FALSE;
			}
			$db_infos = utils::$db->queryResult;
		}

		// Statut des images à ajouter.
		if ($this->setPublishImages)
		{
			$image_status = 1;
			$status_images = 'a_images';
			$status_size = 'a_size';
		}
		else
		{
			$image_status = 0;
			$status_images = 'd_images';
			$status_size = 'd_size';
		}

		// Scan du répertoire à la recherche d'images valides.
		if (($res = opendir($this->_albumsPath . '/' . $dir)) === FALSE)
		{
			$this->getReport['errors'][] = array(
				$dir,
				__('impossible d\'accéder au répertoire')
			);
			return FALSE;
		}
		$this->_sql['insert_images']['params'][$dir] = array();
		while (($ent = readdir($res)) !== FALSE)
		{
			if (!is_file($this->_albumsPath . '/' . $dir . $ent))
			{
				continue;
			}

			// En mode HTTP, on s'occupe que des images spécifiées.
			if ($this->setHttp && !isset($this->setHttpImages[$ent]))
			{
				continue;
			}

			// Si l'image est déjà présente en base de données,
			// on l'UPDATE si nécessaire.
			if (isset($db_infos[$dir . $ent]) &&
			($updade_infos = $this->_updateImage($dir, $ent, $db_infos[$dir . $ent])) !== FALSE)
			{
				$cat_infos['a_size'] += $updade_infos['a_size'];
				$cat_infos['d_size'] += $updade_infos['d_size'];
			}

			// Sinon, si l'image n'est pas présente en base de données,
			// on l'INSERT.
			else if (!isset($db_infos[$dir . $ent]) &&
			!in_array($dir . $ent, $this->_filesRename) &&
			($image_size = $this->_insertImage($dir, $ent, $image_status)) !== FALSE)
			{
				$cat_infos[$status_images]++;
				$cat_infos[$status_size] += $image_size;
			}

			// Sinon on ignore la suite.
			else
			{
				continue;
			}

			// Contrôle du temps d'exécution.
			if ((time() - $this->_timeControl) > $this->_timeLimit)
			{
				$this->_noFilemtime[$dir] = 1;
				$this->getTimeExceeded = TRUE;
				break;
			}
		}
		closedir($res);

		// Si le répertoire contient de nouvelles images valides,
		// ou bien si le poids de certaines images existantes a changé
		// on retourne les informations de ces images.
		if ($cat_infos['a_size'] !== 0 || $cat_infos['d_size'] !== 0)
		{
			return $cat_infos;
		}

		// Sinon, si l'album n'est pas présent dans la base de données
		// on ajoute l'information au rapport.
		else if (!isset($this->_dbCategories[substr($dir, 0, -1)]))
		{
			$this->getReport['cat_reject'][] = array(
				$dir,
				__('le répertoire ne contient aucune image valide')
			);
		}

		// Sinon on UPDATE la date de dernière modification du répertoire
		// pour éviter de le scanner la prochaine fois, mais seulement s'il
		// n'y a pas de message à propos d'une image rejetée dans le rapport
		// et si ce n'est pas une catégorie vide créée en admin.
		else if (!isset($this->_noFilemtime[$dir])
		&& $this->_dbCategories[substr($dir, 0, -1)]['thumb_id'] != -1)
		{
			$this->_sql['update_categories_filemtime']['params'][] = array(
				'cat_filemtime' => $filemtime,
				'cat_path' => substr($dir, 0, -1)
			);
		}

		unset($this->_sql['insert_images']['params'][$dir]);
		return FALSE;
	}

	/**
	 * Récupère les métadonnées EXIF, IPTC et XMP utiles d'une image.
	 *
	 * @param string $file_path
	 *	Chemine du fichier.
	 * @return void
	 */
	private function _getMetadata($file_path)
	{
		$this->_imageMetadata = new metadata($file_path);

		$this->_imageMetadata->getExifImageFields();
		$this->_imageMetadata->getIptcImageFields();
		$this->_imageMetadata->getXmpImageFields();
	}

	/**
	 * Retourne la métadonnée $info.
	 *
	 * @param string $info
	 *	Information à récupérer.
	 * @return mixed
	 *	Retourne l'information demandée (string|array)
	 *	ou NULL si aucune information disponible.
	 */
	private function _getMetadataInfo($info)
	{
		switch ($info)
		{
			// Date de création.
			case 'crtdt' :
				return $this->_getMetadataInfoPriority(
					$this->_imageMetadata->xmpCrtdt,
					$this->_imageMetadata->exifDateTimeOriginal
				);

			// Description.
			case 'desc' :
				return $this->_getMetadataInfoPriority(
					$this->_imageMetadata->xmpDescription,
					$this->_imageMetadata->iptcDescription
				);

			// Mots-clés.
			case 'keywords' :
				return $this->_getMetadataInfoPriority(
					$this->_imageMetadata->xmpKeywords,
					$this->_imageMetadata->iptcKeywords
				);

			// Latitude.
			case 'latitude' :
				return $this->_imageMetadata->exifGPSLatitude;

			// Longitude.
			case 'longitude' :
				return $this->_imageMetadata->exifGPSLongitude;

			// Fabriquant de l'appareil.
			case 'make' :
				$make = $this->_imageMetadata->exifMake;
				return $this->_imageMetadata->getCameraMake($make);

			// Modèle de l'appareil.
			case 'model' :
				return $this->_imageMetadata->exifModel;

			// Orientation.
			case 'orientation' :
				$orientation = $this->_getMetadataInfoPriority(
					$this->_imageMetadata->xmpOrientation,
					$this->_imageMetadata->exifOrientation
				);
				return ($orientation) ? $orientation : '1';

			// Titre.
			case 'title' :
				return $this->_getMetadataInfoPriority(
					$this->_imageMetadata->xmpTitle,
					$this->_imageMetadata->iptcTitle
				);
		}
	}

	/**
	 * Retourne l'information XMP $xmp ou l'information
	 * alternative EXIF ou IPTC $alt, selon la priorité XMP.
	 *
	 * @param string $xmp
	 *	Information XMP.
	 * @param string $alt
	 *	Information EXIF ou IPTC alternative.
	 * @return null|string
	 *	Retourne l'information demandée (string)
	 *	ou NULL si aucune information disponible.
	 */
	private function _getMetadataInfoPriority($xmp, $alt)
	{
		// Si XMP est prioritaire et qu'une information XMP
		// a été trouvée, alors on retourne celle-ci.
		if (utils::$config['xmp_priority'])
		{
			return ($xmp !== NULL) ? $xmp : $alt;
		}

		// Si XMP n'est pas prioritaire et qu'une information alternative
		// (IPTC ou EXIF)  a été trouvée, alors on retourne celle-ci.
		else
		{
			return ($alt !== NULL) ? $alt : $xmp;
		}
	}

	/**
	 * Enregistre les informations de la catégorie à ajouter.
	 *
	 * @param string $dir
	 *	Chemin du répertoire de la catégorie.
	 * @param array $images_infos
	 *	Informations utiles de la catégorie.
	 * @return void
	 */
	private function _insertCategory($dir, $images_infos)
	{
		// On incrémente le compteur du nombre de catégories enfants.
		if (empty($images_infos['filemtime'])
		&& !isset($this->_dbCategories[$dir]))
		{
			// Publiée.
			if ($this->setPublishImages)
			{
				if (isset($this->_subActiveCats[dirname($dir)]))
				{
					$this->_subActiveCats[dirname($dir)]++;
				}
				else
				{
					$this->_subActiveCats[dirname($dir)] = 1;
				}
			}

			// Non publiée.
			else
			{
				if (isset($this->_subDeactiveCats[dirname($dir)]))
				{
					$this->_subDeactiveCats[dirname($dir)]++;
				}
				else
				{
					$this->_subDeactiveCats[dirname($dir)] = 1;
				}
			}
		}

		// Date de dernière modification.
		if (empty($images_infos['filemtime']))
		{
			$cat_filemtime = NULL;
		}
		else
		{
			$cat_filemtime = (empty($this->_noFilemtime[$dir . '/']))
				? date('Y-m-d H:i:s', $images_infos['filemtime'])
				: date('Y-00-00 H:i:s', $images_infos['filemtime']);
		}

		// Titre.
		$cat_name = str_replace('_', ' ', $this->_catNames[basename($dir)]);
		$cat_name = utils::UTF8($cat_name);

		// Nombre d'albums et de catégories enfants activés.
		$cat_a_subalbs = (isset($this->_subActiveAlbs[$dir]))
			? $this->_subActiveAlbs[$dir]
			: 0;
		$cat_a_subcats = (isset($this->_subActiveCats[$dir]))
			? $this->_subActiveCats[$dir]
			: 0;

		// Nombre d'albums et de catégories enfants désactivés.
		$cat_d_subalbs = (isset($this->_subDeactiveAlbs[$dir]))
			? $this->_subDeactiveAlbs[$dir]
			: 0;
		$cat_d_subcats = (isset($this->_subDeactiveCats[$dir]))
			? $this->_subDeactiveCats[$dir]
			: 0;

		// Nombre total d'albums activés dans la catégorie.
		$cat_a_albums = (empty($images_infos['filemtime']))
			? $images_infos['a_albums']
			: 0;

		// Nombre total d'albums activés dans la catégorie.
		$cat_d_albums = (empty($images_infos['filemtime']))
			? $images_infos['d_albums']
			: 0;

		// On ajoute l'éventuel mot de passe d'une catégorie parente.
		$cat_password = NULL;
		$parent_dir = dirname($dir);
		while ($parent_dir != '.')
		{
			if (isset($this->_dbCategories[$parent_dir])
			&& $this->_dbCategories[$parent_dir]['cat_password'] !== NULL)
			{
				$cat_password = $this->_dbCategories[$parent_dir]['cat_password'];
				break;
			}
			$parent_dir = dirname($parent_dir);
		}

		// Statut.
		$cat_status = ($this->setPublishImages)
			? 1
			: 0;

		// Paramètres de la requête préparée.
		$this->_sql['insert_categories']['params'][] = array(
			'user_id' => (int) $this->setUserId,
			'cat_path' => $dir,
			'cat_name' => $cat_name,
			'cat_url' => utils::genURLName($cat_name),
			'cat_a_size' => (int) $images_infos['a_size'],
			'cat_a_subalbs' => (int) $cat_a_subalbs,
			'cat_a_subcats' => (int) $cat_a_subcats,
			'cat_a_albums' => (int) $cat_a_albums,
			'cat_a_images' => (int) $images_infos['a_images'],
			'cat_d_size' => (int) $images_infos['d_size'],
			'cat_d_subalbs' => (int) $cat_d_subalbs,
			'cat_d_subcats' => (int) $cat_d_subcats,
			'cat_d_albums' => (int) $cat_d_albums,
			'cat_d_images' => (int) $images_infos['d_images'],
			'cat_crtdt' => $this->_timeSQL,
			'cat_lastadddt' => $this->_timeSQL,
			'cat_filemtime' => $cat_filemtime,
			'cat_password' => $cat_password,
			'cat_status' => $cat_status
		);

		// On ajoute les informations pour le rapport seulement pour les albums.
		if (!empty($images_infos['filemtime']))
		{
			$this->getReport['alb_add'][] = array(
				$dir,
				$images_infos['a_images'] + $images_infos['d_images'],
				$images_infos['a_size'] + $images_infos['d_size']
			);
		}
	}

	/**
	 * Enregistre les paramètres de l'image à ajouter à la base de données.
	 *
	 * @param string $album
	 *	Chemin du répertoire parent de $image.
	 * @param string $image
	 *	Image à ajouter à la base de données.
	 * @param string $image_status
	 *	Statut de l'image.
	 * @return boolean|string
	 */
	private function _insertImage($album, $image, $image_status)
	{
		// Si le fichier n'est pas une image valide.
		$file = $this->_albumsPath . '/' . $album . $image;
		if (($i = getimagesize($file)) === FALSE)
		{
			if ($this->setReportAllFiles)
			{
				$this->getReport['img_reject'][] = array(
					$image,
					$album,
					__('le fichier n\'est pas une image valide')
				);
				$this->_noFilemtime[$album] = 1;
			}
			return FALSE;
		}

		// Si l'image n'est pas au format GIF, JPEG ou PNG.
		if (in_array($i[2], array(1, 2, 3)) === FALSE)
		{
			$message = sprintf(
				__('type de fichier non accepté : %s'),
				htmlspecialchars($i['mime']) . ' ('. (int) $i[2] . ')'
			);
			$this->getReport['img_reject'][] = array(
				$image,
				$album,
				$message
			);
			$this->_noFilemtime[$album] = 1;
			return FALSE;
		}

		// Si le nom de l'image est incorrect.
		if (($file_name = $this->_checkFileName($image, $album)) === FALSE)
		{
			$this->_noFilemtime[$album] = 1;
			return FALSE;
		}
		$image_path = $this->_albumsPath . '/' . $album . $file_name;

		// Identifiant de l'album.
		$cat_id = 1;
		if (isset($this->_dbCategories[$album]))
		{
			$cat_id = $this->_dbCategories[$album]['cat_id'];
		}

		// Poids du fichier.
		if (($image_filesize = filesize($image_path)) === FALSE)
		{
			return FALSE;
		}

		// Doit-on limiter le poids du fichier ?
		if ($this->setLimitFileSize && $image_filesize > $this->setLimitFileSize)
		{
			$message = sprintf(
				__('le poids du fichier (%s) dépasse la limite autorisée (%s)'),
				utils::filesize($image_filesize),
				utils::filesize($this->setLimitFileSize)
			);
			$this->getReport['img_reject'][] = array(
				$image,
				$album,
				$message
			);
			$this->_noFilemtime[$album] = 1;
			return FALSE;
		}

		// Identifiant de l'utilisateur.
		$user_id = ($this->setHttp && isset($this->setHttpImages[$image]['user_id']))
			? $this->setHttpImages[$image]['user_id']
			: $this->setUserId;

		// Récupération des métadonnées.
		$image_exif = NULL;
		$image_iptc = NULL;
		$image_xmp = NULL;
		$this->_imageMetadata = NULL;
		if ($this->setHttp)
		{
			if ($this->setHttpOriginalDir)
			{
				$original = $this->setHttpOriginalDir . '/' . $file_name;
				if (file_exists($original))
				{
					$this->_getMetadata($original);

					$image_exif = $this->_imageMetadata->getExifDB();
					$image_iptc = $this->_imageMetadata->getIptcDB();
					$image_xmp = $this->_imageMetadata->getXmpDB();
				}
			}
			else if (!empty($this->setHttpImages[$image]['image_exif'])
				  || !empty($this->setHttpImages[$image]['image_iptc'])
				  || !empty($this->setHttpImages[$image]['image_xmp']))
			{
				$this->_imageMetadata = new metadata();

				if (!empty($this->setHttpImages[$image]['image_exif']))
				{
					$image_exif = $this->setHttpImages[$image]['image_exif'];
					$this->_imageMetadata->exifData = unserialize($image_exif);
					$this->_imageMetadata->getExifImageFields();
				}
				if (!empty($this->setHttpImages[$image]['image_iptc']))
				{
					$image_iptc = $this->setHttpImages[$image]['image_iptc'];
					$this->_imageMetadata->iptcData = unserialize($image_iptc);
					$this->_imageMetadata->getIptcImageFields();
				}
				if (!empty($this->setHttpImages[$image]['image_xmp']))
				{
					$image_xmp = $this->setHttpImages[$image]['image_xmp'];
					$this->_imageMetadata->xmpData = $image_xmp;
					$this->_imageMetadata->getXmpImageFields();
				}
			}
		}
		if (!is_object($this->_imageMetadata))
		{
			$this->_getMetadata($image_path);
		}

		// Titre.
		if ($this->setHttp && isset($this->setHttpImages[$image]['image_name']))
		{
			$image_name = $this->setHttpImages[$image]['image_name'];
		}
		else if (!utils::isEmpty($this->_getMetadataInfo('title')))
		{
			$image_name = $this->_getMetadataInfo('title');
		}
		else
		{
			$image_name = img::imageName($image);
		}

		// Description.
		if ($this->setHttp && isset($this->setHttpImages[$image]['image_desc']))
		{
			$image_desc = $this->setHttpImages[$image]['image_desc'];
		}
		else
		{
			$image_desc = $this->_getMetadataInfo('desc');
		}
		$image_desc = utils::isEmpty($image_desc)
			? NULL
			: $image_desc;

		// Tables de metadonnées.
		$this->_metadataTables($album . $file_name);

		// Paramètres de la requête préparée.
		$this->_sql['insert_images']['params'][$album][] = array(
			'cat_id' => (int) $cat_id,
			'user_id' => (int) $user_id,
			'image_path' => $album . $file_name,
			'image_url' => utils::genURLName($image_name),
			'image_height' => (int) $i[1],
			'image_width' => (int) $i[0],
			'image_filesize' => (int) $image_filesize,
			'image_exif' => $image_exif,
			'image_iptc' => $image_iptc,
			'image_xmp' => $image_xmp,
			'image_rotation' => (int) $this->_getMetadataInfo('orientation'),
			'image_lat' => $this->_getMetadataInfo('latitude'),
			'image_long' => $this->_getMetadataInfo('longitude'),
			'image_name' => $image_name,
			'image_desc' => $image_desc,
			'image_adddt' => $this->_timeSQL,
			'image_crtdt' => $this->_getMetadataInfo('crtdt'),
			'image_status' => $image_status
		);

		$this->getReport['img_add']++;

		return $image_filesize;
	}

	/**
	 * Prépare les informations de l'image $image_path
	 * destinées à remplir les tables de métadonnées (tags, cameras).
	 *
	 * @param string $image_path
	 *	Chemin de l'image.
	 * @return void
	 */
	private function _metadataTables($image_path)
	{
		// Mots-clés.
		if (is_array($keywords = $this->_getMetadataInfo('keywords')))
		{
			foreach ($keywords as &$keyword)
			{
				$keyword = str_replace(',', '', $keyword);

				if (!isset($this->_keywordsImages[$keyword]))
				{
					$this->_keywordsImages[$keyword] = array();
				}
				$this->_keywordsImages[$keyword][] = $image_path;
			}
		}

		// Fabriquant et modèle de l'appareil.
		$make = $this->_getMetadataInfo('make');
		$model = $this->_getMetadataInfo('model');
		if (!empty($make) && !empty($model))
		{
			if (!isset($this->_camerasImages[$make]))
			{
				$this->_camerasImages[$make] = array();
			}
			if (!isset($this->_camerasImages[$make][$model]))
			{
				$this->_camerasImages[$make][$model] = array();
			}
			$this->_camerasImages[$make][$model][] = $image_path;
		}
	}

	/**
	 * Renomme un fichier ou un répertoire, si nécessaire.
	 *
	 * @param string $f
	 *	Nom du fichier ou du répertoire à renommer.
	 * @param string $dir
	 *	Chemin du répertoire parent de $f.
	 * @param string $pattern
	 *	Expression régulière des caractères non autorisés.
	 * @param string $type
	 *	Répertoire (dir) ou fichier (file) ?
	 * @return boolean|string
	 */
	private function _renameFile($f, $dir, $pattern, $type)
	{
		// Le nom de fichier est OK s'il ne contient aucun caractère non autorisé.
		if (!preg_match($pattern, $f))
		{
			return $f;
		}

		$path = $this->_albumsPath . '/' . $dir;

		// Nouveau nom de fichier.
		$new_f = utils::UTF8($f);
		$new_f = utils::removeAccents($new_f);
		$new_f = preg_replace($pattern, '_', $new_f);

		// On renomme le fichier si un fichier de même nom existe déjà.
		$test = $new_f;
		$n = 2;
		while (file_exists($path . $test))
		{
			if ($n > 99)
			{
				if ($type == 'dir')
				{
					$message = __('renommage impossible,'
						. ' un répertoire portant le même nom existe déjà : %s');
					$this->getReport['cat_reject'][] = array(
						$dir . $f,
						sprintf($message, $new_f)
					);
				}
				else
				{
					$message = __('renommage impossible,'
						. ' un fichier portant le même nom existe déjà : %s');
					$this->getReport['img_reject'][] = array(
						$f,
						$dir,
						sprintf($message, $new_f)
					);
				}
				return FALSE;
			}
			$test = ($type == 'dir')
				? $new_f . '_' . $n
				: preg_replace('`^(.+)\.([^\.]+)$`', '\1_' . $n . '.\2', $new_f);
			$n++;
		}
		$new_f = $test;

		// On renomme le fichier, sauf en mode simulation.
		if ($this->setSimulate)
		{
			return $f;
		}
		else if (($type == 'dir' && files::renameDir($path . $f, $path . $new_f))
		|| ($type == 'file' && files::renameFile($path . $f, $path . $new_f)))
		{
			$this->_filesRename[] = $dir . $new_f;
			return $new_f;
		}
		else
		{
			$this->getReport['errors'][] = array(
				$dir . $f,
				__('renommage impossible')
			);
			return FALSE;
		}
	}

	/**
	 * Enregistre les informations de la catégorie à updater.
	 *
	 * @param string $dir
	 *	Chemin du répertoire de la catégorie.
	 * @param array $images_infos
	 *	Informations utiles de la catégorie.
	 * @return void
	 */
	private function _updateCategory($dir, $images_infos)
	{
		// Date de dernière modification.
		if (empty($images_infos['filemtime']))
		{
			$cat_filemtime = NULL;
		}
		else
		{
			$cat_filemtime = (empty($this->_noFilemtime[$dir . '/']))
				? date('Y-m-d H:i:s', $images_infos['filemtime'])
				: date('Y-00-00 H:i:s', $images_infos['filemtime']);
		}

		// Date de dernier ajout d'une image.
		$cat_lastadddt = ($images_infos['a_images'] > 0
		|| ($this->_dbCategories[$dir]['cat_a_images'] == 0 && $images_infos['d_images'] > 0))
			? 'cat_lastadddt = "' . $this->_timeSQL . '", '
			: '';

		// S'il n'y a aucune image nouvelle,
		// on ne touche pas au au statut, sinon
		// on force le statut sur 'publié'.
		$cat_status = ($images_infos['a_images'] > 0)
			? ', cat_status = "1"'
			: '';

		// Paramètres de la requête SQL.
		$this->_sql['update_categories'][] = array(
			'dir' => $dir,
			'cat_filemtime' => $cat_filemtime,
			'cat_lastadddt' => $cat_lastadddt,
			'cat_status' => $cat_status,
			'images_infos' => $images_infos
		);

		// Si c'est une catégorie vide et qu'au moins une image activée y a été ajoutée,
		// on ajoute 1 au nombre de sous-catégries activées, et on retire 1 au
		// nombre de sous-catégories désactivées pour les catégories parentes.
		if ($dir !== '.' && $this->_dbCategories[$dir]['thumb_id'] == -1
		&& $images_infos['a_images'] > 0 && empty($images_infos['filemtime']))
		{
			if (isset($this->_subActiveCats[dirname($dir)]))
			{
				$this->_subActiveCats[dirname($dir)]++;
			}
			else
			{
				$this->_subActiveCats[dirname($dir)] = 1;
			}
			if (isset($this->_subDeactiveCats[dirname($dir)]))
			{
				$this->_subDeactiveCats[dirname($dir)]--;
			}
			else
			{
				$this->_subDeactiveCats[dirname($dir)] = -1;
			}
		}

		// On ajoute les informations pour le rapport seulement pour les albums.
		if ($dir !== '.' && !empty($images_infos['filemtime']))
		{
			$this->getReport['alb_update'][] = array(
				$dir,
				$images_infos['a_images'] + $images_infos['d_images'],
				$images_infos['a_size'] + $images_infos['d_size']
			);
		}
	}

	/**
	 * Effectue toutes les modifications nécessaires de la base de données,
	 * et notifie par e-mail en cas de succès.
	 *
	 * @param array $infos
	 *	Informations utiles des éléments scannés.
	 * @return boolean
	 */
	private function _updateDB(&$infos)
	{
		$albums = array();
		$images_id_params = array();
		$cat_images_id = array();
		$categories_id = array();
		$categories_id['.'] = 1;

		// Nouvelles images.
		if (($infos['a_images'] + $infos['d_images']) > 0)
		{
			// Première partie du code servant à récupérer
			// l'id d'une image pour chaque catégorie.
			$images_count = 0;
			$params = array();
			foreach ($this->_sql['insert_images']['params'] as $a => &$p)
			{
				$count = count($p);
				if ($count < 1)
				{
					continue;
				}
				$images_count = $images_count + $count;
				$albums[$images_count - 1] = substr($a, 0, -1);
				$params = array_merge($params, $p);
			}
			$this->_sql['insert_images']['params'] = $params;

			// On INSERT les nouvelles images.
			$sql = array($this->_sql['insert_images']);
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}

			// On mémorise l'identifiant de chaque image insérée
			// associée aux informations correspondantes à cette image.
			$images_id_params = utils::$db->lastInsertIdParams;

			// On détermine l'id de la vignette de chaque catégorie.
			foreach ($albums as $id => $cat)
			{
				$id = utils::$db->lastInsertId[0][$id];
				$cat_images_id[$cat] = $id;
				while (($cat = dirname($cat)) !== '.')
				{
					$cat_images_id[$cat] = $id;
				}
			}
		}

		// On crée un tableau associatif image_path => image_id.
		$image_path_id = $this->_updateImagesPath;
		foreach ($images_id_params as $image_id => &$image_params)
		{
			$image_path_id[$image_params['image_path']] = $image_id;
		}

		// Nouvelles catégories.
		if (count($this->_sql['insert_categories']['params']) > 0)
		{
			// On ajoute l'id de la vignette de chaque catégorie.
			$cat_paths = array();
			for ($i = 0, $count = count($this->_sql['insert_categories']['params']);
			$i < $count; $i++)
			{
				$path = $this->_sql['insert_categories']['params'][$i]['cat_path'];
				$this->_sql['insert_categories']['params'][$i]['thumb_id']
					= $cat_images_id[$path];
				$cat_paths[] = $path;
			}

			// On INSERT les nouvelles catégories.
			$sql = array($this->_sql['insert_categories']);
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}

			// On récupère l'id de chaque catégorie.
			for ($i = 0, $count = count($cat_paths); $i < $count; $i++)
			{
				$categories_id[$cat_paths[$i]] = utils::$db->lastInsertId[0][$i];
			}
		}

		// On ajoute les bons 'cat_id' et 'image_position' aux nouvelles images.
		if (($infos['a_images'] + $infos['d_images']) > 0)
		{
			if (count($this->_sql['update_categories']) > 0)
			{
				for ($i = 0, $count = count($this->_sql['update_categories']);
				$i < $count; $i++)
				{
					$path = $this->_sql['update_categories'][$i]['dir'];
					$categories_id[$path] = $this->_dbCategories[$path]['cat_id'];
				}
			}
			$albums = array_flip($albums);
			$sql = 'UPDATE ' . CONF_DB_PREF . 'images
					   SET cat_id = ?,
						   image_position = image_id
					 WHERE image_path LIKE CONCAT(?, "/%")
					   AND image_position = 0';
			$params = array();
			foreach ($albums as $path => &$id)
			{
				$params[] = array($categories_id[$path], $path);
			}
			$sql = array(array('sql' => $sql, 'params' => $params));
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// On ajoute les bons 'parent_id' et 'cat_position' aux nouvelles catégories.
		if (count($this->_sql['insert_categories']['params']) > 0)
		{
			for ($i = 0, $count = count($this->_sql['insert_categories']['params']);
			$i < $count; $i++)
			{
				$path = $this->_sql['insert_categories']['params'][$i]['cat_path'];
				$this->_sql['update_insert_categories']['params'][] = array(
					'parent_id' => $categories_id[dirname($path)],
					'cat_path' => $path);
			}
			if (count($this->_sql['update_insert_categories']['params']) > 0)
			{
				$sql = array($this->_sql['update_insert_categories']);
				if (utils::$db->exec($sql, FALSE) === FALSE)
				{
					return FALSE;
				}
			}
		}

		// Mise à jour des catégories.
		if (count($this->_sql['update_categories']) > 0)
		{
			$sql = array();
			$up_cat = $this->_sql['update_categories'];
			for ($i = 0, $count = count($up_cat); $i < $count; $i++)
			{
				$cat_path = ($up_cat[$i]['dir'] === '')
					? '.'
					: $up_cat[$i]['dir'];

				// Si la catégorie est désactivée ou vide on choisi
				// une nouvelle vignette, mais à condition qu'il y ait
				// au moins une nouvelle image dans cette catégorie.
				$thumb_id = '';
				$cat_d_images = ($this->setPublishImages)
					? 0
					: $this->_dbCategories[$up_cat[$i]['dir']]['cat_d_images'];
				if ($up_cat[$i]['dir'] !== '.' &&
				($this->setUpdateThumbId || (($up_cat[$i]['images_infos']['a_images']
				+ $up_cat[$i]['images_infos']['d_images']) > 0
				&& (($this->_dbCategories[$up_cat[$i]['dir']]['cat_a_images']
				+ $cat_d_images) == 0 ||
				$this->_dbCategories[$up_cat[$i]['dir']]['thumb_id'] == -1))))
				{
					$thumb_id = 'thumb_id = "' . $cat_images_id[$up_cat[$i]['dir']] . '", ';

					// On supprime la vignette existante.
					$thumb = img::filepath(
						'tb_cat',
						$this->_dbCategories[$up_cat[$i]['dir']]['image_path'],
						$this->_dbCategories[$up_cat[$i]['dir']]['cat_id'],
						$this->_dbCategories[$up_cat[$i]['dir']]['cat_crtdt']
					);
					if (file_exists(GALLERY_ROOT . '/' . $thumb))
					{
						files::unlink(GALLERY_ROOT . '/' . $thumb);
					}
				}

				// Nombre d'albums et de catégories enfants activés.
				$cat_a_subalbs = (isset($this->_subActiveAlbs[$cat_path]))
					? $this->_subActiveAlbs[$cat_path]
					: 0;
				$cat_a_subcats = (isset($this->_subActiveCats[$cat_path]))
					? $this->_subActiveCats[$cat_path]
					: 0;

				// Nombre d'albums et de catégories enfants désactivés.
				$cat_d_subalbs = (isset($this->_subDeactiveAlbs[$cat_path]))
					? $this->_subDeactiveAlbs[$cat_path]
					: 0;
				$cat_d_subcats = (isset($this->_subDeactiveCats[$cat_path]))
					? $this->_subDeactiveCats[$cat_path]
					: 0;

				// Nombre total d'albums activés dans la catégorie.
				$cat_a_albums = ($up_cat[$i]['cat_filemtime'] === NULL)
					? $up_cat[$i]['images_infos']['a_albums']
					: 0;

				// Nombre total d'albums désactivés dans la catégorie.
				$cat_d_albums = ($up_cat[$i]['cat_filemtime'] === NULL)
					? $up_cat[$i]['images_infos']['d_albums']
					: 0;

				// Date de dernière modification du répertoire.
				$filemtime = ($up_cat[$i]['cat_filemtime'] === NULL)
					? 'NULL'
					: '"' . $up_cat[$i]['cat_filemtime'] . '"';

				$sql[] = array(
					'sql' => 'UPDATE ' . CONF_DB_PREF . 'categories
								 SET ' . $thumb_id . '
									 cat_a_size = cat_a_size + '
									 . $up_cat[$i]['images_infos']['a_size'] . ',
									 cat_d_size = cat_d_size + '
									 . $up_cat[$i]['images_infos']['d_size'] . ',
									 cat_a_subalbs = cat_a_subalbs + '
									 . $cat_a_subalbs . ',
									 cat_d_subalbs = cat_d_subalbs + '
									 . $cat_d_subalbs . ',
									 cat_a_subcats = cat_a_subcats + '
									 . $cat_a_subcats . ',
									 cat_d_subcats = cat_d_subcats + '
									 . $cat_d_subcats . ',
									 cat_a_albums = cat_a_albums + '
									 . $cat_a_albums . ',
									 cat_d_albums = cat_d_albums + '
									 . $cat_d_albums . ',
									 cat_a_images = cat_a_images + '
									 . $up_cat[$i]['images_infos']['a_images'] . ',
									 cat_d_images = cat_d_images + '
									 . $up_cat[$i]['images_infos']['d_images'] . ',
									 ' . $up_cat[$i]['cat_lastadddt'] . '
									 cat_filemtime = ' . $filemtime
									 . $up_cat[$i]['cat_status'] . '
							   WHERE cat_path = ?',
					'params' => array(array($cat_path))
				);
			}
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// Mise à jour des images.
		if (count($this->_sql['update_images']['params']) > 0)
		{
			$sql = array($this->_sql['update_images']);
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// Mise à jour du 'filemtime' des catégories.
		if (count($this->_sql['update_categories_filemtime']['params']) > 0)
		{
			$sql = array($this->_sql['update_categories_filemtime']);
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// On ajoute les nouveaux tags et les associations tag - images.
		if (count($this->_keywordsImages) > 0)
		{
			// Enregistrement des tags.
			$params = array();
			foreach ($this->_keywordsImages as $keyword => &$images)
			{
				$params[] = array(
					'tag_name' => $keyword,
					'tag_url' => utils::genURLName($keyword)
				);
			}
			$sql = array(array(
				'params' => $params,
				'sql' => 'INSERT IGNORE INTO ' . CONF_DB_PREF . 'tags
					(tag_name, tag_url) VALUES (:tag_name, :tag_url)'
			));
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}

			$this->getReport['tag_add'] = array_sum(utils::$db->nbResult[0]);

			// On récupère les informations utiles de tous les tags.
			$sql = 'SELECT tag_id,
						   tag_name
					  FROM ' . CONF_DB_PREF . 'tags';
			$fetch_style = array('column' => array('tag_id', 'tag_name'));
			if (utils::$db->query($sql, $fetch_style) === FALSE)
			{
				return FALSE;
			}
			$tags_id_name = utils::$db->queryResult;

			// Enregistrement des associations tag => images.
			$params = array();
			foreach ($tags_id_name as $tag_id => &$tag_name)
			{
				if (!isset($this->_keywordsImages[$tag_name]))
				{
					continue;
				}
				$tag_images = $this->_keywordsImages[$tag_name];
				foreach ($tag_images as &$image_path)
				{
					$params[] = array(
						'tag_id' => $tag_id,
						'image_id' => $image_path_id[$image_path]
					);
				}
			}
			$sql = array(array(
				'params' => $params,
				'sql' => 'INSERT IGNORE INTO ' . CONF_DB_PREF . 'tags_images
					(tag_id, image_id) VALUES (:tag_id, :image_id)'
			));
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// On ajoute les nouveaux fabriquants et modèles d'appareil
		// et les associations modèles d'appareil - images.
		if (count($this->_camerasImages) > 0)
		{
			// Enregistrement des fabriquants d'appareils.
			$params = array();
			foreach ($this->_camerasImages as $make => &$models)
			{
				$params[] = array(
					'camera_brand_name' => $make,
					'camera_brand_url' => utils::genURLName($make)
				);
			}
			$sql = array(array(
				'params' => $params,
				'sql' => 'INSERT IGNORE INTO ' . CONF_DB_PREF . 'cameras_brands
					(camera_brand_name, camera_brand_url)
					VALUES (:camera_brand_name, :camera_brand_url)'
			));
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}

			// On récupère les informations utiles de tous les fabriquants.
			$sql = 'SELECT camera_brand_id,
						   camera_brand_name
					  FROM ' . CONF_DB_PREF . 'cameras_brands';
			$fetch_style = array('column' => array('camera_brand_name', 'camera_brand_id'));
			if (utils::$db->query($sql, $fetch_style) === FALSE)
			{
				return FALSE;
			}
			$camera_brand_name_id = utils::$db->queryResult;

			// Enregistrement des modèles d'appareils.
			$params = array();
			foreach ($this->_camerasImages as $make => &$models)
			{
				foreach ($models as $model => &$images)
				{
					if (!isset($camera_brand_name_id[$make]))
					{
						continue;
					}
					$params[] = array(
						'camera_brand_id' => (int) $camera_brand_name_id[$make],
						'camera_model_name' => $model,
						'camera_model_url' => utils::genURLName($model)
					);
				}
			}
			$sql = array(array(
				'params' => $params,
				'sql' => 'INSERT IGNORE INTO ' . CONF_DB_PREF . 'cameras_models
					(camera_brand_id, camera_model_name, camera_model_url)
					VALUES (:camera_brand_id, :camera_model_name, :camera_model_url)'
			));
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}

			$this->getReport['camera_add'] = array_sum(utils::$db->nbResult[0]);

			// On récupère les informations utiles de tous les modèles.
			$sql = 'SELECT camera_model_id,
						   camera_model_name,
						   camera_brand_name
					  FROM ' . CONF_DB_PREF . 'cameras_models
				 LEFT JOIN ' . CONF_DB_PREF . 'cameras_brands USING (camera_brand_id)';
			$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'camera_model_id');
			if (utils::$db->query($sql, $fetch_style) === FALSE)
			{
				return FALSE;
			}
			$camera_model_id_infos = utils::$db->queryResult;

			// Enregistrement des associations modèles d'appareil - images.
			$params = array();
			foreach ($camera_model_id_infos as $model_id => &$infos)
			{
				if (!isset($this->_camerasImages[$infos['camera_brand_name']]
				[$infos['camera_model_name']]))
				{
					continue;
				}
				$camera_model_images = $this->_camerasImages[$infos['camera_brand_name']]
					[$infos['camera_model_name']];
				foreach ($camera_model_images as &$image_path)
				{
					$params[] = array(
						'camera_model_id' => $model_id,
						'image_id' => $image_path_id[$image_path]
					);
				}
			}
			$sql = array(array(
				'params' => $params,
				'sql' => 'INSERT IGNORE INTO ' . CONF_DB_PREF . 'cameras_models_images
					(camera_model_id, image_id) VALUES (:camera_model_id, :image_id)'
			));
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		$this->_sql = NULL;

		// Contrôle de la cohérence de la table des images.
		$sql = 'SELECT 1
				  FROM ' . CONF_DB_PREF . 'images
				 WHERE cat_id = 1
				    OR image_position = 0
				 LIMIT 1';
		if (utils::$db->query($sql) === FALSE
		|| utils::$db->nbResult > 0)
		{
			trigger_error('Incoherent table "images".', E_USER_WARNING);
			return FALSE;
		}

		// Contrôle de la cohérence de la table des catégories.
		$sql = 'SELECT 1
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE cat_id != 1
				   AND ((thumb_id = 0 AND cat_a_images + cat_d_images = 0) OR
					   parent_id = 0 OR cat_position = 0)
				 LIMIT 1';
		if (utils::$db->query($sql) === FALSE
		|| utils::$db->nbResult > 0)
		{
			trigger_error('Incoherent table "categories".', E_USER_WARNING);
			return FALSE;
		}

		// Mode simulation.
		if ($this->setSimulate)
		{
			utils::$db->rollback();
			return TRUE;
		}

		// Exécution de la transaction.
		if ($this->setTransaction && !utils::$db->commit())
		{
			return FALSE;
		}

		// Notification par e-mail.
		if ($this->setMailAlert)
		{
			$mail = new mail();
			$i = array(
				'user_id' => $this->setUserId,
				'user_login' => $this->setUserLogin
			);
			$this->getNotifyGroups = $mail->notify(
				'images', array_flip($albums), $this->setUserId, $i,
				$this->setNotifyGroupsExclude);
			$mail->send();
		}

		return TRUE;
	}

	/**
	 * Vérifie si les informations utiles d'une image sont différentes de celles
	 * enregistrées dans la base de données, et met à jour celle-ci le cas échéant.
	 *
	 * @param string $album
	 *	Chemin du répertoire parent de $image.
	 * @param string $image
	 *	Nom de fichier de l'image à vérifier.
	 * @param array $db_infos
	 *	Informations de l'image provenant de la base de données.
	 * @return boolean|array
	 */
	private function _updateImage($album, $image, $db_infos)
	{
		// Si l'option de mise à jour des images est désactivée, on arrête là.
		if (!$this->setUpdateImages)
		{
			return FALSE;
		}

		// Informations à mettre à jour pour les catégories parentes.
		$updade_infos = array();
		$updade_infos['a_size'] = 0;
		$updade_infos['d_size'] = 0;

		// Récupération du poids et des dimensions de l'image.
		$file = $this->_albumsPath . '/' . $album . $image;
		if (($image_size = getimagesize($file, $image_infos)) === FALSE)
		{
			return FALSE;
		}
		if (($filesize = filesize($file)) === FALSE)
		{
			return FALSE;
		}

		// Récupération des métadonnées.
		$this->_getMetadata($file);

		// Titre.
		$image_name = $this->_getMetadataInfo('title');
		if ($image_name === NULL)
		{
			$image_name = $db_infos['image_name'];
		}

		// Description.
		$image_desc = $this->_getMetadataInfo('desc');
		if ($image_desc === NULL)
		{
			$image_desc = $db_infos['image_desc'];
		}
		$image_desc = utils::isEmpty($image_desc)
			? NULL
			: $image_desc;

		// Date de création.
		$image_crtdt = $this->_getMetadataInfo('crtdt');
		if ($image_crtdt === NULL)
		{
			$image_crtdt = $db_infos['image_crtdt'];
		}

		// Latitude.
		$image_latitude = $this->_getMetadataInfo('latitude');
		if ($image_latitude === NULL)
		{
			$image_latitude = $db_infos['image_lat'];
		}

		// Longitude.
		$image_longitude = $this->_getMetadataInfo('longitude');
		if ($image_longitude === NULL)
		{
			$image_longitude = $db_infos['image_long'];
		}

		// Tables de metadonnées.
		$this->_metadataTables($album . $image);

		// Les informations de l'image sont-elles différentes
		// de celles enregistrées en base de données ?
		if ($filesize != $db_infos['image_filesize']
		|| $image_size[0] != $db_infos['image_width']
		|| $image_size[1] != $db_infos['image_height']
		|| $image_name != $db_infos['image_name']
		|| $image_desc !== $db_infos['image_desc']
		|| $image_latitude != $db_infos['image_lat']
		|| $image_longitude != $db_infos['image_long']
		|| $image_crtdt != $db_infos['image_crtdt'])
		{
			$diff_filesize = $filesize - $db_infos['image_filesize'];

			// Si le statut de l'image est 'publiée'.
			if ($db_infos['image_status'] == 1)
			{
				$updade_infos['a_size'] += $diff_filesize;
			}
			else
			{
				$updade_infos['d_size'] += $diff_filesize;
			}

			// Paramètres de la requête préparée.
			$this->_sql['update_images']['params'][] = array(
				'image_id' => $db_infos['image_id'],
				'image_path' => $db_infos['image_path'],
				'image_width' => (int) $image_size[0],
				'image_height' => (int) $image_size[1],
				'image_filesize' => (int) $filesize,
				'image_lat' => $image_latitude,
				'image_long' => $image_longitude,
				'image_name' => $image_name,
				'image_url' => utils::genURLName($image_name),
				'image_desc' => $image_desc,
				'image_crtdt' => $image_crtdt
			);

			$this->getReport['img_update']++;
		}

		$this->_updateImagesPath[$db_infos['image_path']] = $db_infos['image_id'];
		return $updade_infos;
	}
}
?>