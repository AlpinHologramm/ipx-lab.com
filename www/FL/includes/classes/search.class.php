<?php
/**
 * Méthodes pour moteur de recherche.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class search
{
	/**
	 * Champs de la table "image" pour la recherche
	 * des images dans l'administration.
	 *
	 * @var array
	 */
	public static $searchAdminImageFields = array(
		'image_desc',
		'image_name',
		'image_path',
		'image_url'
	);

	/**
	 * Options de recherche des images dans l'administration.
	 *
	 * @var array
	 */
	public static $searchAdminImageOptions = array(
		'all_words' => 'bin',
		'date' => 'bin',
		'date_field' => '(?:image_adddt|image_crtdt)',
		'date_end_day' => '\d{2}',
		'date_end_month' => '\d{2}',
		'date_end_year' => '\d{4}',
		'date_start_day' => '\d{2}',
		'date_start_month' => '\d{2}',
		'date_start_year' => '\d{4}',
		'exclude' => 'bin',
		'exclude_filter' => '(?:crtdt|desc|geoloc|place|tags)',
		'filesize' => 'bin',
		'filesize_end' => '\d{1,5}',
		'filesize_start' => '\d{1,5}',
		'image_desc' => 'bin',
		'image_name' => 'bin',
		'image_path' => 'bin',
		'image_tags' => 'bin',
		'image_url' => 'bin',
		'size' => 'bin',
		'size_height_end' => '\d{1,5}',
		'size_height_start' => '\d{1,5}',
		'size_width_end' => '\d{1,5}',
		'size_width_start' => '\d{1,5}',
		'status' => '(?:all|publish|unpublish)',
		'type' => 'album',
		'user' => '(?:all|\d{1,11})'
	);



	/**
	 * Fabrication de la clause WHERE pour une recherche
	 * dans les images coté administration.
	 *
	 * @param array $fiels
	 *	Champs dans lesquels effectuer la recherche.
	 * @param array $sql_albums_access
	 *	Permissions de l'utilisateur.
	 * @return array
	 */
	public static function getAdminImagesSQLWhere($fields, $sql_albums_access)
	{
		$search_images_id = array();

		// Tags.
		if (isset($_GET['search_image_tags']))
		{
			// Clause WHERE pour la recherche dans les tags.
			$_GET['search_tag_name'] = 1;
			$sql_where_tags = self::getSQLWhere(array('tag_name'), TRUE, TRUE);

			// Récupération des identifiants des images liées aux tags trouvés.
			if (is_array($sql_where_tags))
			{
				$sql = 'SELECT img.image_id
						  FROM ' . CONF_DB_PREF . 'tags AS t
					 LEFT JOIN ' . CONF_DB_PREF . 'tags_images AS ti
							ON t.tag_id = ti.tag_id
					 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
							ON img.image_id = ti.image_id
					 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
							ON img.cat_id = cat.cat_id
						 WHERE ' . $sql_where_tags['sql']
								 . $sql_albums_access;
				$fetch_style = array('column' => array('image_id', 'image_id'));
				if (utils::$db->prepare($sql) !== FALSE
				&& utils::$db->executeQuery($sql_where_tags['params'], $fetch_style) !== FALSE
				&& utils::$db->nbResult > 0)
				{
					$search_images_id = array_merge(
						$search_images_id, utils::$db->queryResult
					);
				}
			}
		}

		// Recherche.
		$sql_search = self::getSQLWhere($fields, TRUE, TRUE, $search_images_id);
		if (!$sql_search
		 || $sql_search['params'] === NULL
		 || $sql_search['sql'] === NULL)
		{
			return FALSE;
		}

		// Exclusions.
		if (isset($_GET['search_exclude']) && isset($_GET['search_exclude_filter']))
		{
			switch ($_GET['search_exclude_filter'])
			{
				// Date de création.
				// Description.
				// Lieu.
				case 'crtdt' :
				case 'desc' :
				case 'place' :
					$sql_search['sql'] .=
						' AND image_' . $_GET['search_exclude_filter'] . ' IS NULL';
					break;

				// Géolocalisation.
				case 'place' :
					$sql_search['sql'] .=
						' AND image_lat IS NULL
						  AND image_long IS NULL';
					break;

				// Tags.
				case 'tags' :
					$sql_search['sql'] .=
						' AND (SELECT COUNT(*)
								 FROM ' . CONF_DB_PREF. 'tags_images AS ti
								WHERE ti.image_id = img.image_id) = 0';
					break;
			}
		}

		// Statut.
		if (isset($_GET['search_status']))
		{
			switch ($_GET['search_status'])
			{
				case 'publish' :
					$sql_search['sql'] .= ' AND image_status = "1"';
					break;

				case 'unpublish' :
					$sql_search['sql'] .= ' AND image_status = "0"';
					break;
			}
		}

		// Utilisateur.
		if (isset($_GET['search_user']) && preg_match('`^\d{1,11}$`', $_GET['search_user']))
		{
			$sql_search['sql'] .= ' AND img.user_id = ' . (int) $_GET['search_user'];
		}

		return array(
			'params' => $sql_search['params'],
			'sql' => $sql_search['sql']
		);
	}

	/**
	 * Fabrication de la clause WHERE pour une recherche dans les images,
	 * incluant la recherche dans les tags, commentaires,
	 * marques et modèles d'appareils photos liés aux images.
	 *
	 * @param array $user_perms
	 *	Permissions de l'utilisateur.
	 * @return array
	 */
	public static function getImagesSQLWhere($user_perms)
	{
		$search_images_id = array();

		// Recherche dans les tags.
		if (utils::$config['tags'] && isset($_GET['search_tags']))
		{
			// Clause WHERE pour la recherche dans les tags.
			$_GET['search_tag_name'] = 1;
			$sql_where = self::getSQLWhere(array('tag_name'), TRUE, TRUE);

			// Récupération des identifiants des images liées aux tags trouvés.
			if (is_array($sql_where))
			{
				$sql = 'SELECT img.image_id
						  FROM ' . CONF_DB_PREF . 'tags AS t
					 LEFT JOIN ' . CONF_DB_PREF . 'tags_images AS ti
							ON t.tag_id = ti.tag_id
					 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
							ON img.image_id = ti.image_id
					 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
							ON img.cat_id = cat.cat_id
						 WHERE %s
						   AND ' . $sql_where['sql'];
				$fetch_style = array('column' => array('image_id', 'image_id'));
				$result = sql::sqlProtect('image', $sql, $fetch_style,
					FALSE, $sql_where['params']);
				if ($result !== FALSE && $result['nb_result'] > 0)
				{
					$search_images_id = array_merge(
						$search_images_id, $result['query_result']
					);
				}
			}
		}

		// Recherche dans les commentaires.
		// Les commentaires doivent être activés
		// et l'utilisateur doit avoir la permission de les lire.
		if (utils::$config['comments']
		&& (utils::$config['users'] != 1
		 || $user_perms['gallery']['perms']['read_comments'])
		&& isset($_GET['search_comments']))
		{
			// Clause WHERE pour la recherche dans les commentaires.
			$_GET['search_com_message'] = 1;
			$sql_where = self::getSQLWhere(array('com_message'), TRUE, TRUE);

			// Récupération des identifiants des images liées aux commentaires trouvés.
			if (is_array($sql_where))
			{
				$sql = 'SELECT img.image_id
						  FROM ' . CONF_DB_PREF . 'comments AS com
					 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
							ON com.image_id = img.image_id
					 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
							ON img.cat_id = cat.cat_id
						 WHERE %s
						   AND com_status = "1"
						   AND ' . $sql_where['sql'];
				$fetch_style = array('column' => array('image_id', 'image_id'));
				$result = sql::sqlProtect('image', $sql, $fetch_style,
					FALSE, $sql_where['params']);
				if ($result !== FALSE && $result['nb_result'] > 0)
				{
					$search_images_id = array_merge(
						$search_images_id, $result['query_result']
					);
				}
			}
		}

		// Recherche dans les marques et modèles d'appareils photos.
		foreach (array('brand', 'model') as $type)
		{
			if (isset($_GET['search_' . $type . 's']))
			{
				// Clause WHERE pour la recherche dans les marques.
				$_GET['search_camera_' . $type . '_name'] = 1;
				$sql_where = self::getSQLWhere(
					array('camera_' . $type . '_name'), TRUE, TRUE
				);

				// Récupération des identifiants des images liées
				// aux marques ou modèles trouvés.
				if (is_array($sql_where))
				{
					$sql = 'SELECT img.image_id
							  FROM ' . CONF_DB_PREF . 'cameras_models AS cam_m
						 LEFT JOIN ' . CONF_DB_PREF . 'cameras_brands AS cam_b
								ON cam_m.camera_brand_id = cam_b.camera_brand_id
						 LEFT JOIN ' . CONF_DB_PREF . 'cameras_models_images AS cam_mi
								ON cam_m.camera_model_id = cam_mi.camera_model_id
						 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
								ON cam_mi.image_id = img.image_id
						 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
								ON img.cat_id = cat.cat_id
							 WHERE %s
							   AND ' . $sql_where['sql'];
					$fetch_style = array('column' => array('image_id', 'image_id'));
					$result = sql::sqlProtect('image', $sql, $fetch_style,
						FALSE, $sql_where['params']);
					if ($result !== FALSE && $result['nb_result'] > 0)
					{
						$search_images_id = array_merge(
							$search_images_id, $result['query_result']
						);
					}
				}
			}
		}

		return self::getSQLWhere(array('image_desc', 'image_name'),
			TRUE, TRUE, $search_images_id);
	}

	/**
	 * Retourne la partie de la clause WHERE pour la récupération
	 * d'objets selon les critères de recherche fournis par $_GET.
	 *
	 * @param array $fields
	 *	Champs autorisés où effectuer la recherche.
	 * @param boolean $table_image
	 *	S'agit-il d'une recherche dans la table des images ?
	 * @param boolean $search_date
	 *	S'agit-il d'une recherche avec la date comme critère possible ?
	 * @param array $search_images_id
	 *	Identifiants des images à ajouter à la clause WHERE.
	 * @return boolean|array
	 *	Retourne FALSE si la clause WHERE est vide,
	 *	ou un tableau associatif avec, comme clés :
	 *	'params' : paramètres pour la requête préparée (array).
	 *	'sql'    : partie de la clause WHERE pour la recherche (string).
	 */
	public static function getSQLWhere($fields, $table_image = FALSE, $search_date = FALSE,
	$search_images_id = array())
	{
		if (!isset($_GET['search_query']))
		{
			return FALSE;
		}

		// Nettoyage de la requête.
		$query = trim($_GET['search_query']);
		$query = preg_replace('`-+`', '-', $query);
		$query = str_replace('- ', '', $query);
		$query = str_replace(' *', '', $query);
		$query = preg_replace('`\s+`', ' ', $query);

		// Méthodes « AND » ou « OR ».
		$method = (isset($_GET['search_all_words'])) ? 'AND' : 'OR';

		// On ne tient pas compte de la casse.
		$query = mb_strtolower($query);

		// Décomposition de la recherche, sauf pour
		// les parties qui se trouvent entre guillemets.
		$query = preg_split('`\s+(?!.*[^-\s]")`i', $query, -1, PREG_SPLIT_NO_EMPTY);

		$sql_fields = array();
		$params = array();
		$p = 1;

		// Pour chaque partie de la requête.
		foreach ($query as $q)
		{
			// Suppression des guillemets.
			$q = str_replace('"', '', $q);

			// Remplacement des caractères non-alphanumériques.
			$q = mb_ereg_replace('[^-\w\*\?\'\s]', '?', $q);

			// On ne tient pas compte des accents (langues européennes).
			// Ne fonctionne pas correctement à cause des limitations
			// du moteur d'expressions régulières de MySQL
			// (http://dev.mysql.com/doc/refman/5.0/en/regexp.html).
			// D'où recourt à preg_match() et à CONVERT() (voir plus loin).
			$utf8 = TRUE;
			if (preg_match('`^[\x20-\x7e\xc2a0-\xc3bf]+$`', $q))
			{
				$q = utils::regexpAccents($q);
				$utf8 = FALSE;
			}

			// Doit-on inclure ou exclure cette partie de la requête ?
			$sql_not = '';
			$sql_method = $method;
			if ($q{0} == '-')
			{
				$q = substr($q, 1);
				$sql_not = 'NOT ';
				$sql_method = 'AND';
			}

			// Si la requête est vide, inutile d'aller plus loin.
			if (trim($q) == '')
			{
				continue;
			}

			// Jokers et espaces.
			$q = str_replace(
				array(' ', '*', '?'),
				array('[^[:alnum:]]', '[^[:space:]]*', '.'),
				$q
			);

			// On ne recherche que des mots entiers.
			$q = '([[:<:]]|^)' . $q . '([[:>:]]|$)';

			// Champs de recherche.
			foreach ($fields as $name)
			{
				if (isset($_GET['search_' . $name]))
				{
					if (!isset($sql_fields[$name]))
					{
						$sql_fields[$name] = '';
					}
					$sql_name = ($utf8)
						? $name
						: 'CONVERT(' . $name . ' USING LATIN1)';
					$sql_fields[$name] .= $sql_method
						. ' LOWER(' . $sql_name . ') '
						. $sql_not . 'REGEXP :q_' . $p . ' ';
					$params['q_' . $p] = ($name == 'cat_path' || $name == 'image_path')
						? '/' . str_replace('[^[:space:]]*', '[^/[:space:]]*', $q) . '$'
						: $q;
				}
			}
			$p++;
		}

		// Préparation de la clause WHERE de la requête.
		$sql = '';
		foreach ($sql_fields as $f)
		{
			if ($f)
			{
				$sql .= 'OR (' . preg_replace('`^(?:AND|OR) `', '', $f) . ') ';
			}
		}

		if ($sql == '' && count($search_images_id) === 0)
		{
			return FALSE;
		}

		if ($sql != '')
		{
			$sql = '(' . preg_replace('`^OR `', '', $sql) . ')';

			// Recherche par date.
			$sql_date = '';
			if ($search_date
			&& isset($_GET['search_date'])
			&& isset($_GET['search_date_field'])
			&& isset($_GET['search_date_end_day'])
			&& isset($_GET['search_date_end_month'])
			&& isset($_GET['search_date_end_year'])
			&& isset($_GET['search_date_start_day'])
			&& isset($_GET['search_date_start_month'])
			&& isset($_GET['search_date_start_year']))
			{
				$field = $_GET['search_date_field'];
				$sql_start_date = (int) $_GET['search_date_start_year']
					. '-' . (int) $_GET['search_date_start_month']
					. '-' . (int) $_GET['search_date_start_day'];
				$sql_end_date = (int) $_GET['search_date_end_year']
					. '-' . (int) $_GET['search_date_end_month']
					. '-' . (int) $_GET['search_date_end_day'];
				$sql_date = ' AND ' . $field . ' >= "' . $sql_start_date . ' 00:00:00"'
					 . ' AND ' . $field . ' <= "' . $sql_end_date . ' 23:59:59"';

				// Exception pour le champ 'image_crtdt'.
				if ($field == 'image_crtdt')
				{
					$sql_date = str_replace(
						array(' 00:00:00', ' 23:59:59'),
						array('', ''),
						$sql_date
					);
				}
			}

			// Recherche par dimensions.
			$sql_size = '';
			if ($table_image
			&& isset($_GET['search_size'])
			&& isset($_GET['search_size_height_end'])
			&& isset($_GET['search_size_height_start'])
			&& isset($_GET['search_size_width_end'])
			&& isset($_GET['search_size_width_start']))
			{
				$sql_size = ' AND image_height >= ' . (int) $_GET['search_size_height_start']
						  . ' AND image_height <= ' . (int) $_GET['search_size_height_end']
						  . ' AND image_width >= ' . (int) $_GET['search_size_width_start']
						  . ' AND image_width <= ' . (int) $_GET['search_size_width_end'];
			}

			// Recherche par poids.
			$sql_filesize = '';
			if ($table_image
			&& isset($_GET['search_filesize'])
			&& isset($_GET['search_filesize_end'])
			&& isset($_GET['search_filesize_start']))
			{
				$end = (int) $_GET['search_filesize_end'] * 1024;
				$start = (int) $_GET['search_filesize_start'] * 1024;
				$sql_filesize = ' AND image_filesize >= ' . $start
							  . ' AND image_filesize <= ' . $end;
			}

			// Recherche par catégories.
			$sql_categories = '';
			if (isset($_GET['search_categories'])
			&& is_array($_GET['search_categories']))
			{
				$sql_categories .= self::_sqlWhereCategories();
			}

			// On ajoute les conditions de recherche
			// à la recherche dans les champs.
			$sql = ' (' . $sql . $sql_date . $sql_size
				. $sql_filesize . $sql_categories . ')';
		}

		// Ajout d'identifiants d'images.
		if (count($search_images_id) > 0)
		{
			$search_images_id = array_map('intval', $search_images_id);
			$sql_images_id = 'image_id IN (' . implode(', ', $search_images_id) . ')';
			$sql = ($sql == '')
				? '(' . $sql_images_id . ')'
				: '(' . $sql . ' OR ' . $sql_images_id . ')';
		}

		return array('sql' => $sql, 'params' => $params);
	}

	/**
	 * Gestion de la requête du moteur de recherche dans la galerie.
	 *
	 * @return mixed
	 */
	public static function galleryPostGet()
	{
		// Options 'cases à cocher'.
		$options = array(
			'all_words',
			'comments',
			'date',
			'filesize',
			'image_desc',
			'brands',
			'models',
			'image_name',
			'size',
			'tags'
		);

		// Options avec vérification par regexp.
		$options_patterns = array(
			'date_field' => 'image_(?:adddt|crtdt)',
			'date_end_day' => '\d{2}',
			'date_end_month' => '\d{2}',
			'date_end_year' => '\d{4}',
			'date_start_day' => '\d{2}',
			'date_start_month' => '\d{2}',
			'date_start_year' => '\d{4}',
			'filesize_end' => '\d{1,5}',
			'filesize_start' => '\d{1,5}',
			'size_height_end' => '\d{1,5}',
			'size_height_start' => '\d{1,5}',
			'size_width_end' => '\d{1,5}',
			'size_width_start' => '\d{1,5}'
		);

		// POST.
		if (isset($_POST['search_query'])
		&& !utils::isEmpty($_POST['search_query'])
		&& mb_strlen($_POST['search_query']) <= 255)
		{
			// Options de recherche par défaut.
			$search_options = array(
				'image_desc' => 1,
				'image_name' => 1,
				'tags' => 1
			);

			// Options de recherche envoyées par l'utilisateur.
			if (utils::$config['search_advanced']
			&& isset($_POST['search_options'])
			&& is_array($_POST['search_options']))
			{
				// Vérifications des options.
				foreach ($_POST['search_options'] as $o => $v)
				{
					// Options avec vérifications par regexp.
					if ((isset($options_patterns[$o])
					&& preg_match('`^' . $options_patterns[$o] . '$`', $v)))
					{
						$_POST['search_options'][$o] = $v;
						continue;
					}

					// Cases à cocher.
					else if (in_array($o, $options))
					{
						$_POST['search_options'][$o] = 1;
						continue;
					}

					// Catégories.
					else if ($o == 'categories' && is_array($v))
					{
						sort($v);
						$_POST['search_options'][$o] = array_map('intval', $v);
						continue;
					}

					unset($_POST['search_options'][$o]);
				}

				$search_options = $_POST['search_options'];
			}

			// Identifiant de recherche.
			$search_id = utils::genKey(FALSE, 12);

			// Enregistrement de la requête en base de données.
			$sql = 'INSERT INTO ' . CONF_DB_PREF . 'search (
				search_id,
				search_query,
				search_options,
				search_date
				) VALUES (
				:search_id,
				:search_query,
				:search_options,
				NOW())';
			$params = array(
				'search_id' => $search_id,
				'search_query' => $_POST['search_query'],
				'search_options' => serialize($search_options)
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE
			|| utils::$db->nbResult === 0)
			{
				return FALSE;
			}

			// Redirection.
			utils::redirect('search/' . $search_id, TRUE);
		}

		// GET.
		else if (isset($_GET['search']))
		{
			// Récupération de la requête par son identifiant.
			$sql = 'SELECT search_query,
						   search_options
					  FROM ' . CONF_DB_PREF . 'search
					 WHERE search_id = :search_id';
			$params = array('search_id' => $_GET['search']);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($params, 'row') === FALSE)
			{
				return utils::$db->msgError;
			}
			if (utils::$db->nbResult === 0)
			{
				return NULL;
			}
			$search = utils::$db->queryResult;
			$search_options = unserialize($search['search_options']);

			// Requête.
			$_GET['search_query'] = $search['search_query'];

			// Options.
			foreach ($search_options as $o => &$v)
			{
				// Options avec vérifications par regexp.
				if ((isset($options_patterns[$o])
				&& preg_match('`^' . $options_patterns[$o] . '$`', $v)))
				{
					$_GET['search_' . $o] = $v;
					continue;
				}

				// Cases à cocher.
				else if (in_array($o, $options))
				{
					$_GET['search_' . $o] = 1;
					continue;
				}

				// Catégories.
				else if ($o == 'categories' && is_array($v))
				{
					sort($v);
					for ($i = 0, $count_v = count($v); $i < $count_v; $i++)
					{
						$v[$i] = (int) $v[$i];
						if (strlen($v[$i]) > 11)
						{
							unset($v[$i]);
						}
					}
					$_GET['search_categories'] = $v;
					continue;
				}
			}
		}
	}



	/**
	 * Construit la partie de la clause WHERE pour la recherche par catégories.
	 *
	 * @return string
	 */
	private static function _sqlWhereCategories()
	{
		static $search_categories = '';
		if ($search_categories !== '')
		{
			return $search_categories;
		}

		// Récupération du chemin des catégories sélectionnées.
		$sql = 'SELECT cat_id,
					   cat_path
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE cat_id > 1
				   AND cat_id IN (' . implode(', ', array_map('intval',
					   $_GET['search_categories'])) . ')';
		$fetch_style = array('column' => array('cat_id', 'cat_path'));
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return '';
		}

		foreach (utils::$db->queryResult as &$path)
		{
			$search_categories .= ' OR image_path LIKE "'
				. utils::filters($path, 'path') . '/%%"';
		}
		return $search_categories = ' AND (' . substr($search_categories, 4) . ')';
	}
}
?>