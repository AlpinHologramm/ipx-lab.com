<?php
/**
 * Méthodes de template communes.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class template
{
	/**
	 * Informations de l'objet utilisées pour le modèle de description.
	 *
	 * @var array
	 */
	private static $_descTplInfos;

	/**
	 * Type de l'objet (image ou catégorie).
	 *
	 * @var string
	 */
	private static $_descTplType;

	/**
	 * Plan de la galerie.
	 *
	 * @see function _constructMap
	 * @var array
	 */
	private static $_map;



	/**
	 * Génère les listes déroulantes pour sélectionner une date.
	 *
	 * @param string $date
	 * @param integer $year_start
	 * @param string $name_format
	 * @param boolean $time
	 * @return string
	 */
	public static function dateSelect($date = NULL, $year_start = 1900, $name_format = '%s',
	$time = FALSE)
	{
		$day = 0;
		$month = 0;
		$year = 0;
		$hour = 0;
		$minute = 0;
		$second = 0;
		$regex = '`^((?!0000)\d{4})-((?!00)\d{2})-((?!00)\d{2})(?:\s(\d{2}):(\d{2}):(\d{2}))?$`';
		if (preg_match($regex, $date, $m))
		{
			$year = $m[1];
			$month = $m[2];
			$day = $m[3];
			if (isset($m[4]))
			{
				$hour = $m[4];
				$minute = $m[5];
				$second = $m[6];
			}
		}

		// Jours.
		$select_days = '<select class="day" name="' . sprintf($name_format, 'day') . '">';
		$select_days .= '<option class="date_title" value="00">' . __('Jour') . '</option>';
		for ($d = 1; $d <= 31; $d++)
		{
			$d = str_pad($d, 2, '0', STR_PAD_LEFT);
			$selected = ($d == $day) ? ' selected="selected"' : '';
			$select_days .= '<option' . $selected . ' value="' . $d . '">' . $d . '</option>';
		}
		$select_days .= '</select>';

		// Mois.
		$select_months = '<select class="month" name="' . sprintf($name_format, 'month') . '">';
		$select_months .= '<option class="date_title" value="00">' . __('Mois') . '</option>';
		for ($m = 1; $m <= 12; $m++)
		{
			$m = str_pad($m, 2, '0', STR_PAD_LEFT);
			$selected = ($m == $month) ? ' selected="selected"' : '';
			$select_months .= '<option' . $selected . ' value="' . $m . '">'
				. utils::localeTime('%B', date('Y-' . $m . '-01')) . '</option>';
		}
		$select_months .= '</select>';

		// Années.
		$select_years = '<select class="year" name="' . sprintf($name_format, 'year') . '">';
		$select_years .= '<option class="date_title" value="0000">' . __('Année') . '</option>';
		for ($y = date('Y'), $current_year = $year_start; $y >= $current_year; $y--)
		{
			$selected = ($y == $year) ? ' selected="selected"' : '';
			$select_years .= '<option' . $selected . ' value="' . $y . '">' . $y . '</option>';
		}
		$select_years .= '</select>';

		// Heure, minute, seconde.
		$select_time = '';
		if ($time)
		{
			$select_time = '&nbsp;' . __('à') . '&nbsp;';
			foreach (array('hour','minute','second') as $name)
			{
				$select_time .= '<select class="' . $name . '" name="'
					. sprintf($name_format, $name) . '">';
				$max = ($name == 'hour') ? 23 : 59;
				for ($u = 0; $u <= $max; $u++)
				{
					$class = !$u ? ' class="date_title"' : '';
					$u = str_pad($u, 2, '0', STR_PAD_LEFT);
					$selected = ($u == $$name) ? ' selected="selected"' : '';
					$select_time .= '<option' . $class . $selected . ' value="' . $u . '">'
						. $u . '</option>';
				}
				$select_time .= '</select>';
				if ($name != 'second')
				{
					$select_time .= ":";
				}
			}
		}

		return "$select_days&nbsp;\n$select_months&nbsp;\n$select_years$select_time";
	}

	/**
	 * Génère un tableau HTML contenant les informations utiles des requêtes SQL.
	 *
	 * @return string
	 */
	public static function debugSQL()
	{
		if (utils::$config['debug_sql'] != 1)
		{
			return;
		}
		$queries =& utils::$db->queries;

		$total_time = 0;
		$debug_sql = '<table id="debug_sql"><tr>
			<th class="num">#</th>
			<th class="file">' . __('Fichier') . '</th>
			<th class="line">' . __('Ligne') . '</th>
			<th class="sql">' . __('Requête SQL') . '</th>
			<th class="result">' . __('Résultat') . '</th>
			<th class="nb_result">' . __('Nombre de lignes affectées') . '</th>
			<th class="time">' . __('Temps d\'exécution') . '</th>
		</tr>';

		for ($i = 0, $count_i = count($queries); $i < $count_i; $i++)
		{
			// Fichier.
			$file = $queries[$i]['file'];
			if (strpos($file, GALLERY_ROOT) === 0)
			{
				$file = substr($file, strlen(GALLERY_ROOT) + 1);
			}

			// Requête(s) SQL.
			if (is_array($queries[$i]['sql']))
			{
				$query = '';
				for ($n = 0, $count_n = count($queries[$i]['sql']); $n < $count_n; $n++)
				{
					if ($count_n > 1)
					{
						$query .= '<span class="q">[' . ($n + 1) . ']</span>';
					}
					if (is_array($queries[$i]['sql'][$n]))
					{
						$nb = count($queries[$i]['sql'][$n]['params']);
						$query .= nl2br(htmlentities($queries[$i]['sql'][$n]['sql']));
						$query .= '<br />';
						$query .= '<span class="params">';
						$query .= sprintf(__('nombre de lignes : %s'), $nb);
						$query .= '</span>';
					}
					else
					{
						$query .= nl2br(htmlentities($queries[$i]['sql'][$n]));
					}
					$query .= '<hr />';
				}
				$query = substr($query, 0, -6);
			}
			else
			{
				$query = nl2br(htmlentities($queries[$i]['sql']));
			}

			// Résultat.
			$result = ($queries[$i]['result'] == 'SUCCESS')
				? '<span class="success">' . __('succès') . '</span>'
				: '<span class="failure">' . __('échec') . '</span>';

			// Exception.
			$exception = '';
			if (!empty($queries[$i]['exception']))
			{
				$exception .= '<br /><br />';
				$exception .= nl2br(htmlentities($queries[$i]['exception']));
			}

			// Nombre de lignes affectées.
			$nb_result = 0;
			if (is_array($queries[$i]['nb_result']))
			{
				for ($n = 0, $count_n = count($queries[$i]['nb_result']); $n < $count_n; $n++)
				{
					$r =& $queries[$i]['nb_result'][$n];
					$nb_result += (is_array($r)) ? array_sum($r) : $r;
				}
			}
			else
			{
				$nb_result = $queries[$i]['nb_result'];
			}

			// Durée d'exécution.
			$time = utils::numeric(sprintf('%0.3f ms', $queries[$i]['time'] * 1000));
			$total_time += $queries[$i]['time'];

			// Ligne du tableau.
			$debug_sql .= '<tr>
				<td class="num">' . ($i + 1) . '</td>
				<td class="file">' . $file . '</td>
				<td class="line">' . $queries[$i]['line'] . '</td>
				<td class="sql">' . $query . '</td>
				<td class="result">' . $result . $exception . '</td>
				<td class="nb_result">' . (int) $nb_result . '</td>
				<td class="time">' . $time . '</td>
			</tr>';
		}

		// Durée totale d'exécution des requêtes.
		$total_time = utils::numeric(sprintf('%0.3f ms', $total_time * 1000));
		$debug_sql .= '<tr id="total">
			<td class="num">*</td>
			<td class="file"></td>
			<td class="line"></td>
			<td class="sql"></td>
			<td class="result"></td>
			<td class="nb_result"></td>
			<td class="time">' . $total_time . '</td>
		</tr>';

		$debug_sql .= '</table>';

		return $debug_sql;
	}

	/**
	 * Modèles de description pour catégories et images.
	 *
	 * @param string $type
	 *	Type de l'objet : 'cat' ou 'image'.
	 * @param array $infos
	 *	Informations utiles de l'objet.
	 * @param boolean $no_tpl
	 *	Ne pas utiliser le modèle de description ?
	 * @return string
	 *	Nouvelle description de l'objet.
	 */
	public static function desc($type, &$infos, $no_tpl = FALSE)
	{
		self::$_descTplInfos =& $infos;
		self::$_descTplType =& $type;

		// Pas de modèle de description pour la description de la galerie.
		if ($type == 'cat' && $infos['cat_id'] == 1)
		{
			$no_tpl = TRUE;
		}

		// Description de catégorie.
		if ($type == 'cat' && !$no_tpl && utils::$config['desc_template_categories_active'])
		{
			$template = utils::$config['desc_template_categories_text'];

			$replace = array(
				'{DESCRIPTION}' => self::_descTplCatInfo('DESCRIPTION'),
				'{ID}' => self::_descTplCatInfo('ID'),
				'{TITLE}' => self::_descTplCatInfo('TITLE'),
				'{URL}' => self::_descTplCatInfo('URL')
			);
		}

		// Description d'image.
		else if ($type == 'image' && !$no_tpl && utils::$config['desc_template_images_active'])
		{
			$template = utils::$config['desc_template_images_text'];

			$replace = array(
				'{DESCRIPTION}' => self::_descTplImageInfo('DESCRIPTION'),
				'{FILENAME}' => self::_descTplImageInfo('FILENAME'),
				'{HEIGHT}' => self::_descTplImageInfo('HEIGHT'),
				'{ID}' => self::_descTplImageInfo('ID'),
				'{TITLE}' => self::_descTplImageInfo('TITLE'),
				'{URL}' => self::_descTplImageInfo('URL'),
				'{WIDTH}' => self::_descTplImageInfo('WIDTH')
			);
		}

		// Pas de modèle de description.
		else
		{
			return (string) nl2br(
				utils::tplHTMLFilter(
					utils::getLocale($infos[$type . '_desc'])
				)
			);
		}

		$template = utils::getLocale($template);

		// Conditions.
		do
		{
			$test = $template;
			$template = preg_replace_callback(
				'`{IF\(([^\)]+)\)}[^$]*?{ENDIF\(\1\)}`', 'self::_descTplIf', $template);
		}
		while ($test != $template);

		// Remplacements.
		$template = str_replace(array_keys($replace), array_values($replace), $template);

		return (string) nl2br(utils::tplHTMLFilter($template));
	}

	/**
	 * Affichage des liens de navigation.
	 *
	 * @param string $item
	 * @param integer $nb_pages
	 * @return boolean
	 */
	public static function disNavigation($item, $nb_pages)
	{
		switch ($item)
		{
			// Lien entre les pages.
			case 'next_active' :
				return $_GET['page'] < $nb_pages && $_GET['page'] >= 1;

			case 'next_inactive' :
				return $_GET['page'] >= $nb_pages || $_GET['page'] < 1;

			case 'prev_active' :
				return $_GET['page'] <= $nb_pages && $_GET['page'] > 1;

			case 'prev_inactive' :
				return $_GET['page'] > $nb_pages || $_GET['page'] <= 1;

			// La barre doit-elle être affichée ?
			case 'top' :
				return $nb_pages > 1
					&& (utils::$config['nav_bar'] == 'top'
					|| utils::$config['nav_bar'] == 'top_bottom');

			case 'bottom' :
				return $nb_pages > 1
					&& (utils::$config['nav_bar'] == 'bottom'
					|| utils::$config['nav_bar'] == 'top_bottom');

			default :
				return $nb_pages > 1;
		}
	}

	/**
	 * Formate le message d'un commentaire.
	 *
	 * @param string $str
	 * @param boolean|array $smiles
	 * @param string $smilies_icons_pack
	 * @return void
	 */
	public static function formatComment($str, $smilies = FALSE, $smilies_icons_pack = '')
	{
		static $smilies_codes;
		static $smilies_icons;

		$word_limit = utils::$config['comments_words_maxlength'];
		$link_limit = utils::$config['comments_links_maxlength'];

		// Limiter la longueur des mots ?
		if (utils::$config['comments_words_limit'])
		{
			$str = explode("\n", $str);
			foreach ($str as &$line)
			{
				$line = explode(' ', $line);
				foreach ($line as &$word)
				{
					if (preg_match('`(?:^|[[(])' . utils::regexpURL() . '`i', $word)
					&& utils::$config['comments_convert_urls'])
					{
						$word = utils::linkify($word, $link_limit);
						continue;
					}

					if (mb_strlen($word) > $word_limit)
					{
						$word = wordwrap($word, $word_limit, "\n", TRUE);
					}

					$word = utils::tplProtect($word);
				}
				$line = implode(' ', $line);
			}
			$str = implode("\n", $str);
		}
		else
		{
			$str = (utils::$config['comments_convert_urls'])
				? utils::linkify($str, $link_limit)
				: utils::tplProtect($str);
		}

		// Smilies ?
		if ($smilies)
		{
			if (!$smilies_icons)
			{
				array_walk($smilies, create_function(
					'&$v,$k',
					'$v = "<img alt=\"" . $k . "\" src=\"'
						. utils::tplProtect(CONF_GALLERY_PATH) . '/images/smilies/'
						. utils::tplProtect($smilies_icons_pack) . '/" . $v .  "\" />";'
				));
				$smilies_codes = array_keys($smilies);
				$smilies_icons = array_values($smilies);
			}
			$str = str_replace($smilies_codes, $smilies_icons, $str);
		}

		return nl2br($str);
	}

	/**
	 * L'information $item de l'image doit-elle être affichée ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public static function disImageStats($item = '')
	{
		$active = TRUE;

		switch ($item)
		{
			// Nombre de commentaires.
			case 'comments' :
				$active = utils::$config['comments'];
				break;

			// Nombre de favoris.
			case 'favorites' :
				$active = utils::$config['users'];
				break;

			// Nombre de votes et note moyenne.
			case 'votes' :
				$active = utils::$config['votes'];
				break;

			// Titre.
			case 'title' :
				return utils::$config['widgets_params']['stats_images']['title'] != '';

			// Au moins l'une des stats.
			case '' :
				foreach (utils::$config['widgets_params']['stats_images']['items']
				as $name => &$status)
				{
					if (self::disImageStats($name))
					{
						return TRUE;
					}
				}
				return FALSE;
		}

		return $active
			&& utils::$config['widgets_params']['stats_images']['items'][$item];
	}

	/**
	 * Retourne l'information $item de l'image.
	 *
	 * @param string $item
	 * @param array $i
	 *	Informations utiles de l'image.
	 * @return string
	 */
	public static function getImageStats($item, &$i)
	{
		switch ($item)
		{
			// Ajoutée par.
			case 'added_by' :
				$user = '/';
				if ($i['user_id'] != 2 && $i['user_status'] == 1)
				{
					$user = (utils::$config['users'])
						? '<a href="' . utils::genURL('user/' . (int) $i['user_id']) . '">'
							. utils::tplProtect($i['user_login']) . '</a>'
						: utils::tplProtect($i['user_login']);
				}
				return $user;

			// Date d'ajout.
			case 'added_date' :
				$adddt = utils::localeTime(__('%A %d %B %Y'), $i['image_adddt']);
				$link = utils::genURL('date-added/'
					. date('Y-m-d', strtotime($i['image_adddt'])));
				return '<a href="' . $link . '">' . utils::tplProtect($adddt) . '</a>';

			// Nombre de commentaires.
			case 'comments' :
				return (int) $i['image_comments'];

			// Date de création.
			case 'created_date' :
				if ($i['image_crtdt'] === NULL)
				{
					$crtdt = '/';
				}
				else
				{
					$crtdt = utils::localeTime(__('%A %d %B %Y'), $i['image_crtdt']);
					$link = utils::genURL('date-created/'
						. date('Y-m-d', strtotime($i['image_crtdt'])));
					$crtdt = '<a href="' . $link . '">' . utils::tplProtect($crtdt) . '</a>';
				}
				return $crtdt;

			// Nombre de favoris.
			case 'favorites' :
				return (int) $i['nb_favorites'];

			// Poids du fichier.
			case 'filesize' :
				return $i['image_filesize']
					? utils::filesize($i['image_filesize'])
					: '?';

			// Hauteur de l'image.
			case 'height' :
				return (isset($i['image_height_original']))
					? (int) $i['image_height_original']
					: (int) $i['image_height'];

			// Nombre de visites.
			case 'hits' :
				return (int) $i['image_hits'];

			// Note moyenne.
			case 'rate' :
				return number_format((float) $i['image_rate'], 1, __(','), '');

			case 'rate_visual' :
				$style_path = utils::tplProtect(CONF_GALLERY_PATH
					. '/template/'
					. utils::$config['theme_template']
					. '/style/' . utils::$config['theme_style']
				);
				return self::visualRate($i['image_rate'], $style_path);

			// Titre.
			case 'title' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['widgets_params']['stats_images']['title']
				));

			// Nombre de votes.
			case 'votes' :
				$votes = ($i['image_votes'] > 1)
					? __('%s votes')
					: __('%s vote');
				return sprintf($votes, (int) $i['image_votes']);

			// Largeur de l'image.
			case 'width' :
				return (isset($i['image_width_original']))
					? (int) $i['image_width_original']
					: (int) $i['image_width'];
		}
	}

	/**
	 * Retourne l'élément de navigation $item.
	 *
	 * @param string $item
	 * @param integer $nb_pages
	 * @param string $link
	 * @return string
	 */
	public static function getNavigation($item, $nb_pages, $link)
	{
		switch ($item)
		{
			// Première page.
			case 'first' :
				return '<<';

			// Lien de la première page.
			case 'first_link' :
				return utils::genURL($link);

			// Liste déroulante des pages.
			case 'html_options' :
				for ($i = 1, $options = ''; $i <= $nb_pages; $i++)
				{
					$selected = ($i == $_GET['page'])
						? ' selected="selected" class="selected"'
						: '';
					$page = ($i == 1) ? '' : '/page/' . $i;
					$options .= '<option' . $selected
						. ' value="' . $page . '">' . $i . '</option>';
				}
				return $options;

			// Dernière page.
			case 'last' :
				return '>>';

			// Lien de la dernière page.
			case 'last_link' :
				return utils::genURL($link . '/page/' . $nb_pages);

			// Requête de la section courante, sans la partie /page/n
			case 'link' :
				return utils::genURL($link);

			// Page suivante.
			case 'next' :
				return '>';

			// Lien vers la page suivante.
			case 'next_link' :
				return utils::genURL($link . '/page/' . ($_GET['page'] + 1));

			// Page précédente.
			case 'prev' :
				return '<';

			// Lien vers la page précédente.
			case 'prev_link' :
				return ($_GET['page'] == 2)
					? utils::genURL($link)
					: utils::genURL($link . '/page/' . ($_GET['page'] - 1));
		}
	}

	/**
	 * Crée les liens de la barre de position (fil d'ariane).
	 *
	 * @param string $item
	 *	Élement de la barre de position souhaité.
	 * @param string $purl_parents
	 *	Partie de l'URL correspondant au nom des éléments parents.
	 * @param string $purl_current
	 *	Partie de l'URL correspondant au nom de l'élément courant.
	 * @param string $image
	 *	L'élement courant est-il une image ?
	 * @param boolean $purl_home
	 *	Doit-on indiquer explicitement le paramètre d'URL
	 *	ou bien est-ce que la page d'accueil correspond à l'URL d'entrée ?
	 * @param boolean $purl_name
	 *	Doit-on indiquer le nom de chaque objet en plus de son identifiant ?
	 * @param string $home_word
	 *	Nom de la page d'accueil.
	 * @param array $parents
	 *	Informations utiles des parents de l'objet courant.
	 * @param array $infos
	 *	Informations utiles de l'objet courant.
	 * @param string $parent_page
	 *	Numéro de page de la catégorie parente où se situe l'objet courant.
	 * @param string $separator
	 *	Caractère séparateur des éléments de la hiérarchie.
	 * @param boolean $no_one
	 *	Se trouve-t-on sur une autre page que la page d'accueil ?
	 * @return string
	 *	Fil d'ariane de l'objet courant.
	 */
	public static function getPosition($item, $purl_parents, $purl_current, $image, $purl_home,
	$purl_name, $home_word, $parents, $infos, $parent_page, $separator, $no_one)
	{
		switch ($item)
		{
			// Accueil.
			case 'home' :
				$l = ($purl_name) ? $purl_parents . '/1-' . __('galerie') : $purl_parents . '/1';
				$link = ($purl_home) ? $l : '';
				$link = (!is_array($parents) && $parent_page !== NULL)
					? $l . '/page/' . $parent_page
					: $link;
				$home = '<a href="' . utils::genURL($link) . '">' . $home_word . '</a>';

				return ($no_one)
					? '<span id="homelink">' . $home . '</span><span class="pos_sep">'
						. utils::tplProtect($separator) . '</span>'
					: '<span id="homelink" class="current">' . $home . '</span>';

			// Parents de l'objet courant.
			case 'parents' :
				$p = '';
				if (is_array($parents) && $no_one)
				{
					$n = 0;
					foreach ($parents as $i)
					{
						$type = ($image && $n == count($parents) - 1)
							? $image
							: $purl_parents;
						$link = ($n == count($parents) - 1 && $parent_page !== NULL)
							? '/page/' . $parent_page
							: '';
						$name = ($purl_name) ? '-' . $i['cat_url'] : '';
						$link = utils::genURL($type . '/' . $i['cat_id']
							. $name . $link);
						$pos_sep_last = ($n == count($parents) - 1)
							? ' pos_sep_last'
							: '';
						$p .= '<a href="' . $link . '">'
							. utils::tplProtect(utils::getLocale($i['cat_name']))
							. '</a><span class="pos_sep' . $pos_sep_last . '">'
							. utils::tplProtect($separator) . '</span>';
						$n++;
					}
				}
				return $p;

			// Objet courant.
			case 'current' :
				if (is_array($infos) && $no_one)
				{
					$type = ($image) ? 'image' : 'cat';
					$urlname = ($purl_name) ? '-' . $infos[$type . '_url'] : '';
					$link = utils::genURL(
						$purl_current . '/' . $infos[$type . '_id'] . $urlname
					);
					$link = '<a href="' . $link . '">%s</a>';
					$name = utils::tplProtect(utils::getLocale($infos[$type . '_name']));
					$current = ($image && utils::$config['images_direct_link'])
						? $name
						: sprintf($link, $name);
					return '<span class="current">' . $current . '</span>';
				}
				break;

			// Les trois précédents réunis.
			default :
				return self::getPosition('home', $purl_parents, $purl_current,
					$image, $purl_home, $purl_name, $home_word, $parents, $infos,
					$parent_page, $separator, $no_one)
					. self::getPosition('parents', $purl_parents, $purl_current,
					$image, $purl_home, $purl_name, $home_word, $parents, $infos,
					$parent_page, $separator, $no_one)
					. self::getPosition('current', $purl_parents, $purl_current,
					$image, $purl_home, $purl_name, $home_word, $parents, $infos,
					$parent_page, $separator, $no_one);
		}
	}

	/**
	 * Retourne l'emplacement d'une vignette.
	 *
	 * @param string $type
	 *	Type de la vignete : 'cat', 'img', 'pen' ou 'wid'.
	 * @pararm array $i
	 *	Informations utiles de l'image ou de la catégorie.
	 * @return string
	 */
	public static function getThumbSrc($type, &$i)
	{
		// Emplacement de la vignette.
		switch ($type)
		{
			case 'cat' :
				$tb_type = 'tb_cat';
				$id = $i['cat_id'];
				$date = $i['cat_crtdt'];

				// Vignette externe.
				if ($i['thumb_id'] == 0)
				{
					$ext = explode('.', $i['tb_infos']);
					$file = 'i.' . $ext[6];
					$i['image_path'] = img::filepath('im_external',
						$file, $i['cat_id'], $i['cat_crtdt']);
				}
				else
				{
					$file = $i['image_path'];
				}
				break;

			case 'img' :
			case 'wid' :
				$tb_type = 'tb_' . $type;
				$file = $i['image_path'];
				$id = $i['image_id'];
				$date = $i['image_adddt'];
				
				break;

			case 'pen' :
				$tb_type = 'tb_img';
				$file = utils::hashImages($i['up_file'])
					. preg_replace('`^.+(\.[^\.]+)$`', '$1', $i['up_file']);
				$id = $i['up_id'];
				$date = $i['up_adddt'];
				$i['image_path'] = $file;
				break;
		}
		$thumb = img::filepath($tb_type, $file, $id, $date);

		// Si la vignette existe et que
		// l'option de protection des vignettes est désactivée.
		if (!CONF_THUMBS_PROTECT && file_exists(GALLERY_ROOT . '/' . $thumb))
		{
			$arg = (isset($i['tb_infos'])) ? '?' . md5($i['tb_infos']) : '';
			$thumb = CONF_GALLERY_PATH . '/' . $thumb . $arg;
		}
		else
		{
			$type = (isset($ext)) ? 'e' : $type;

			// Paramètre de sécurité pour empêcher
			// les manipulations malicieuses des vignettes.
			$k = md5($i['image_path'] . '|' . $type[0]
				. '|' . CONF_KEY . '|' . basename($thumb));

			// Paramètre de sécurité pour protéger
			// des accès direct aux vignettes.
			$s = (CONF_THUMBS_PROTECT)
				? md5($k . '|s|' . CONF_KEY . '|'
					. utils::$cookieSession->read('token'))
				: '';

			$thumb = CONF_GALLERY_PATH . '/thumb.php?'
				. $type[0] . '=' . $i['image_path']
				. '&t=' . basename($thumb)
				. '&k=' . $k
				. '&s=' . $s;
		}

		return $thumb;
	}

	/**
	 * Génère les éléments de la liste déroulante pour le choix de la langue.
	 *
	 * @param string $current
	 * @return string
	 */
	public static function langSelect($current)
	{
		$l = '';
		foreach (utils::$config['locale_langs'] as $code => $name)
		{
			$selected = ($code == $current) ? ' selected="selected" class="selected"' : '';
			$l .= '<option' . $selected . ' value="' . utils::tplprotect($code) . '">'
				. utils::tplprotect($name) . '</option>';
		}

		return $l;
	}

	/**
	 * Construit un plan sous forme de liste déroulante
	 * d'une partie ou de toutes les catégories de la galerie.
	 *
	 * @param array $categories
	 *	Informations utiles des catégories.
	 * @param array $options
	 *	Options de fabrication du plan.
	 * @param integer $parent_id
	 *	Identifiant de la catégorie parente.
	 * @param integer $first_parent_id
	 *	Identifiant de la catégorie parente la plus éloignée.
	 * @param integer $n
	 *	Niveau de profondeur.
	 * @param array $m
	 *	Portion de plan.
	 * @param boolean $start
	 *	Indique si l'on démarre la fabrication du plan.
	 * @return string
	 */
	public static function mapSelect(&$categories, $options = array(),
	$parent_id = 1, $first_parent_id = 0, $n = 0, $m = array(), $start = TRUE)
	{
		static $list;

		$value_search = array('{ID}', '{TYPE}');

		// Démarrage.
		if ($start)
		{
			self::$_map = array();
			$list = '';

			// Construction du plan.
			if (is_array($categories))
			{
				foreach ($categories as $id => &$infos)
				{
					if ($id != 1)
					{
						self::$_map[$infos['parent_id']][$id] = ($infos['cat_filemtime'] === NULL)
							? array()
							: NULL;
					}
				}
				self::_constructMap(self::$_map[$parent_id]);
			}
			$m = &self::$_map;

			// Options par défaut.
			$options_default = array(
				'cat_one' => TRUE,
				'class_id' => FALSE,
				'class_infos' => FALSE,
				'class_selected' => FALSE,
				'class_type' => FALSE,
				'ignore' => array(),
				'ignore_albums' => FALSE,
				'nolines_category' => FALSE,
				'selected' => 0,
				'status' => array(),
				'value_tpl' => '{ID}',
				'value_url' => FALSE
			);
			foreach ($options_default as $o => $v)
			{
				if (!isset($options[$o]))
				{
					$options[$o] = $v;
				}
			}
		}

		// Niveau de profondeur.
		$level = str_repeat('&nbsp;', $n * 3);

		// On parcours la catégorie courante à la recherche de sous-catégories.
		if (!is_array($categories))
		{
			return $list ? $list : '<option>&nbsp;</option>';
		}
		foreach ($m as $id => &$v)
		{
			if (!isset($categories[$id]))
			{
				continue;
			}

			// Ignorer les albums ?
			if ($options['ignore_albums'] && $categories[$id]['cat_filemtime'] !== NULL)
			{
				continue;
			}

			// Ignorer la catégorie courante et ses sous-catégories ?
			if (in_array($id, $options['ignore']))
			{
				continue;
			}

			// Ignorer la catégorie selon le statut ?
			if ($options['status'] !== array()
			&& !in_array($categories[$id]['cat_status'], $options['status']))
			{
				continue;
			}

			// Identifiant du parent le plus éloigné après la catégorie 1.
			$fpid = ($first_parent_id > 1) ? $first_parent_id : $id;

			// Type de catégorie.
			$type = ($categories[$id]['cat_filemtime'] === NULL) ? 'category' : 'album';

			// Attribut "class".
			$class = array();
			if ($options['class_id'])
			{
				$class[] = 'ig_mc_id_' . (int) $id;
			}
			if ($options['class_type'])
			{
				$class[] = 'ig_mc_type_' . $type;
			}
			if ($options['class_infos'])
			{
				$class[] = 'ig_mc_n_' . (int) $n;
				$class[] = 'ig_mc_pid_' . (int) $categories[$id]['parent_id'];
				$class[] = 'ig_mc_fpid_' . (int) $fpid;
			}

			// Attribut "selected".
			$selected = '';
			if ($options['selected'] && $options['selected'] == $id)
			{
				$selected = ' selected="selected"';
				if ($options['class_selected'])
				{
					$class[] = 'selected';
				}
			}

			// Attribut "value".
			if ($options['value_url'] && isset($categories[$id]['cat_url']))
			{
				$value = ($id == 1)
					? ''
					: utils::genURL($type . '/' . $id . '-' . $categories[$id]['cat_url'], TRUE);
			}
			else
			{
				$value = str_replace($value_search, array((int) $id, $type),
					$options['value_tpl']);
			}

			// On ajoute la catégorie à la liste.
			$class = ($class === array())
				? ''
				: ' class="' . implode(' ', $class) . '"';
			$lines = ($id == $parent_id ||
				($options['nolines_category'] && $categories[$id]['cat_filemtime'] === NULL))
				? ''
				: '|-- ';
			$name = ($id == 1)
				? __('galerie')
				: utils::tplProtect(utils::getLocale($categories[$id]['cat_name']));
			$list .= '<option' . $class . $selected . ' value="' . $value . '">'
				. $level . $lines . $name . '</option>';

			// On boucle si c'est une catégorie.
			if (is_array($v))
			{
				self::mapSelect($categories, $options, $id, $fpid, $n + 1, $v, FALSE);
			}
		}

		return $list ? $list : '<option>&nbsp;</option>';
	}

	/**
	 * Permet de faire une boucle sur le tableau $array.
	 *
	 * @param array $array
	 * @param array $n
	 * @return boolean
	 */
	public static function nextObject(&$array, &$n)
	{
		if (is_array($array) && !empty($array))
		{
			if ($n < 0)
			{
				reset($array);
			}
			$n++;
			if ($n > 0)
			{
				if ($n !== count($array))
				{
					return (bool) each($array);
				}
				$n = -1;
				reset($array);
				return FALSE;
			}
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Génère la note visuelle en HTML.
	 *
	 * @param float $rate
	 * @param string $style_path
	 * @param string $small
	 * @return string
	 */
	public static function visualRate($rate, $style_path, $small = '')
	{
		foreach (array('empty', 'half', 'full') as $r)
		{
			$$r = '<img alt="' . $r . '" src="' . $style_path
				. '/star-' . $r . $small . '.png" />';
		}

		switch (round($rate * 2) / 2)
		{
			case 0 :
				return str_repeat($empty, 5);

			case 0.5 :
				return $half . str_repeat($empty, 4);

			case 1 :
				return $full . str_repeat($empty, 4);

			case 1.5 :
				return $full . $half . str_repeat($empty, 3);

			case 2 :
				return str_repeat($full, 2) . str_repeat($empty, 3);

			case 2.5 :
				return str_repeat($full, 2) . $half . str_repeat($empty, 2);

			case 3 :
				return str_repeat($full, 3) . str_repeat($empty, 2);

			case 3.5 :
				return str_repeat($full, 3) . $half . $empty;

			case 4 :
				return str_repeat($full, 4) . $empty;

			case 4.5 :
				return str_repeat($full, 4) . $half;

			case 5 :
				return str_repeat($full, 5);
		}
	}

	/**
	 * L'option de filigrane $item doit-elle être affichée ?
	 *
	 * @param string $item
	 * @param array $w_params
	 * @return boolean
	 */
	public static function disWatermarkOption($item, $w_params)
	{
		switch ($item)
		{
			// Image de filigrane.
			case 'image_file' :
				return !empty($w_params['image_file']);

			// Fonction imagettfbbox pour le texte.
			case 'imagettfbbox' :
				return function_exists('imagettfbbox');

			// Options.
			case 'image_active' :
			case 'text_active' :
				return $w_params[$item];

			// Type de filigrane.
			case 'watermark_default' :
			case 'watermark_none' :
			case 'watermark_specific' :
				$item = explode('_', $item);
				return $w_params['watermark'] == $item[1];
		}
	}

	/**
	 * Retourne le paramètre de filigrane $item.
	 *
	 * @param string $item
	 * @param array $w_params
	 * @return string
	 */
	public static function getWatermarkOption($item, $w_params)
	{
		switch ($item)
		{
			// Boutons radio.
			case 'text_size_type_fixed' :
			case 'text_size_type_pct' :
			case 'image_size_type_fixed' :
			case 'image_size_type_pct' :
				$param = str_replace(array('_fixed', '_pct'), '', $item);
				$value = str_replace(array('text_size_type_', 'image_size_type_'), '', $item);
				return $w_params[$param] == $value
					? ' checked="checked"'
					: '';

			// Cases à cocher.
			case 'background_active' :
			case 'background_large' :
			case 'border_active' :
			case 'image_active' :
			case 'text_active' :
			case 'text_shadow_active' :
				return $w_params[$item]
					? ' checked="checked"'
					: '';

			// Champs textes.
			case 'background_alpha' :
			case 'background_color' :
			case 'background_padding' :
			case 'border_alpha' :
			case 'border_color' :
			case 'border_size' :
			case 'image_opacity' :
			case 'image_size_pct' :
			case 'image_x' :
			case 'image_y' :
			case 'quality' :
			case 'text' :
			case 'text_alpha' :
			case 'text_color' :
			case 'text_shadow_alpha' :
			case 'text_shadow_color' :
			case 'text_shadow_size' :
			case 'text_size_fixed' :
			case 'text_size_pct' :
			case 'text_x' :
			case 'text_y' :
				return utils::tplProtect($w_params[$item]);

			// Image de filigrane.
			case 'image_file' :
				$session = utils::$cookieSession->read('token');
				$f = $w_params['image_file'];
				$s = md5($f . '|f|' . CONF_KEY . '|s|' . $session);
				return utils::tplProtect(
					CONF_GALLERY_PATH . '/readfile.php?f=' . $f . '&s=' . $s
				);

			// Dimensions HTML de l'image.
			case 'image_size' :
				$i = img::getImageSize(GALLERY_ROOT . '/'
					. $w_params['image_file']);
				return 'width="' . $i['width'] . '" height="' . $i['height'] . '"';

			// A l'extérieur de l'image ?
			case 'text_external' :
				$int = '<option%s value="0">' . __('À l\'intérieur de l\'image') . '</option>';
				$ext = '<option%s value="1">' . __('À l\'extérieur de l\'image') . '</option>';
				$int = ($w_params[$item])
					? sprintf($int, '')
					: sprintf($int, ' selected="selected"');
				$ext = ($w_params[$item])
					? sprintf($ext, ' selected="selected"')
					: sprintf($ext, '');
				return $int . $ext;

			// Fonte.
			case 'text_font' :
				$options = '';
				foreach (scandir(GALLERY_ROOT . '/fonts/') as $filename)
				{
					if (preg_match('`^[-a-z0-9_]{1,64}\.ttf$`i', $filename))
					{
						$selected = ($w_params[$item] == $filename)
							? ' selected="selected"'
							: '';
						$f = utils::tplProtect($filename);
						$options .= '<option' . $selected
							. ' value="' . $f . '">' . $f . '</option>';
					}
				}
				return $options;

			// Position.
			case 'image_position' :
			case 'text_position' :
				$options = '';
				$values = array(
					'top left' => __('En haut à gauche'),
					'top center' => __('En haut'),
					'top right' => __('En haut à droite'),
					'center left' => __('À gauche'),
					'center center' => __('Au centre'),
					'center right' => __('À droite'),
					'bottom left' => __('En bas à gauche'),
					'bottom center' => __('En bas'),
					'bottom right' => __('En bas à droite')
				);
				foreach ($values as $value => &$text)
				{
					$selected = ($value == $w_params[$item])
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected
						. ' value="' . $value . '">' . $text . '</option>';
				}
				return $options;
		}
	}



	/**
	 * Construction du plan.
	 *
	 * @param array $m
	 * @return void
	 */
	private static function _constructMap(&$m)
	{
		if (!is_array($m))
		{
			return;
		}
		foreach ($m as $id => &$v)
		{
			if (is_array($v))
			{
				if (isset(self::$_map[$id]))
				{
					$v = self::$_map[$id];
					unset(self::$_map[$id]);
				}
				self::_constructMap($v);
			}
		}
	}

	/**
	 * Retourne une information de catégorie pour le modèle de description.
	 *
	 * @param array $infos
	 *	Informations utiles de la catégorie.
	 * @return string
	 *	Information de catégorie.
	 */
	private static function _descTplCatInfo($info)
	{
		switch ($info)
		{
			// Description.
			case 'DESCRIPTION' :
				return utils::getLocale(self::$_descTplInfos['cat_desc']);

			// Identifiant.
			case 'ID' :
				return self::$_descTplInfos['cat_id'];

			// Lieu.
			case 'PLACE' :
				return utils::getLocale(self::$_descTplInfos['cat_place']);

			// Titre.
			case 'TITLE' :
				return utils::getLocale(self::$_descTplInfos['cat_name']);

			// URL de la catégorie.
			case 'URL' :
				return GALLERY_HOST . utils::genURL(
					(self::$_descTplInfos['cat_filemtime'] === NULL
						? 'category' : 'album')
					. '/' . self::$_descTplInfos['cat_id']
					. '-' . self::$_descTplInfos['cat_url']
				);
		}
	}

	/**
	 * Retourne une information d'image pour le modèle de description.
	 *
	 * @param array $infos
	 *	Informations utiles de l'image.
	 * @return string
	 *	Information d'image.
	 */
	private static function _descTplImageInfo($info)
	{
		switch ($info)
		{
			// Description.
			case 'DESCRIPTION' :
				return utils::getLocale(self::$_descTplInfos['image_desc']);

			// Nom de fichier.
			case 'FILENAME' :
				return basename(self::$_descTplInfos['image_path']);

			// Hauteur de l'image.
			case 'HEIGHT' :
				return self::$_descTplInfos['image_height'];

			// Identifiant.
			case 'ID' :
				return self::$_descTplInfos['image_id'];

			// Lieu.
			case 'PLACE' :
				return utils::getLocale(self::$_descTplInfos['image_place']);

			// Titre.
			case 'TITLE' :
				return utils::getLocale(self::$_descTplInfos['image_name']);

			// URL de l'image.
			case 'URL' :
				return GALLERY_HOST . utils::genURL(
					'image/' . self::$_descTplInfos['image_id']
					. '-' . self::$_descTplInfos['image_url']
				);

			// Largeur de l'image.
			case 'WIDTH' :
				return self::$_descTplInfos['image_width'];
		}
	}

	/**
	 * Méthode de callback pour les conditions du modèle de description.
	 *
	 * @param string $str
	 * @return null|string
	 */
	private static function _descTplIf($str)
	{
		switch ($str[1])
		{
			// Description.
			case 'DESCRIPTION' :
				$replace = !utils::isEmpty(
					self::$_descTplInfos[self::$_descTplType . '_desc']
				);
				break;

			// Lieu.
			case 'PLACE' :
				$replace = utils::getLocale(
					self::$_descTplInfos[self::$_descTplType . '_place']
				) !== NULL;
				break;
		}

		if ($replace)
		{
			$replace = $str[0];
			$replace = substr($replace, 6 + strlen($str[1]), strlen($str[0]));
			$replace = substr($replace, 0, -9 -strlen($str[1]));

			return $replace;
		}
	}
}
?>