<?php
/**
 * Opérations sur les avatars.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class avatar
{
	/**
	 * Changement de l'avatar.
	 *
	 * @param array $i
	 *	Informations $_FILES du fichier uploadé.
	 * @param integer $user_id
	 *	Identifiant de l'utilisateur.
	 * @param boolean $user_avatar
	 *	L'utilisateur a-t-il déjà un avatar ?
	 * @param string $error_message
	 *	Message à retourner en cas d'erreur.
	 * @return boolean|string
	 *	- TRUE si succès
	 *	- FALSE si aucun fichier
	 *	- un message d'erreur si échec
	 */
	public static function change($i, $user_id, $user_avatar, $error_message)
	{
		$error_message = 'error:' . $error_message;

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
				return FALSE;

			// Autre erreur.
			default :
				return sprintf(
					$error_message . ' ' . __('Code erreur : %s'),
					$i['error']
				);
		}

		if (!is_uploaded_file($i['tmp_name']))
		{
			return FALSE;
		}

		// On génère aléatoirement un nom de répertoire temporaire.
		$tempdir_path = GALLERY_ROOT . '/cache/up_temp/' . utils::genKey();
		if (!files::mkdir($tempdir_path))
		{
			return $error_message;
		}

		// On déplace l'image vers le répertoire temporaire.
		$dest_filename = $tempdir_path . '/' . basename($i['tmp_name']);
		if (!move_uploaded_file($i['tmp_name'], $dest_filename))
		{
			return $error_message;
		}

		try
		{
			// Le fichier est-il trop lourd ?
			if (filesize($dest_filename) > (1024 * utils::$config['avatars_maxfilesize']))
			{
				throw new Exception('warning:' . __('Le fichier est trop lourd.'));
			}

			// Le format de l'image est-il correct ?
			if (($size = img::getImageSize($dest_filename)) === FALSE
			|| !img::supportType($size['filetype']))
			{
				throw new Exception('warning:' . __('Le fichier n\'est pas une image valide.'));
			}

			// Dimensions de l'image.
			if ($size['width'] < 50 || $size['height'] < 50
			|| $size['width'] > 1000 || $size['height'] > 1000)
			{
				throw new Exception('warning:'
					. sprintf(__('L\'image doit faire au moins %s pixels de coté'
					. ' et pas plus de %s pixels de largeur et %s pixels de hauteur.'),
					50, 1000, 1000));
			}

			$avatar_file = self::_file($user_id);
			$avatar_thumb = self::_file($user_id, TRUE);

			// On redimensionne l'image si nécessaire,
			// au format JPEG (quel que soit le format initial).
			$max_size = (int) utils::$config['avatars_maxsize'];
			if ($size['width'] > $max_size || $size['height'] > $max_size)
			{
				$src_img = img::gdCreateImage($dest_filename, $size['filetype']);
				$resize = img::resizeProp($size['width'], $size['height'], $max_size, $max_size);
				$dst_img = img::gdResize($src_img, 0, 0, $size['width'], $size['height'],
					0, 0, $resize['width'], $resize['height']);
				if (!img::gdCreateFile($dst_img, $avatar_file, 2, 100))
				{
					throw new Exception($error_message);
				}
			}

			// Sinon on déplace directement l'image dans le répertoire des avatars.
			else if (!files::copyFile($dest_filename, $avatar_file))
			{
				throw new Exception($error_message);
			}

			// On crée la vignette de l'avatar au format JPEG
			// (quel que soit le format initial) si nécessaire.
			$thumb_size = utils::$config['avatars_thumbsize'];
			if ($size['width'] > $thumb_size || $size['height'] > $thumb_size)
			{
				$size = img::getImageSize($dest_filename);
				$dst = img::resizeCoords($size['width'], $size['height'],
					$thumb_size, $thumb_size);
				$src_img = img::gdCreateImage($dest_filename, $size['filetype']);
				$dst_img = img::gdResize($src_img, 0, 0, $size['width'], $size['height'],
					$dst['x'], $dst['y'], $dst['w'], $dst['h'],
					$thumb_size, $thumb_size, 255, 255, 255);
				if (!img::gdCreateFile($dst_img, $avatar_thumb, $size['filetype'], 100))
				{
					throw new Exception($error_message);
				}
			}

			// Sinon on copie simplement l'avatar.
			else if (!files::copyFile($dest_filename, $avatar_thumb))
			{
				throw new Exception($error_message);
			}

			// On met à jour la base de données, si nécessaire.
			if (!$user_avatar)
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . 'users
						   SET user_avatar = "1"
						 WHERE user_id = ' . (int) $user_id . '
						 LIMIT 1';
				if (utils::$db->exec($sql) === FALSE || utils::$db->nbResult !== 1)
				{
					throw new Exception($error_message);
				}
			}

			files::rmdir($tempdir_path);

			// Log d'activité.
			if (utils::$purlDir != '/' . CONF_ADMIN_DIR)
			{
				sql::logUserActivity('avatar_change', $user_id);
			}

			return TRUE;
		}
		catch (Exception $e)
		{
			files::rmdir($tempdir_path);
			return $e->getMessage();
		}
	}

	/**
	 * Suppression de l'avatar.
	 *
	 * @param integer $user_id
	 *	Identifiant de l'utilisateur.
	 * @return boolean
	 */
	public static function delete($user_id)
	{
		$avatar_file = self::_file($user_id);
		$avatar_thumb = self::_file($user_id, TRUE);

		// Mise à jour de la base de données.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'users
				   SET user_avatar = "0"
				 WHERE user_id = ' . (int) $user_id . '
				 LIMIT 1';
		if (utils::$db->exec($sql) === FALSE || utils::$db->nbResult !== 1)
		{
			return FALSE;
		}

		// Suppression des fichiers.
		if (file_exists($avatar_file))
		{
			files::unlink($avatar_file);
		}
		if (file_exists($avatar_thumb))
		{
			files::unlink($avatar_thumb);
		}

		// Log d'activité.
		if (utils::$purlDir != '/' . CONF_ADMIN_DIR)
		{
			sql::logUserActivity('avatar_delete', $user_id);
		}

		return TRUE;
	}



	/**
	 * Emplacement de l'avatar sur le disque.
	 *
	 * @param integer $user_id
	 *	Identifiant de l'utilisateur
	 * @param boolean $thumb
	 *	Vignette de l'avatar ?
	 * @return string
	 *	Chemin absolu du fichier.
	 */
	private static function _file($user_id, $thumb = FALSE)
	{
		$thumb = ($thumb) ? '_thumb' : '';
		return GALLERY_ROOT . '/users/avatars/user' . (int) $user_id . $thumb . '.jpg';
	}
}
?>