<?php
/**
 * Création d'images avec filigrane.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class watermark
{
	/**
	 * Image GD créée pour ajouter le filigrane.
	 *
	 * @var resource
	 */
	public $gdImage;

	/**
	 * Type de fichier de l'image.
	 *
	 * @var integer
	 */
	public $filetype;

	/**
	 * Chemin de la fonte TTF utilisée pour ajouter un texte sur l'image.
	 *
	 * @var string
	 */
	public $fontFile;

	/**
	 * Texte à ajouter sur l'image.
	 *
	 * @var string
	 */
	public $text;

	/**
	 * Angle du texte.
	 *
	 * @var integer
	 */
	public $textAngle;

	/**
	 * Épaisseur de la bordure.
	 *
	 * @var integer
	 */
	public $textBorder;

	/**
	 * Dimensions de la zone occupée par le texte.
	 *
	 * @var array
	 */
	public $textBox;

	/**
	 * Le texte doit-il être placé à l'extérieur de l'image ?
	 *
	 * @var boolean
	 */
	public $textExternal;

	/**
	 * Marge interne par rapport au fond.
	 *
	 * @var integer
	 */
	public $textPadding;

	/**
	 * Position du texte dans l'image.
	 * Valeurs possibles :
	 *	"top left",		"top center",	"top right"
	 *	"center left",	"center center",	"center right"
	 *	"bottom left",	"bottom center",	"bottom right"
	 *
	 * @var string
	 */
	public $textPosition;

	/**
	 * Taille du texte.
	 *
	 * @var integer
	 */
	public $textSize;

	/**
	 * Position du texte depuis le bord horizontal.
	 *
	 * @var integer
	 */
	public $textX;

	/**
	 * Position du texte depuis le bord vertical.
	 *
	 * @var integer
	 */
	public $textY;



	/**
	 * Informations utiles de l'image.
	 *
	 * @var string
	 */
	private static $_imageInfos;



	/**
	 * Initialisation.
	 * Ajoute le filigrane avec les paramètres $watermark_params, si fournis.
	 *
	 * @param string|resource $image
	 *	(string) Chemin absolu du de l'image sur laquelle on doit ajouter un filigrane.
	 *	(resource) Ressource de l'image.
	 * @param array $watermark_params
	 *	Paramètres du filigrane.
	 * @return void
	 */
	public function __construct($image, $watermark_params = NULL)
	{
		if (is_string($image))
		{
			$image_infos = img::getImageSize($image);
			$this->filetype = $image_infos['filetype'];

			$this->gdImage = img::gdCreateImage($image, $this->filetype);
		}
		else if (is_resource($image))
		{
			$this->gdImage = $image;
		}
		else
		{
			return;
		}

		if ($watermark_params === NULL)
		{
			return;
		}

		img::$gdTransparency = TRUE;

		// Image.
		$image_file = GALLERY_ROOT . '/images/watermarks/' . $watermark_params['image_file'];
		if ($watermark_params['image_active']
		&& file_exists($image_file) && is_file($image_file))
		{
			$pct = ($watermark_params['image_size_type'] == 'pct')
				? $watermark_params['image_size_pct']
				: 0;
			$this->addImage(
				$image_file,
				$watermark_params['image_position'],
				$watermark_params['image_x'],
				$watermark_params['image_y'],
				$watermark_params['image_opacity'],
				$pct
			);
		}

		// Texte.
		if ($watermark_params['text_active'])
		{
			$border_size = ($watermark_params['border_active'])
				? $watermark_params['border_size']
				: 0;
			$pct = ($watermark_params['text_size_type'] == 'pct')
				? $watermark_params['text_size_pct']
				: 0;
			$this->setText(
				$watermark_params['text'],
				$watermark_params['text_size_fixed'],
				$pct,
				0,
				$watermark_params['background_padding'],
				$border_size,
				$watermark_params['text_position'],
				$watermark_params['text_x'],
				$watermark_params['text_y'],
				$watermark_params['text_external'],
				GALLERY_ROOT . '/fonts/' . $watermark_params['text_font']
			);

			// Fond.
			if ($watermark_params['background_active'])
			{
				$this->addBackground(
					$watermark_params['background_color'],
					$watermark_params['background_alpha'],
					$watermark_params['background_large']
				);
			}

			// Bordure.
			if ($watermark_params['border_active'])
			{
				$this->addBorders(
					$watermark_params['border_color'],
					$watermark_params['border_alpha'],
					$watermark_params['background_large']
				);
			}

			// Ombre du texte.
			if ($watermark_params['text_shadow_active'])
			{
				$this->addTextShadow(
					$watermark_params['text_shadow_color'],
					$watermark_params['text_shadow_alpha'],
					$watermark_params['text_shadow_size']
				);
			}

			// On ajoute le texte.
			$this->addText(
				$watermark_params['text_color'],
				$watermark_params['text_alpha']
			);
		}
	}

	/**
	 * Ajoute un fond derrière le texte.
	 *
	 * @param string $color_html
	 *	Couleur HTML du rectangle.
	 * @param integer $alpha
	 *	Niveau de transparence (entre 0 et 127).
	 * @param boolean $large
	 *	Le fond doit-il s'étendre sur toute la largeur de l'image ?
	 * @return void
	 */
	public function addBackground($color_html, $alpha = 0, $large = FALSE)
	{
		$this->_addRectangle($color_html, $alpha, $large, TRUE);
	}

	/**
	 * Ajoute un cadre autour du texte.
	 *
	 * @param string $color_html
	 *	Couleur HTML du rectangle.
	 * @param integer $alpha
	 *	Niveau de transparence (entre 0 et 127).
	 * @param boolean $large
	 *	Le cadre doit-il s'étendre sur toute la largeur de l'image ?
	 * @return void
	 */
	public function addBorders($color_html, $alpha = 0, $large = FALSE)
	{
		for ($i = 1; $i <= $this->textBorder; $i++)
		{
			$this->_addRectangle($color_html, $alpha, $large, FALSE, $i);
		}
	}

	/**
	 * Ajoute un filigrane sur l'image.
	 *
	 * @param string $watermark_file
	 *	Chemin du fichier de filigrane.
	 * @param string $position
	 *	Position du texte dans l'image ("bottom right", "center center", etc.).
	 * @param integer $x
	 *	Position par rapport au bord horizontal.
	 * @param integer $y
	 *	Position par rapport au bord vertical.
	 * @param integer $opacity
	 *	Opacité du filigrane (entre 0 et 100).
	 * @param integer $pct
	 *	Taille proportionnelle du filigrane par rapport à l'image.
	 * @return void
	 */
	public function addImage($watermark_file, $position, $x = 0, $y = 0, $opacity = 100, $pct = 0)
	{
		// Image du filigrane.
		$watermark_infos = img::getImageSize($watermark_file);
		$watermark_image = img::gdCreateImage($watermark_file, $watermark_infos['filetype']);

		// Dimensions du filigrane.
		$watermark_width = imagesx($watermark_image);
		$watermark_height = imagesy($watermark_image);

		// Redimensionnement du filigrane.
		if ($pct)
		{
			// Nouvelles dimensions du filigrane.
			$watermark_width = round(imagesx($this->gdImage) * ($pct / 100));
			$watermark_height = round($watermark_height *
				($watermark_width / imagesx($watermark_image)));
		}

		// On redimensionne le filigrane.
		$watermark_image_resized = imagecreate($watermark_width, $watermark_height);
		$watermark_image = img::gdResize($watermark_image, 0, 0,
			imagesx($watermark_image), imagesy($watermark_image),
			0, 0, $watermark_width, $watermark_height);

		// On repositionne le filigrane.
		$debug_backtrace = debug_backtrace();
		if (basename($debug_backtrace[1]['file']) !== 'image.php')
		{
			$x = $x
				? round(imagesx($this->gdImage) / (self::$_imageInfos['image_width'] / $x))
				: $x;
			$y = $y
				? round(imagesy($this->gdImage) / (self::$_imageInfos['image_height'] / $y))
				: $y;
		}

		// Position du filigrane.
		$position = explode(' ', $position);
		switch ($position[0])
		{
			case 'bottom' :
				$dst_y = imagesy($this->gdImage) - imagesy($watermark_image) - $y;
				break;

			case 'center' :
				$dst_y = (imagesy($this->gdImage) - imagesy($watermark_image)) / 2;
				break;

			case 'top' :
				$dst_y = $y;
				break;
		}
		switch ($position[1])
		{
			case 'center' :
				$dst_x = (imagesx($this->gdImage) - imagesx($watermark_image)) / 2;
				break;

			case 'left' :
				$dst_x = $x;
				break;

			case 'right' :
				$dst_x = imagesx($this->gdImage) - imagesx($watermark_image) - $x;
				break;
		}

		// On ajoute le filigrane sur l'image.
		imagecopymerge($this->gdImage, $watermark_image, $dst_x, $dst_y, 0, 0,
			$watermark_width, $watermark_height, $opacity);
		imagedestroy($watermark_image);
	}

	/**
	 * Ajoute un texte sur l'image.
	 *
	 * @param string $color_html
	 *	Couleur HTML du rectangle.
	 * @param integer $alpha
	 *	Niveau de transparence (entre 0 et 127).
	 * @return void
	 */
	public function addText($color_html, $alpha = 0)
	{
		$this->_addText($color_html, $alpha);
	}

	/**
	 * Ajoute une ombre pour le texte.
	 *
	 * @param string $color_html
	 *	Couleur HTML du rectangle.
	 * @param integer $alpha
	 *	Niveau de transparence (entre 0 et 127).
	 * @param integer $size
	 *	Épaisseur de l'ombre.
	 * @return void
	 */
	public function addTextShadow($color_html, $alpha = 0, $size = 1)
	{
		for ($i = 1; $i <= $size; $i++)
		{
			$this->_addText($color_html, $alpha, $i);
		}
	}

	/**
	 * Modifie les options de filigrane.
	 *
	 * @param array $watermark
	 *	Paramètres de filigrane.
	 *	Le tableau sera modifié avec les paramètres
	 *	envoyés dans $_POST[$post_arr].
	 * @param string $watermarks_dir
	 *	Répertoire où se trouve les images de filigrane.
	 * @param string $post_arr
	 *	Tableau POST où se trouvent les options.
	 * @return null|string
	 */
	public static function changeOptions(&$watermark, $watermark_dir = NULL,
	$post_arr = 'watermark_options')
	{
		// Paramètres par défaut.
		if (!is_array(utils::$config['watermark_params_default']))
		{
			utils::$config['watermark_params_default']
				= unserialize(utils::$config['watermark_params_default']);
		}
		ksort(utils::$config['watermark_params_default']);

		// On ajoute les paramètres non présents
		// dans les paramètres par défaut.
		foreach (utils::$config['watermark_params_default'] as $param => &$value)
		{
			if (!array_key_exists($param, $watermark))
			{
				$watermark[$param] = $value;
			}
		}

		// On supprime les paramétres inexistants
		// dans les paramètres par défaut.
		foreach ($watermark as $param => &$value)
		{
			if (!array_key_exists($param, utils::$config['watermark_params_default']))
			{
				unset($watermark[$param]);
			}
		}

		ksort($watermark);

		if (!isset($_POST[$post_arr]) || !is_array(utils::$config['watermark_params_default']))
		{
			return;
		}

		// Cases à cocher.
		$checkboxes = array(
			'background_active',
			'background_large',
			'border_active',
			'image_active',
			'text_active',
			'text_external',
			'text_shadow_active'
		);
		foreach ($checkboxes as &$param)
		{
			if (!empty($_POST[$post_arr][$param]) && !$watermark[$param])
			{
				$watermark[$param] = 1;
			}
			else if (empty($_POST[$post_arr][$param]) && $watermark[$param])
			{
				$watermark[$param] = 0;
			}
		}

		// Boutons radio.
		$radios = array(
			'image_size_type' => array('fixed', 'pct'),
			'text_size_type' => array('fixed', 'pct'),
			'watermark' => array('default', 'none', 'specific')
		);
		foreach ($radios as $param => &$values)
		{
			// Vérification du format.
			if (!isset($_POST[$post_arr][$param])
			|| !in_array($_POST[$post_arr][$param], $values))
			{
				continue;
			}

			if ($_POST[$post_arr][$param] != $watermark[$param])
			{
				$watermark[$param] = $_POST[$post_arr][$param];
			}
		}

		// Couleurs.
		$colors = array(
			'background_color',
			'border_color',
			'text_color',
			'text_shadow_color'
		);
		foreach ($colors as &$param)
		{
			// Vérification du format.
			if (!isset($_POST[$post_arr][$param])
			|| !preg_match('`^#[\da-f]{6}$`', $_POST[$post_arr][$param]))
			{
				continue;
			}

			if ($_POST[$post_arr][$param] != $watermark[$param])
			{
				$watermark[$param] = $_POST[$post_arr][$param];
			}
		}

		// Champs textes : entiers.
		$integers = array(
			'background_alpha' => 127,
			'background_padding' => 9999,
			'border_alpha' => 127,
			'border_size' => 999,
			'image_opacity' => 100,
			'image_size_pct' => 999,
			'image_x' => 99999,
			'image_y' => 99999,
			'quality' => 100,
			'text_alpha' => 127,
			'text_shadow_alpha' => 127,
			'text_shadow_size' => 999,
			'text_size_fixed' => 999,
			'text_size_pct' => 999,
			'text_x' => 99999,
			'text_y' => 99999
		);
		foreach ($integers as $param => &$max)
		{
			// Vérification du format.
			if (!isset($_POST[$post_arr][$param])
			|| !preg_match('`^\d{1,5}$`', $_POST[$post_arr][$param])
			|| (int) $_POST[$post_arr][$param] < 0
			|| (int) $_POST[$post_arr][$param] > $max)
			{
				continue;
			}

			if ($_POST[$post_arr][$param] != $watermark[$param])
			{
				$watermark[$param] = (int) $_POST[$post_arr][$param];
			}
		}

		// Position.
		$position = array(
			'image_position',
			'text_position'
		);
		$position_values = array(
			'top left',    'top center',    'top right',
			'center left', 'center center', 'center right',
			'bottom left', 'bottom center', 'bottom right'
		);
		foreach ($position as &$param)
		{
			// Vérification du format.
			if (!isset($_POST[$post_arr][$param])
			|| !in_array($_POST[$post_arr][$param], $position_values))
			{
				continue;
			}

			if ($_POST[$post_arr][$param] != $watermark[$param])
			{
				$watermark[$param] = $_POST[$post_arr][$param];
			}
		}

		// Fonte.
		if (isset($_POST[$post_arr]['text_font']))
		{
			foreach (scandir(GALLERY_ROOT . '/fonts/') as $filename)
			{
				if (!preg_match('`^[-a-z0-9_]{1,64}\.ttf$`i', $filename))
				{
					continue;
				}

				if ($_POST[$post_arr]['text_font'] == $filename
				 && $_POST[$post_arr]['text_font'] != $watermark['text_font'])
				{
					$watermark['text_font'] = $_POST[$post_arr]['text_font'];
					break;
				}
			}
		}

		// Texte.
		if (isset($_POST[$post_arr]['text'])
		&& $_POST[$post_arr]['text'] != $watermark['text']
		&& mb_strlen($_POST[$post_arr]['text']) <= 64)
		{
			$watermark['text'] = $_POST[$post_arr]['text'];
		}

		// Image de filigrane.
		if (isset($_FILES['file_upload']))
		{
			$error_message = 'error:' . __('Impossible de changer l\'image de filigrane.');
			$i = $_FILES['file_upload'];

			// Y a-t-il une erreur ?
			switch ($i['error'])
			{
				// Aucune erreur.
				case 0 :
					break;

				// Fichier trop lourd.
				case 1 :
				case 2 :
					return 'warning:' . __('Le fichier est trop lourd.');

				// Aucun fichier.
				case 4 :
					return;

				// Autre erreur.
				default :
					return sprintf(
						$error_message . ' ' . __('Code erreur : %s'),
						$i['error']
					);
			}

			if (!is_uploaded_file($i['tmp_name']))
			{
				return $error_message . ' [' . __LINE__ . ']';
			}

			// Type de fichier.
			if (($image_infos = img::getImageSize($i['tmp_name'])) === FALSE
			|| !in_array($image_infos['filetype'], array(1, 2, 3)))
			{
				return 'warning:' . __('Le fichier n\'est pas une image valide.');
			}

			// Fichier destination.
			if (!is_dir(GALLERY_ROOT . '/' . $watermark_dir))
			{
				files::mkdir(GALLERY_ROOT . '/' . $watermark_dir);
			}
			$dest_filename = GALLERY_ROOT . '/' . $watermark_dir . 'watermark';

			// Si le fichier est identique à celui existant déjà,
			// inutile d'aller plus loin.
			$md5_file = md5_file($i['tmp_name']);
			if (file_exists($dest_filename)
			&& $md5_file == md5_file($dest_filename))
			{
				return;
			}

			// On déplace l'image vers le répertoire de filigrane.
			if (!move_uploaded_file($i['tmp_name'], $dest_filename))
			{
				return $error_message . ' [' . __LINE__ . ']';
			}

			$watermark['image_file'] = basename($dest_filename);
			$watermark['image_file_md5'] = $md5_file;

			return 'success:' . __('Modifications enregistrées.');
		}
	}

	/**
	 * Retourne les paramètres de filigrane en fonction
	 * des différentes options de filigrane activées.
	 *
	 * @param array $image_infos
	 *	Informations utiles de l'image obtenues
	 *	avec includes/object_infos.php.
	 * @return array|boolean
	 *	Retourne FALSE si aucun filigrane ne doit être utilisé,
	 *	sinon retourne le tableau des paramètres de filigrane.
	 */
	public static function getParams($image_infos)
	{
		self::$_imageInfos = $image_infos;

		// Paramètres du filigrane de l'utilisateur.
		$watermark_user = FALSE;
		if (utils::isSerializedArray($image_infos['user_watermark']))
		{
			$image_infos['user_watermark'] = unserialize($image_infos['user_watermark']);
			if ($image_infos['user_watermark']['watermark'] != 'default')
			{
				$watermark_user = utils::$config['watermark_users'];
			}
		}

		// Paramètres du filigrane de la catégorie.
		$watermark_category = FALSE;
		if (utils::isSerializedArray($image_infos['cat_watermark']))
		{
			$image_infos['cat_watermark'] = unserialize($image_infos['cat_watermark']);
			if ($image_infos['cat_watermark']['watermark'] != 'default')
			{
				$watermark_category = utils::$config['watermark_categories'];
			}
		}

		// Paramètres du filigrane global.
		if (utils::isSerializedArray(utils::$config['watermark_params']))
		{
			utils::$config['watermark_params'] = unserialize(utils::$config['watermark_params']);
		}

		// Priorité 1 : filigrane de l'utilisateur.
		if ($watermark_user)
		{
			$watermark_params =& $image_infos['user_watermark'];

			// Chemin de l'image de filigrane.
			if (!utils::isEmpty($watermark_params['image_file']))
			{
				$watermark_params['image_file'] = 'users/' . (int) $image_infos['user_id']
					. '/' . $watermark_params['image_file'];
			}

			// Sert à avoir un md5 unique au type de filigrane.
			$watermark_params['type'] = 'user';
		}

		// Priorité 2 : filigrane de la catégorie.
		else if ($watermark_category)
		{
			$watermark_params =& $image_infos['cat_watermark'];

			// Chemin de l'image de filigrane.
			if (!utils::isEmpty($watermark_params['image_file']))
			{
				$watermark_params['image_file'] = 'categories/' . (int) $image_infos['cat_id']
					. '/' . $watermark_params['image_file'];
			}

			// Sert à avoir un md5 unique au type de filigrane.
			$watermark_params['type'] = 'cat';
		}

		// Priorité 3 : filigrane global.
		else if (utils::$config['watermark'])
		{
			$watermark_params =& utils::$config['watermark_params'];

			// Sert à avoir un md5 unique au type de filigrane.
			$watermark_params['type'] = 'global';
		}

		// Aucun filigrane activé.
		else
		{
			return FALSE;
		}

		// Si aucun filigrane n'est à utiliser, on arrête là.
		if ($watermark_params['watermark'] == 'none')
		{
			return FALSE;
		}

		// Les fonctions GD imagettfbbox et imagecolorallocatealpha
		// doivent être activées pour l'ajout d'un texte.
		if ($watermark_params['text_active']
		&& (!function_exists('imagettfbbox') || !function_exists('imagecolorallocatealpha')))
		{
			$watermark_params['text_active'] = FALSE;
		}

		// On vérifie qu'il y a du contenu disponible.
		if ((($watermark_params['text_active']
			&& !utils::isEmpty($watermark_params['text']))
		 || ($watermark_params['image_active']
			&& !utils::isEmpty($watermark_params['image_file'])))
		=== FALSE)
		{
			return FALSE;
		}

		return $watermark_params;
	}

	/**
	 * Initialise le texte à ajouter sur l'image.
	 *
	 * @param string $text
	 *	Texte à ajouter sur l'image.
	 * @param integer $size
	 *	Taille du texte.
	 * @param integer $size
	 *	Indique le pourcentage de l'image que doit occuper le texte,
	 *	indépendamment de la taille de ce dernier.
	 *	0 pour désactiver.
	 * @param integer $angle
	 *	Angle du texte.
	 * @param integer $padding
	 *	Marge interne par rapport au fond.
	 * @param integer $border
	 *	Épaisser de la bordure.
	 * @param string $position
	 *	Position du texte dans l'image ("bottom right", "center center", etc.).
	 * @param integer $x
	 *	Position par rapport au bord horizontal.
	 * @param integer $y
	 *	Position par rapport au bord vertical.
	 * @param boolean $external
	 *	Doit-on dessiner le texte en dehors de l'image ?
	 * @param string $font_file
	 *	Chemin absolu de la fonte TTF.
	 * @return void
	 */
	public function setText($text, $size, $pct, $angle, $padding, $border,
	$position, $x, $y, $external, $font_file)
	{
		if (!function_exists('imagettfbbox'))
		{
			return;
		}

		// Paramètres du texte.
		$this->fontFile = $font_file;
		$this->text = $text;
		$this->textAngle = $angle;
		$this->textBorder = $border;
		$this->textExternal = $external;
		$this->textPadding = $padding;
		$this->textPosition = $position;
		$this->textSize = $size;
		$this->textX = $x;
		$this->textY = $y;

		// Dimensions de la zone occupée par le texte.
		$width_ok = FALSE;
		$pct_status = 0;
		while (!$width_ok)
		{
			$this->textBox = imagettfbbox(
				$this->textSize, $this->textAngle, $this->fontFile, $this->text
			);
			if ($this->textBox === FALSE)
			{
				break;
			}

			$this->textBox['height'] = $this->textBox[1] - $this->textBox[5];
			$this->textBox['width'] = $this->textBox[2];

			if ($this->textBox['width'] == 0)
			{
				break;
			}

			$pct_width = round(100 / (imagesx($this->gdImage) / $this->textBox['width']));

			if ($pct)
			{
				if ($pct_width > $pct && $pct_status != 1)
				{
					$this->textSize--;
					$pct_status = -1;
					continue;
				}
				else if ($pct_width < $pct && $pct_status != -1)
				{
					$this->textSize++;
					$pct_status = 1;
					continue;
				}
			}

			$width_ok = TRUE;
		}

		// On agrandie l'image si le texte
		// doit être placé à l'extérieur de celle-ci.
		if ($this->textExternal && substr($position, 0, 6) != 'center')
		{
			$new_width = imagesx($this->gdImage);
			$new_height = imagesy($this->gdImage) + $this->textBox['height']
				+ ($this->textPadding * 2) + ($this->textBorder * 2)
				+ 1;

			$new_image = imagecreatetruecolor($new_width, $new_height);

			$dst_height = (substr($position, 0, 3) == 'top')
				? $this->textBox['height'] + ($this->textPadding * 2) + ($this->textBorder * 2)
					+ 1
				: 0;
			imagecopymerge($new_image, $this->gdImage, 0, $dst_height,
				0, 0, imagesx($this->gdImage), imagesy($this->gdImage), 100);
			$this->gdImage = $new_image;
		}

		$position = explode(' ', $position);

		if ($position[0] == 'bottom')
		{
			$this->textY = $this->textY + 1;
		}

		if ($position[1] == 'right')
		{
			$this->textX = $this->textX + 1;
		}
	}



	/**
	 * Dessine un rectangle sur l'image.
	 *
	 * @param string $color_html
	 *	Couleur HTML du rectangle.
	 * @param integer $alpha
	 *	Niveau de transparence (entre 0 et 127).
	 * @param boolean $large
	 *	Le rectangle doit-il occuper toute la largeur de l'image ?
	 * @param boolean $filled
	 *	Doit-on dessiner un rectangle plein ?
	 * @param integer $level
	 *	Éloignement du rectangle par rapport au rectangle défini.
	 * @return void
	 */
	private function _addRectangle($color_html, $alpha, $large, $filled, $level = 1)
	{
		// Couleur du fond.
		$rgb = img::html2rgb($color_html);
		$color = imagecolorallocatealpha($this->gdImage, $rgb[0], $rgb[1], $rgb[2], $alpha);

		// Coordonnées du rectangle.
		$position = explode(' ', $this->textPosition);
		switch ($position[0])
		{
			case 'bottom' :
				$y1 = imagesy($this->gdImage) - $this->textY - $this->textBox['height']
				    - ($this->textPadding * 2) - $this->textBorder;
				$y2 = imagesy($this->gdImage) - $this->textY - $this->textBorder;
				break;

			case 'center' :
				$y1 = ((imagesy($this->gdImage) - $this->textBox['height']
					- ($this->textPadding * 2)) / 2);
				$y2 = ((imagesy($this->gdImage) + $this->textBox['height']
					+ ($this->textPadding * 2)) / 2);
				break;

			case 'top' :
				$y1 = $this->textY + $this->textBorder;
				$y2 = $this->textY + $this->textBox['height']
					+ ($this->textPadding * 2) + $this->textBorder;
				break;
		}
		if ($large)
		{
			if ($filled)
			{
				$x1 = 0;
				$x2 = imagesx($this->gdImage);
			}
			else
			{
				$x1 = -1;
				$x2 = imagesx($this->gdImage) + 1;
			}
		}
		else
		{
			switch ($position[1])
			{
				case 'center' :
					$x1 = (imagesx($this->gdImage) - $this->textBox['width']
						- ($this->textPadding * 2)) / 2;
					$x2 = (imagesx($this->gdImage) + $this->textBox['width']
						+ ($this->textPadding * 2)) / 2;
					break;

				case 'left' :
					$x1 = $this->textX + $this->textBorder;
					$x2 = $this->textX + $this->textBox['width']
						+ ($this->textPadding * 2) + $this->textBorder;
					break;

				case 'right' :
					$x1 = imagesx($this->gdImage) - $this->textX - $this->textBox['width']
						- ($this->textPadding * 2) - $this->textBorder;
					$x2 = imagesx($this->gdImage) - $this->textX - $this->textBorder;
					break;
			}
		}

		// On dessine le rectangle.
		if ($filled)
		{
			imagefilledrectangle($this->gdImage, $x1, $y1, $x2, $y2, $color);
		}
		else
		{
			$x1 = $x1 - $level;
			$x2 = $x2 + $level;
			$y1 = $y1 - $level;
			$y2 = $y2 + $level;
			imagerectangle($this->gdImage, $x1, $y1, $x2, $y2, $color);
		}
	}

	/**
	 * Dessine un texte sur l'image.
	 *
	 * @param string $color_html
	 *	Couleur HTML du rectangle.
	 * @param integer $alpha
	 *	Niveau de transparence (entre 0 et 127).
	 * @param integer $level
	 *	Éloignement du texte par rapport au texte défini.
	 * @return void
	 */
	private function _addText($color_html, $alpha = 0, $level = 0)
	{
		// Couleur du texte.
		$rgb = img::html2rgb($color_html);
		$color = imagecolorallocatealpha($this->gdImage, $rgb[0], $rgb[1], $rgb[2], $alpha);

		// Position du texte.
		$position = explode(' ', $this->textPosition);
		switch ($position[0])
		{
			case 'bottom' :
				$y = imagesy($this->gdImage) - $this->textY
					- $this->textPadding - $this->textBorder - $this->textBox[1];
				break;

			case 'center' :
				$y = ((imagesy($this->gdImage) + $this->textBox['height']) / 2)
				   - $this->textBox[1];
				break;

			case 'top' :
				$y = ($this->textBox['height'] + $this->textY + $this->textPadding
					+ $this->textBorder) - $this->textBox[1];
				break;
		}
		switch ($position[1])
		{
			case 'center' :
				$x = (imagesx($this->gdImage) - $this->textBox['width']) / 2;
				break;

			case 'left' :
				$x = $this->textX + $this->textPadding + $this->textBorder;
				break;

			case 'right' :
				$x = imagesx($this->gdImage) - $this->textBox['width']
				   - $this->textPadding - $this->textBorder - $this->textX;
				break;
		}

		// On ajoute le texte sur l'image.
		$x = $x + $level;
		$y = $y + $level;
		imagettftext($this->gdImage, $this->textSize, $this->textAngle,
			$x, $y, $color, $this->fontFile, $this->text);
	}
}
?>