<?php
/**
 * Traitement des images.
 * Fonctions GD, calculs de dimensions, gestion de fichiers
 * et récupération d'informations utiles.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class img
{
	/**
	 * Doit-on activer la gestion de la transparence ?
	 *
	 * @var boolean
	 */
	public static $gdTransparency = TRUE;



	/**
	 * Supprime toutes les images redimensionnées de l'image $image_id.
	 *
	 * @param integer $image_id
	 *	Identifiant de l'image.
	 * @param string $image_path
	 *	Chemin de l'image.
	 * @param string $image_adddt
	 *	Date d'ajout de l'image.
	 * @return boolean
	 */
	public static function deleteResizedImages($image_id, $image_path, $image_adddt)
	{
		$ok = TRUE;

		// Récupération des informations utiles de toutes les catégories
		// ayant l'image $image_id comme vignette.
		$sql = 'SELECT cat_id,
					   cat_crtdt
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE thumb_id = ' . (int) $image_id;
		$fetch_style = array(
			'column' => array('cat_id', 'cat_crtdt')
		);
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			$ok = FALSE;
		}
		$categories = utils::$db->queryResult;

		// Suppression des vignettes de catégories.
		if (is_array($categories) && utils::$db->nbResult > 0)
		{
			foreach ($categories as $cat_id => &$cat_crtdt)
			{
				$tb_cat = GALLERY_ROOT . '/'
					. img::filepath('tb_cat', $image_path, $cat_id, $cat_crtdt);
				if (file_exists($tb_cat) && !files::unlink($tb_cat))
				{
					$ok = FALSE;
				}
			}
		}

		// Images de tailles intermédiaires.
		$im_resize = GALLERY_ROOT . '/'
			. img::filepath('im_resize', $image_path, $image_id, $image_adddt);
		if (file_exists($im_resize) && !files::unlink($im_resize))
		{
			$ok = FALSE;
		}
		$im_diaporama = GALLERY_ROOT . '/'
			. img::filepath('im_diaporama', $image_path, $image_id, $image_adddt);
		if (file_exists($im_diaporama) && !files::unlink($im_diaporama))
		{
			$ok = FALSE;
		}

		// Images avec filigrane.
		$str_watermark = md5(utils::$config['watermark_params'] . '|' . $image_adddt);
		$im_diaporama_watermark = GALLERY_ROOT . '/'
			. img::filepath('im_diaporama_watermark', $image_path, $image_id, $str_watermark);
		$im_resize_watermark = GALLERY_ROOT . '/'
			. img::filepath('im_resize_watermark', $image_path, $image_id, $str_watermark);
		$im_watermark = GALLERY_ROOT . '/'
			. img::filepath('im_watermark', $image_path, $image_id, $str_watermark);
		if (file_exists($im_diaporama_watermark) && !files::unlink($im_diaporama_watermark))
		{
			$ok = FALSE;
		}
		if (file_exists($im_resize_watermark) && !files::unlink($im_resize_watermark))
		{
			$ok = FALSE;
		}
		if (file_exists($im_watermark) && !files::unlink($im_watermark))
		{
			$ok = FALSE;
		}

		// Images d'édition et vignettes.
		$im_edit = GALLERY_ROOT . '/'
			. img::filepath('im_edit', $image_path, $image_id, $image_adddt);
		$tb_img = GALLERY_ROOT . '/'
			. img::filepath('tb_img', $image_path, $image_id, $image_adddt);
		$tb_wid = GALLERY_ROOT . '/'
			. img::filepath('tb_wid', $image_path, $image_id, $image_adddt);
		if (file_exists($im_edit) && !files::unlink($im_edit))
		{
			$ok = FALSE;
		}
		if (file_exists($tb_img) && !files::unlink($tb_img))
		{
			$ok = FALSE;
		}
		if (file_exists($tb_wid) && !files::unlink($tb_wid))
		{
			$ok = FALSE;
		}

		return $ok;
	}

	/**
	 * Affiche une image depuis un fichier.
	 *
	 * @param string $file
	 *	Chemin de l'image.
	 * @param string $content_type
	 *	Type mime de l'image.
	 * @param string $header_filename
	 *	Nom de fichier à envoyer au navigateur.
	 * @param string $content_disposition
	 *	attachment|inline
	 * @return void
	 */
	public static function displayFile($file, $content_type = 'image/jpeg',
	$header_filename = NULL, $content_disposition = 'inline')
	{
		if (!is_readable($file))
		{
			files::chmodFile($file);
		}
		$header_filename = ($header_filename) ? $header_filename : basename($file);
		header('Content-Type: ' . $content_type);
		header('Content-Disposition: ' . $content_disposition
			. '; filename="' . $header_filename . '"');
		if (!readfile($file))
		{
			header('Content-Type: text/html; charset=' . CONF_CHARSET);
			echo 'Failed to read file.';
		}
		die;
	}

	/**
	 * Retourne le chemin d'un fichier selon le type de fichier demandé.
	 *
	 * @param string $type
	 *	Type de fichier.
	 * @param string $file
	 *	Nom de fichier.
	 * @param integer $id
	 *	Identifiant de l'image.
	 * @param string $str
	 *	Chaîne permettant de générer un nom de fichier unique.
	 * @param string|integer $size
	 *	Dimensions max. de l'image.
	 * @return string
	 */
	public static function filepath($type, $file, $id, $str, $size = '')
	{
		switch ($type)
		{
			case 'im_diaporama' :
			case 'im_diaporama_watermark' :
				if ($size == '')
				{
					$size = utils::$config['diaporama_resize_gd_quality']
					  . '|' . utils::$config['diaporama_resize_gd_width']
					  . '|' . utils::$config['diaporama_resize_gd_height'];
				}
				break;

			case 'im_resize' :
			case 'im_resize_watermark' :
				if ($size == '')
				{
					$size = utils::$config['images_resize_gd_quality']
					  . '|' . utils::$config['images_resize_gd_width']
					  . '|' . utils::$config['images_resize_gd_height'];
				}
				break;

			case 'tb_cat' :
				if ($size == '')
				{
					$size = CONF_THUMBS_CAT_METHOD . '|' . CONF_THUMBS_CAT_QUALITY . '|'
						 . (CONF_THUMBS_CAT_METHOD == 'crop'
						  ? CONF_THUMBS_CAT_WIDTH . '|' . CONF_THUMBS_CAT_HEIGHT
						  : CONF_THUMBS_CAT_SIZE);
				}
				break;

			case 'tb_img' :
				if ($size == '')
				{
					$size = CONF_THUMBS_IMG_METHOD . '|' . CONF_THUMBS_IMG_QUALITY . '|'
						 . (CONF_THUMBS_IMG_METHOD == 'crop'
						  ? CONF_THUMBS_IMG_WIDTH . '|' . CONF_THUMBS_IMG_HEIGHT
						  : CONF_THUMBS_IMG_SIZE);
				}
				break;

			case 'tb_wid' :
				if ($size == '')
				{
					$size = CONF_THUMBS_WID_METHOD . '|' . CONF_THUMBS_WID_QUALITY . '|'
						 . (CONF_THUMBS_WID_METHOD == 'crop'
						  ? CONF_THUMBS_WID_WIDTH . '|' . CONF_THUMBS_WID_HEIGHT
						  : CONF_THUMBS_WID_SIZE);
				}
				break;

			case 'im_backup' :
			case 'im_edit' :
			case 'im_external' :
			case 'im_watermark' :
				break;

			default :
				return;
		}

		return 'cache/' . $type . '/'
			. utils::hashImages($id, $type . '|' . $str . '|' . $size)
			. strtolower(preg_replace('`.+(\.[a-z]{2,4})$`i', '$1', $file));
	}

	/**
	 * Permet de savoir si GD est activé.
	 *
	 * @return boolean
	 */
	public static function gdActive()
	{
		return function_exists('imagetypes');
	}

	/**
	 * Enregistre dans un fichier une image créée par GD.
	 *
	 * @param resource $gd_image
	 *	Identifiant de l'image créée par GD.
	 * @param string $file
	 *	Chemin du fichier.
	 * @param integer $file_type
	 *	Type du fichier.
	 * @param integer $quality
	 *	Qualité du fichier souhaité.
	 * @return boolean
	 */
	public static function gdCreateFile(&$gd_image, $file, $file_type, $quality)
	{
		files::chmodDir(dirname($file));

		$quality = (int) $quality;
		if ($quality > 100)
		{
			$quality = 100;
		}

		try
		{
			switch ($file_type)
			{
				case 1 :
					imagegif($gd_image, $file);
					break;

				case 2 :
					imagejpeg($gd_image, $file, $quality);
					break;

				case 3 :
					imagepng($gd_image, $file, round($quality / 11));
					break;
			}
			imagedestroy($gd_image);

			return TRUE;
		}
		catch (Exception $e)
		{
			return FALSE;
		}
	}

	/**
	 * Crée une image avec GD.
	 *
	 * @param string $file
	 *	Chemin du fichier.
	 * @param integer $file_type
	 *	Type du fichier.
	 * @return resource|string
	 */
	public static function gdCreateImage($file, $file_type)
	{
		$error_message = 'File type not supported: %s.';

		switch ($file_type)
		{
			case 1 :
				return (self::supportType($file_type))
					? imagecreatefromgif($file)
					: sprintf($error_message, 'GIF');

			case 2 :
				return (self::supportType($file_type))
					? imagecreatefromjpeg($file)
					: sprintf($error_message, 'JPG');

			case 3 :
				return (self::supportType($file_type))
					? imagecreatefrompng($file)
					: sprintf($error_message, 'PNG');

			default :
				return sprintf($error_message, $file_type);
		}
	}

	/**
	 * Inverse une image verticalement ou horizontalement.
	 *
	 * @param resource $src_img
	 *	Ressource de l'image source.
	 * @param resource $type
	 *	Type d'inversement : 'horizontal' ou 'vertical'.
	 * @return resource
	 *	Ressource de l'image destination.
	 */
	public static function gdFlip($src_img, $type)
	{
		$width = imagesx($src_img);
		$height = imagesy($src_img);

		$dst_img = imagecreatetruecolor($width, $height);

		// Conservation de la transparence.
		self::gdTransparency($dst_img);

		switch ($type)
		{
			case 'horizontal':
				for ($i = 0; $i < $width; $i++)
				{
					if (!imagecopy($dst_img, $src_img,
					($width - $i - 1), 0, $i, 0, 1, $height))
					{
						return FALSE;
					}
				}
				break;

			case 'vertical':
				for ($i = 0; $i < $height; $i++)
				{
					if (!imagecopy($dst_img, $src_img, 0,
					($height - $i - 1), 0, $i, $width, 1))
					{
						return FALSE;
					}
				}
				break;

			default :
				$dst_img = $src_img;
		}

		imagedestroy($src_img);
		return $dst_img;
	}

	/**
	 * Retourne les informations de GD.
	 *
	 * @return array
	 */
	public static function gdInfos()
	{
		if (!self::gdActive() || !function_exists('gd_info'))
		{
			return FALSE;
		}

		return gd_info();
	}

	/**
	 * Transforme une image selon le paramètre Exif 'Orientation'.
	 *
	 * @param resource $src_img
	 *	Ressource de l'image source.
	 * @param integer $orientation
	 *	Valeur du paramètre EXIf 'Orientation'.
	 * @return boolean|resource
	 *	Ressource de l'image destination,
	 *	ou FALSE en cas d'erreur.
	 */
	public static function gdOrientation($src_img, $orientation)
	{
		switch ((int) $orientation)
		{
			case 2 :
				return self::gdFlip($src_img, 'horizontal');

			case 3 :
				return self::gdRotate($src_img, -180);

			case 4 :
				return self::gdFlip($src_img, 'vertical');

			case 5 :
				$src_img = self::gdRotate($src_img, -90);
				if (is_resource($src_img))
				{
					return self::gdFlip($src_img, 'horizontal');
				}
				return FALSE;

			case 6 :
				return self::gdRotate($src_img, -90);

			case 7 :
				$src_img = self::gdRotate($src_img, -90);
				if (is_resource($src_img))
				{
					return self::gdFlip($src_img, 'vertical');
				}
				return FALSE;

			case 8 :
				return self::gdRotate($src_img, -270);

			default :
				return $src_img;
		}
	}

	/**
	 * Découpe et redimensionne une image avec GD.
	 *
	 * @param resource $src_img
	 *	Ressource de l'image source.
	 * @param integer $src_x
	 *	Coordonnée X de l'image source.
	 * @param integer $src_y
	 *	Coordonnée Y de l'image source.
	 * @param integer $src_w
	 *	Largeur de l'image source.
	 * @param integer $src_h
	 *	Hauteur de l'image source.
	 * @param integer $dst_x
	 *	Coordonnée X de l'image destination.
	 * @param integer $dst_y
	 *	Coordonnée Y de l'image destination.
	 * @param integer $dst_w
	 *	Largeur de l'image destination.
	 * @param integer $dst_h
	 *	Hauteur de l'image destination.
	 * @param integer $dst_canvas_w
	 *	Largeur de l'image finale.
	 * @param integer $dst_canvas_h
	 *	Hauteur de l'image finale.
	 * @param integer $bg_color_red
	 *	Valeur pour le composant rouge de la couleur de fond.
	 * @param integer $bg_color_green
	 *	Valeur pour le composant vert de la couleur de fond.
	 * @param integer $bg_color_blue
	 *	Valeur pour le composant bleu de la couleur de fond.
	 * @return resource
	 *	Ressource de l'image destination.
	 */
	public static function gdResize($src_img, $src_x, $src_y, $src_w, $src_h,
	$dst_x, $dst_y, $dst_w, $dst_h, $dst_canvas_w = 0, $dst_canvas_h = 0,
	$bg_color_red = 0, $bg_color_green = 0, $bg_color_blue = 0)
	{
		// Dimensions de l'image finale.
		$dst_canvas_w = ($dst_canvas_w == 0) ? $dst_w : $dst_canvas_w;
		$dst_canvas_h = ($dst_canvas_h == 0) ? $dst_h : $dst_canvas_h;
		$dst_img = imagecreatetruecolor(round($dst_canvas_w), round($dst_canvas_h));

		// Conservation de la transparence.
		self::gdTransparency($dst_img);

		// Couleur de fond.
		$bg_color = imagecolorallocate($dst_img,
			$bg_color_red, $bg_color_green, $bg_color_blue);
		imagefill($dst_img, 0, 0, $bg_color);

		// Redimensionnement.
		imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y,
			round($dst_w), round($dst_h), round($src_w), round($src_h));

		imagedestroy($src_img);

		return $dst_img;
	}

	/**
	 * Rotation d'une image avec GD.
	 *
	 * @param resource $src_img
	 *	Ressource de l'image source.
	 * @param integer $angle
	 *	Angle de rotation, en degrés.
	 * @return boolean|resource
	 *	Ressource de l'image destination.
	 */
	public static function gdRotate($src_img, $angle)
	{
		if (!function_exists('imagerotate'))
		{
			return FALSE;
		}

		$dst_img = imagerotate($src_img, (int) $angle, 0);

		self::gdTransparency($dst_img);

		return $dst_img;
	}

	/**
	 * Permet de conserver la transparence lors de transformations sur une image.
	 *
	 * @param resource $image
	 *	Ressource de l'image.
	 * @return void
	 */
	public static function gdTransparency(&$image)
	{
		if (self::$gdTransparency && CONF_GD_TRANSPARENCY
		&& function_exists('imagealphablending')
		&& function_exists('imagecolorallocatealpha')
		&& function_exists('imagecolortransparent')
		&& is_resource($image))
		{
			imagealphablending($image, FALSE);
			$color = imagecolorallocatealpha($image, 0, 0, 0, 127);
			imagecolortransparent($image, $color);
		}
	}

	/**
	 * Récupère les dimensions et le type d'une image.
	 *
	 * @param string $file
	 *	Chemin du fichier.
	 * @return array|boolean
	 */
	public static function getImageSize($file)
	{
		if (!file_exists($file) || ($size = getimagesize($file)) === FALSE)
		{
			return FALSE;
		}

		return array(
			'width' => $size[0],
			'height' => $size[1],
			'filetype' => $size[2],
			'tags' => $size[3],
			'mime' => $size['mime']
		);
	}

	/**
	 * Retourne le type MIME d'une image à partir de son extension.
	 *
	 * @param string $ext
	 *	Extension de fichier.
	 * @return array|boolean
	 */
	public static function getMimeType($ext)
	{
		switch ($ext)
		{
			case 'gif' :
				return 'image/gif';
				break;

			case 'png' :
				return 'image/png';
				break;

			default :
				return 'image/jpeg';
		}
	}

	/**
	 * Retourne les dimensions d'une vignette.
	 *
	 * @param array $i
	 *	Dimensions de la vignette stockées dans la base de données.
	 * @param array $type
	 *	Type de vignette : 'cat ou 'img'.
	 * @param integer $forced
	 *	Coté du "carré forcé" de la vignette.
	 * @return array
	 */
	public static function getThumbSize(&$i, $type, $forced = 0)
	{
		$mode = ($type == 'cat') ? CONF_THUMBS_CAT_METHOD : CONF_THUMBS_IMG_METHOD;

		// On extrait les éventuelles informations de vignette disponibles.
		$sconf = FALSE;
		$infos = array();
		if (!empty($i['tb_infos']))
		{
			$infos = explode('.', $i['tb_infos']);
			$sconf = self::isThumbConfCrop($type, $mode, $infos);
		}

		// Vignette externe.
		if ($type == 'cat' && !empty($infos[4]))
		{
			if ($sconf && $mode != 'crop' && $infos[1] != 0)
			{
				$width = $infos[1];
				$height = $infos[2];
			}
			else
			{
				$width = $infos[4];
				$height = $infos[5];
			}
		}

		// Vignette en mode 'prop' retaillée.
		else if ($sconf && $mode != 'crop')
		{
			if ($forced == 0)
			{
				return array('width' => $infos[1], 'height' => $infos[2]);
			}
			$width = $infos[1];
			$height = $infos[2];
		}

		else
		{
			$width = $i['image_width'];
			$height = $i['image_height'];
		}

		return img::thumbSize($type, $width, $height, $forced);
	}

	/**
	 * Convertit une couleur au format HTML vers le format RGB.
	 *
	 * @param string $html
	 *	Code couleur au format HTML.
	 * @return array
	 */
	public static function html2rgb($html)
	{
		$html = str_replace('#', '', $html);
		return list($rgb[0], $rgb[1], $rgb[2]) = sscanf($html, '%2x%2x%2x');
	}

	/**
	 * Retourne le nom d'une image à partir de son nom de fichier.
	 *
	 * @param string $filename
	 *	Nom de fichier de l'image.
	 * @return string
	 */
	public static function imageName($filename)
	{
		$filename = str_replace('_', ' ', $filename);
		$filename = preg_replace('`^(.+)\.[^\.]+$`', '$1', $filename);
		$filename = trim($filename);

		return utils::UTF8($filename);
	}

	/**
	 * Détermine les nouvelles dimensions d'une image
	 * redimensionnée de façon proportionnelle.
	 *
	 * @param integer $img_width
	 *	Largeur de l'image.
	 * @param integer $img_height
	 *	Hauteur de l'image.
	 * @param integer $max_width
	 *	Largeur max. de l'image redimensionnée.
	 * @param integer $max_height
	 *	Hauteur max. de l'image redimensionnée.
	 * @return array
	 */
	public static function imageResize($img_width, $img_height, $max_width, $max_height)
	{
		$ratio_width = $img_width / $max_width;
		$ratio_height = $img_height / $max_height;
		if (($img_width > $max_width)
		&& ($ratio_width >= $ratio_height))
		{
			$img_width = $max_width;
			$img_height = round($img_height / $ratio_width);
		}
		elseif (($img_height > $max_height)
		&& ($ratio_height >= $ratio_width))
		{
			$img_width = round($img_width / $ratio_height);
			$img_height = $max_height;
		}

		return array(
			'width' => (int) $img_width,
			'height' => (int) $img_height
		);
	}


	/**
	 * Calcule les dimensions de l'image à afficher.
	 *
	 * @return void
	 */
	public static function imageSize($image_width, $image_height)
	{
		// Aucun redimensionnement.
		if (utils::$config['images_resize'] == 0)
		{
			return;
		}

		// Redimensionnement par HTML.
		if (utils::$config['images_resize_method'] == 1)
		{
			$max_width = (int) utils::$config['images_resize_html_width'];
			$max_height = (int) utils::$config['images_resize_html_height'];
		}

		// Redimensionnement par GD.
		else if (utils::$config['images_resize_method'] == 2)
		{
			$max_width = (int) utils::$config['images_resize_gd_width'];
			$max_height = (int) utils::$config['images_resize_gd_height'];
		}

		else
		{
			return;
		}

		// Calcule des dimensions de l'image redimensionnée.
		if ($image_width > $max_width || $image_height > $max_height)
		{
			return img::imageResize((int) $image_width,
				(int) $image_height, $max_width, $max_height);
		}
	}

	/**
	 * Les paramètres de rognage de la vignette correspondent-ils
	 * aux dimensions de la configuration actuelle des vignettes ?
	 *
	 * @param array $type
	 *	Type de vignette : 'cat ou 'img'.
	 * @param array $mode
	 *	Méthode de redimensionnement : 'crop' ou 'prop'.
	 * @param array $i
	 *	Informations de rognage.
	 * @return boolean
	 */
	public static function isThumbConfCrop($type, $mode, &$i)
	{
		if ($type == 'cat')
		{
			return ($mode == 'crop' && $i[0] == 0
					&& $i[1] == CONF_THUMBS_CAT_WIDTH
					&& $i[2] == CONF_THUMBS_CAT_HEIGHT)
				|| ($mode == 'prop' && $i[0] == CONF_THUMBS_CAT_SIZE);
		}
		if ($type == 'img')
		{
			return ($mode == 'crop' && $i[0] == 0
					&& $i[1] == CONF_THUMBS_IMG_WIDTH
					&& $i[2] == CONF_THUMBS_IMG_HEIGHT)
				|| ($mode == 'prop' && $i[0] == CONF_THUMBS_IMG_SIZE);
		}
	}

	/**
	 * Détermine les coordonnées d'une image redimensionnée en mode 'crop'.
	 *
	 * @param integer $img_width
	 *	Largeur de l'image.
	 * @param integer $img_height
	 *	Hauteur de l'image.
	 * @param integer $max_width
	 *	Largeur de l'image redimensionnée.
	 * @param integer $max_height
	 *	Hauteur de l'image redimensionnée.
	 * @return array
	 */
	public static function resizeCrop($img_width, $img_height, $max_width, $max_height)
	{
		$x = $y = 0;
		$w = $img_width;
		$h = $img_height;

		$ratio_w = $img_width / $max_width;
		$ratio_h = $img_height / $max_height;
		$ratio_m = ($max_width > $max_height)
			? $max_width / $max_height
			: $max_height / $max_width;

		if ($ratio_w < $ratio_h)
		{
			if ($max_width > $max_height)
			{
				$h = $img_width / $ratio_m;
			}
			else
			{
				$h = $img_width * $ratio_m;
			}
			$y = ($img_height - $h) / 2;
		}
		else
		{
			if ($max_width > $max_height)
			{
				$w = $img_height * $ratio_m;
			}
			else
			{
				$w = $img_height / $ratio_m;
			}
			$x = ($img_width - $w) / 2;
		}

		return array('x' => round($x), 'y' => round($y), 'w' => round($w), 'h' => round($h));
	}

	/**
	 * Détermine les dimensions d'une image redimensionnée en mode 'prop'.
	 *
	 * @param integer $img_width
	 *	Largeur de l'image.
	 * @param integer $img_height
	 *	Hauteur de l'image.
	 * @param integer $max_width
	 *	Largeur de l'image redimensionnée.
	 * @param integer $max_height
	 *	Hauteur de l'image redimensionnée.
	 * @return array
	 */
	public static function resizeProp($img_width, $img_height, $max_width, $max_height)
	{
		return ($img_width < $img_height)
			? array(
				'width' => ($max_height / $img_height) * $img_width,
				'height' => $max_height
			)
			: array(
				'width' => $max_width,
				'height' => ($max_width / $img_width) * $img_height
			);
	}

	/**
	 * Détermine les coordonnées de destination d'une image destinée
	 * à être redimensionnée de manière proportionnelle.
	 *
	 * @param integer $src_width
	 *	Largeur de l'image.
	 * @param integer $src_height
	 *	Hauteur de l'image.
	 * @param integer $dst_width
	 *	Largeur de l'image redimensionnée.
	 * @param integer $dst_height
	 *	Hauteur de l'image redimensionnée.
	 * @return array
	 */
	public static function resizeCoords($src_width, $src_height, $dst_width, $dst_height)
	{
		$dst['w'] = $dst_width;
		$dst['h'] = $dst_height;
		$dst['x'] = 0;
		$dst['y'] = 0;
		if ($src_width < $src_height)
		{
			$dst['w'] = ($dst_height / $src_height) * $src_width;
			$dst['x'] = ($dst_width - $dst['w']) / 2;
		}
		else
		{
			$dst['h'] = ($dst_width / $src_width) * $src_height;
			$dst['y'] = ($dst_height - $dst['h']) / 2;
		}

		return $dst;
	}

	/**
	 * Effectue la rotation d'une image si nécessaire,
	 * et les opérations relatives à cette rotation.
	 *
	 * @param array $image_infos
	 *	Informations utiles de l'image.
	 * @return array
	 *	Informations de l'image modifiées.
	 */
	public static function rotation(&$image_infos)
	{
		if (!utils::$config['images_orientation'] || $image_infos['image_rotation'] == 1)
		{
			return $image_infos;
		}

		// Chemin de l'image.
		$file_path = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/' . $image_infos['image_path'];
		if (!file_exists($file_path))
		{
			return $image_infos;
		}

		// Vérification de la valeur du paramètre 'Orientation'.
		$orientation = (int) $image_infos['image_rotation'];
		if ($orientation < 2 || $orientation > 8)
		{
			return $image_infos;
		}

		// Récupération du type de l'image.
		$i = img::getImageSize($file_path);

		// Transformation de l'image.
		$image = img::gdCreateImage($file_path, $i['filetype']);
		$image = img::gdOrientation($image, $orientation);
		if (!is_resource($image))
		{
			return $image_infos;
		}

		// On effectue un backup de l'image si cela n'a pas déjà été fait.
		$backup_file = GALLERY_ROOT . '/' . img::filepath('im_backup',
			$image_infos['image_path'], $image_infos['image_id'],
			$image_infos['image_adddt']);
		if (!file_exists($backup_file))
		{
			if (!files::copyFile($file_path, $backup_file)
			 || !file_exists($backup_file))
			{
				return $image_infos;
			}
		}

		// Enregistrement de la nouvelle image.
		if (!img::gdCreateFile($image, $file_path, $i['filetype'],
		utils::$config['images_orientation_quality']))
		{
			return $image_infos;
		}

		// Récupération des nouvelles dimensions
		// et du nouveau poids de l'image.
		clearstatcache();
		$i = img::getImageSize($file_path);
		$filesize = filesize($file_path);
		$image_infos['image_width'] = (int) $i['width'];
		$image_infos['image_height'] = (int) $i['height'];
		$image_infos['image_filesize'] = (int) $filesize;

		// Mise à jour du poids des catégories parentes.
		$update_size = $filesize - (int) $image_infos['image_filesize'];
		$parents_ids = alb::getParentsIds($image_infos['cat_id']);
		$up = array(
			'images' => 0,
			'albums' => 0,
			'size' => 0,
			'hits' => 0,
			'rate' => 0,
			'comments' => 0,
			'votes' => 0
		);
		$cat_update = array('a' => $up, 'd' => $up);
		if ($image_infos['image_status'])
		{
			$cat_update['a']['size'] = $update_size;
			$a_up = '+';
			$d_up = FALSE;
		}
		else
		{
			$cat_update['d']['size'] = $update_size;
			$a_up = FALSE;
			$d_up = '+';
		}

		// Mise à jour de la base de données.
		$sql = array(
			'UPDATE ' . CONF_DB_PREF . 'images
				SET image_rotation = "1",
					image_width = ' . (int) $i['width'] . ',
					image_height = ' . (int) $i['height'] . ',
					image_filesize = ' . (int) $filesize . ',
					image_tb_infos = NULL
			  WHERE image_id = ' . (int) $image_infos['image_id'],

			'UPDATE ' . CONF_DB_PREF . 'categories
				SET cat_tb_infos = NULL
			  WHERE thumb_id = ' . (int) $image_infos['image_id'],

			alb::updateParentsStats($cat_update, $a_up, $d_up, $parents_ids)
		);

		// Si succès de la mise à jour de la base de données,
		// on supprime toutes les images redimensionnées de l'image.
		if (utils::$db->exec($sql))
		{
			img::deleteResizedImages($image_infos['image_id'],
				$image_infos['image_path'], $image_infos['image_adddt']);
		}

		// Sinon, on restaure l'image.
		else
		{
			files::copyFile($backup_file, $file_path);
		}

		return $image_infos;
	}

	/**
	 * Détermine les nouvelles dimensions d'une image après une rotation.
	 *
	 * @param array $image_infos
	 *	Informations utiles de l'image.
	 * @param boolean $delete_image_filesize
	 *	Doit-on supprimer le poids de l'image ?
	 * @return array
	 *	Informations de l'image modifiées.
	 */
	public static function rotationSize(&$image_infos, $delete_image_filesize = FALSE)
	{
		$orientation = (int) $image_infos['image_rotation'];

		if (utils::$config['images_orientation']
		&& $orientation > 4 && $orientation < 9)
		{
			$height = $image_infos['image_height'];
			$width = $image_infos['image_width'];
			$image_infos['image_height'] = $width;
			$image_infos['image_width'] = $height;
			if ($delete_image_filesize)
			{
				$image_infos['image_filesize'] = 0;
			}
		}

		return $image_infos;
	}

	/**
	 * Le type d'image $type est-il supporté par GD ?
	 *
	 * @param integer|string $type
	 * @return boolean
	 */
	public static function supportType($type)
	{
		switch ($type)
		{
			case 1 :
			case 'gif' :
				return imagetypes() & IMG_GIF;

			case 2 :
			case 'jpg' :
				return imagetypes() & IMG_JPG;

			case 3 :
			case 'png' :
				return imagetypes() & IMG_PNG;
		}

		return FALSE;
	}

	/**
	 * Génère les valeurs CSS nécessaires au centrage des vignettes.
	 *
	 * @param array|string $type
	 *	Type de vignette (image, image au hasard, catégorie,),
	 *	ou paramètres de l'image.
	 * @param integer $tb_width
	 *	Largeur de la vignette.
	 * @param integer $tb_height
	 *	Hauteur de la vignette.
	 * @param integer $forced
	 *	Coté du "carré forcé" de la vignette.
	 * @return string
	 *	Valeurs CSS pour utilisation avec padding ou margin.
	 */
	public static function thumbCenter($type, $tb_width, $tb_height, $forced = 0)
	{
		$tb_width = (int) $tb_width;
		$tb_height = (int) $tb_height;

		$tb_conf = (is_array($type)) ? $type : self::thumbConf($type);

		// Vignette en taille forcée.
		if ($forced > 0)
		{
			$tb_conf['width'] = $tb_conf['height'] = $forced;
		}

		// Vignette découpée.
		elseif ($tb_conf['mode'] == 'crop')
		{
			// On ne centre la vignette que si elle est plus petite
			// que les dimensions de découpe.
			if ($tb_width < $tb_conf['crop_width']
			&& $tb_height < $tb_conf['crop_height'])
			{
				$tb_conf['width'] = $tb_conf['crop_width'];
				$tb_conf['height'] = $tb_conf['crop_height'];
			}
			else
			{
				return '0';
			}
		}

		$top = 0;
		$right = 0;
		$bottom = 0;
		$left = 0;

		if ($tb_width < $tb_conf['width'])
		{
			$right = ($tb_conf['width'] - $tb_width) / 2;
			$left = $right;
			if (is_float($right))
			{
				$right = floor($right);
				$left = ceil($left);
			}
		}

		if ($tb_height < $tb_conf['height'])
		{
			$top = ($tb_conf['height'] - $tb_height) / 2;
			$bottom = $top;
			if (is_float($top))
			{
				$top = floor($top);
				$bottom = ceil($bottom);
			}
		}

		return $top . 'px ' . $right . 'px ' . $bottom . 'px ' . $left . 'px';
	}

	/**
	 * Récupère les paramètres de configuration des vignettes,
	 * selon le type de vignette.
	 *
	 * @param string $type
	 *	Type de vignette (image, image au hasard, catégorie).
	 * @return array
	 */
	public static function thumbConf($type)
	{
		switch ($type)
		{
			case 'cat' :
				return array(
					'mode' => CONF_THUMBS_CAT_METHOD,
					'crop_width' => (int) CONF_THUMBS_CAT_WIDTH,
					'crop_height' => (int) CONF_THUMBS_CAT_HEIGHT,
					'height' => (int) CONF_THUMBS_CAT_SIZE,
					'width' => (int) CONF_THUMBS_CAT_SIZE
				);

			case 'img' :
				return array(
					'mode' => CONF_THUMBS_IMG_METHOD,
					'crop_width' => (int) CONF_THUMBS_IMG_WIDTH,
					'crop_height' => (int) CONF_THUMBS_IMG_HEIGHT,
					'height' => (int) CONF_THUMBS_IMG_SIZE,
					'width' => (int) CONF_THUMBS_IMG_SIZE
				);

			case 'wid' :
				return array(
					'mode' => CONF_THUMBS_WID_METHOD,
					'crop_width' => (int) CONF_THUMBS_WID_WIDTH,
					'crop_height' => (int) CONF_THUMBS_WID_HEIGHT,
					'height' => (int) CONF_THUMBS_WID_SIZE,
					'width' => (int) CONF_THUMBS_WID_SIZE
				);
		}
	}

	/**
	 * Retourne les dimensions d'une vignette
	 * selon son type et les dimensions de l'image.
	 *
	 * @param string $type
	 *	Type de vignette : 'img', 'cat' ou 'wid'.
	 * @param integer $img_width
	 *	Largeur de l'image.
	 * @param integer $img_height
	 *	Hauteur de l'image.
	 * @param integer $forced
	 *	Coté du "carré forcé" de la vignette.
	 * @return array
	 *	Dimensions de la vignette.
	 */
	public static function thumbSize($type, $img_width, $img_height, $forced = 0)
	{
		$img_width = (int) $img_width;
		$img_height = (int) $img_height;

		$tb_conf = self::thumbConf($type);

		// Taille forcée.
		if ($forced > 0)
		{
			if ($tb_conf['mode'] == 'crop')
			{
				if ($tb_conf['crop_width'] > $tb_conf['crop_height'])
				{
					$r = $tb_conf['crop_width'] / $tb_conf['crop_height'];
					$tb_conf['crop_width'] = $forced;
					$tb_conf['crop_height'] = round($tb_conf['crop_width'] / $r);
				}
				else
				{
					$r = $tb_conf['crop_height'] / $tb_conf['crop_width'];
					$tb_conf['crop_height'] = $forced;
					$tb_conf['crop_width'] = round($tb_conf['crop_height'] / $r);
				}
			}
			elseif ($tb_conf['width'] != $forced)
			{
				$tb_conf['width'] = $tb_conf['height'] = $forced;
			}
		}

		// Retaillage.
		if ($tb_conf['mode'] == 'crop')
		{
			$width = $tb_conf['crop_width'];
			$height = $tb_conf['crop_height'];
		}

		// Redimensionnement proportionnel.
		else
		{
			$width = $tb_conf['width'];
			$height = $tb_conf['height'];
			if ($img_width < $img_height)
			{
				$width = ($height / $img_height) * $img_width;
			}
			else
			{
				$height = ($width / $img_width) * $img_height;
			}
		}

		// Si l'image est plus petite que la vignette,
		// on prend les dimensions de l'image.
		if ($img_width <= $width && $img_height <= $height)
		{
			$width = $img_width;
			$height = $img_height;
		}

		return array('width' => round($width), 'height' => round($height));
	}
}
?>