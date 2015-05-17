
<?php include(dirname(__FILE__) . '/deconnect.tpl.php'); ?>

<?php if ($tpl->disPositionSpecial()) : ?>
		<p id="position_special"><?php echo $tpl->getPositionSpecial(); ?></p>
<?php endif; ?>

<?php if ($tpl->disAdminLink() || $tpl->disGallery('diaporama') || $tpl->disAuthUser() || $tpl->disGallery('basket') || $tpl->disEdit() || $tpl->disDeconnect() || $tpl->disDelete()) : ?>
		<div id="obj_tools">
			<p class="obj_tool_menu_icon" id="obj_tools_link"><a href="javascript:;"><?php echo __('Outils'); ?></a></p>
			<div class="obj_tool_box" id="obj_tool_menu">
				<p class="obj_tool_title"><span><?php echo __('Outils'); ?></span></p>
				<ul class="obj_tool_body">
<?php if ($tpl->disAdminLink()) : ?>
					<li id="tool_admin"><span class="icon icon_admin"><a class="normal_link" href="<?php echo $tpl->getAdminLink(); ?>"><?php echo __('Administrer'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disGallery('diaporama')) : ?>
					<li id="tool_diaporama"><span class="icon icon_diaporama"><a rel="<?php echo $tpl->getDiaporama('query'); ?>" class="js_link" href="javascript:;"><?php echo __('Lancer un diaporama'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disGallery('download_image')) : ?>
					<li id="tool_download"><span class="icon icon_download"><a class="js_link" rel="<?php echo $tpl->getGallery('gallery_path'); ?>/download.php?img=<?php echo $_GET['object_id']; ?>"><?php echo __('Télécharger l\'image'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disAuthUser()) : ?>
					<li id="tool_fav"><span class="icon icon_fav_<?php if ($tpl->disImage('in_favorites')) : ?>remove<?php else : ?>add<?php endif; ?>"><a class="js_link" href="javascript:;"><?php if ($tpl->disImage('in_favorites')) : ?><?php echo __('Retirer des favoris'); ?><?php else : ?><?php echo __('Ajouter aux favoris'); ?><?php endif; ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disGallery('basket')) : ?>
					<li id="tool_basket"><span class="icon icon_basket_<?php if ($tpl->disImage('in_basket')) : ?>remove<?php else : ?>add<?php endif; ?>"><a class="js_link" href="javascript:;"><?php if ($tpl->disImage('in_basket')) : ?><?php echo __('Retirer du panier'); ?><?php else : ?><?php echo __('Ajouter au panier'); ?><?php endif; ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disEdit()) : ?>
					<li class="obj_tool_box_link" id="tool_edit"><span class="icon icon_edit"><a class="js_link" href="javascript:;"><?php echo __('Éditer'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disDelete()) : ?>
					<li id="tool_delete_image"><span class="icon icon_delete"><a class="js_link" href="javascript:;"><?php echo __('Supprimer'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disDeconnect()) : ?>
					<li id="deconnect_object_link"><span class="icon icon_deconnect"><a class="js_link" href="javascript:;"><?php echo __('Déconnexion'); ?></a></span></li>
<?php endif; ?>
				</ul>
			</div>
<?php if ($tpl->disEdit()) : ?>
			<div class="obj_tool_box" id="obj_tool_edit">
				<p class="obj_tool_title"><span><?php echo __('Édition'); ?></span></p>
				<form class="obj_tool_body" method="post" action="">
					<div class="fielditems">
						<p class="field" id="edit_langs">
							<label for="edit_langs"><?php echo __('Langue d\'édition :'); ?></label>
							<select>
<?php while ($tpl->nextLang()) : ?>
								<option value="<?php echo $tpl->getLang('code'); ?>" <?php if ($tpl->disLang('default')) : ?> selected="selected"<?php endif; ?>><?php echo $tpl->getLang('name'); ?></option>
<?php endwhile; ?>
							</select>
						</p>
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('default')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
							<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="edit_title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre :'); ?></label>
							<input name="title[<?php echo $tpl->getLang('code'); ?>]" maxlength="255" id="edit_title_<?php echo $tpl->getLang('code'); ?>" type="text" class="obj_tool_focus text edit_title" value="<?php echo $tpl->getImage('title_lang'); ?>" />
						</p>
<?php endwhile; ?>
						<p class="field field_ftw">
							<label for="edit_urlname"><?php echo __('Nom d\'URL :'); ?></label>
							<input maxlength="255" id="edit_urlname" type="text" class="text" value="<?php echo $tpl->getImage('urlname'); ?>" />
						</p>
<?php if ($tpl->disGallery('tags')) : ?>
						<p class="field field_ftw">
							<label for="edit_tags"><?php echo __('Tags (séparés par une virgule) :'); ?></label>
							<textarea name="tags" rows="2" cols="30" id="edit_tags"><?php echo $tpl->getImage('tags'); ?></textarea>
						</p>
<?php endif; ?>
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('default')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
							<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="edit_desc_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Description :'); ?></label>
							<textarea class="edit_desc" name="desc[<?php echo $tpl->getLang('code'); ?>]" rows="6" cols="30" id="edit_desc_<?php echo $tpl->getLang('code'); ?>"><?php echo $tpl->getImage('desc_lang'); ?></textarea>
						</p>
<?php endwhile; ?>
						<p class="buttons">
							<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
							<input type="reset" class="cancel" value="<?php echo __('Annuler'); ?>" />
						</p>
						<p class="ajax_report message message_success">
							<span><?php echo __('Modifications enregistrées.'); ?></span>
						</p>
						<p class="ajax_report message message_error"><span></span></p>
					</div>
				</form>
			</div>
<?php endif; ?>
		</div>
<?php endif; ?>

		<p id="position"><?php echo $tpl->getPosition(); ?></p>

<?php if ($tpl->disNavigation('top')) : ?>
		<div class="nav" id="nav_top">

			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('image %s|%s'), $tpl->getAlbum('current_image'), $tpl->getAlbum('nb_images')); ?></div>

<?php if ($tpl->disNavigation('prev_inactive')) : ?>
			<span class="first inactive"><?php echo $tpl->getNavigation('first'); ?></span>
			<span class="prev inactive"><?php echo $tpl->getNavigation('prev'); ?></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('prev_active')) : ?>
			<span class="first"><a title="<?php echo __('Première image'); ?>" href="<?php echo $tpl->getNavigation('first_link'); ?>"><?php echo $tpl->getNavigation('first'); ?></a></span>
			<span class="prev"><a title="<?php echo __('Image précédente'); ?>" href="<?php echo $tpl->getNavigation('prev_link'); ?>"><?php echo $tpl->getNavigation('prev'); ?></a></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('next_active')) : ?>
			<span class="next"><a title="<?php echo __('Image suivante'); ?>" href="<?php echo $tpl->getNavigation('next_link'); ?>"><?php echo $tpl->getNavigation('next'); ?></a></span>
			<span class="last"><a title="<?php echo __('Dernière image'); ?>" href="<?php echo $tpl->getNavigation('last_link'); ?>"><?php echo $tpl->getNavigation('last'); ?></a></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('next_inactive')) : ?>
			<span class="next inactive"><?php echo $tpl->getNavigation('next'); ?></span>
			<span class="last inactive"><?php echo $tpl->getNavigation('last'); ?></span>
<?php endif; ?>

		</div>
<?php endif; ?>


		<div id="image_container">
<?php if ($tpl->disImage('resize')) : ?>
			<span style="width:<?php echo $tpl->getImage('width'); ?>px;" id="image_resize_msg">
				<span>
					<?php echo __('Cliquez sur l\'image pour l\'afficher en taille réelle.'); ?>

				</span>
			</span>
<?php endif; ?>
			<div id="image">
<?php if ($tpl->disImage('resize')) : ?>
				<a href="<?php echo $tpl->getImage('link'); ?>">
<?php endif; ?>
<?php if ($tpl->disGallery('images_anti_copy')) : ?>
					<span style="position:absolute;width:<?php echo $tpl->getImage('width'); ?>px;height:<?php echo $tpl->getImage('height'); ?>px;background:url('data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');"></span>
<?php endif; ?>
					<img width="<?php echo $tpl->getImage('width'); ?>"
						height="<?php echo $tpl->getImage('height'); ?>"
						alt="<?php echo $tpl->getImage('title'); ?>"
						src="<?php echo $tpl->getImage('src'); ?>" />
<?php if ($tpl->disImage('resize')) : ?>
				</a>
<?php endif; ?>
			</div>
			<span id="image_filename"><?php echo $tpl->getImage('filename'); ?></span>
		</div>

<?php if ($tpl->disImage('desc') || $tpl->disWidgets('geoloc') || $tpl->disImageTags() || $tpl->disWidgets('stats_images') || $tpl->disExif() || $tpl->disIptc() || $tpl->disXmp()) : ?>
		<div<?php if ($tpl->disVote() || $tpl->disGallery('comments')) : ?> class="image_column"<?php endif; ?> id="image_infos">
<?php if ($tpl->disWidgets('geoloc')) : ?>
			<div class="image_column_bloc" id="image_geoloc">
				<h2>
<?php if ($tpl->disWidgetGeoloc('title')) : ?>
					<?php echo $tpl->getWidgetGeoloc('title'); ?>

<?php else : ?>
					<?php echo __('Géolocalisation'); ?>

<?php endif; ?>
				</h2>
				<div id="gmap_canvas"></div>
				<p><?php echo $tpl->getImage('place'); ?></p>
			</div>
<?php endif; ?>
<?php if ($tpl->disImageTags()) : ?>
			<div class="image_column_bloc" id="image_tags">
				<h2><?php echo __('Tags'); ?></h2>
				<ul>
<?php while ($tpl->nextImageTags()) : ?>
					<li class="icon icon_tag"><a href="<?php echo $tpl->getImageTags('link'); ?>"><?php echo $tpl->getImageTags('name'); ?></a></li>
<?php endwhile; ?>
				</ul>
			</div>
<?php endif; ?>
<?php if ($tpl->disImage('desc')) : ?>
			<div class="image_column_bloc" id="image_description">
				<h2><?php echo __('Description'); ?></h2>
				<p><?php echo $tpl->getImage('desc'); ?></p>
			</div>
<?php endif; ?>
<?php if ($tpl->disWidgets('stats_images')) : ?>
			<div class="image_column_bloc image_infos" id="image_stats">
				<h2>
<?php if ($tpl->disImageStats('title')) : ?>
					<?php echo $tpl->getImageStats('title'); ?>

<?php else : ?>
					<?php echo __('Statistiques'); ?>

<?php endif; ?>
				</h2>
				<ul>
<?php if ($tpl->disImageStats('filesize')) : ?>
					<li><?php printf(__('<span>Poids</span> : %s'), $tpl->getImageStats('filesize')); ?></li>
<?php endif; ?>
<?php if ($tpl->disImageStats('size')) : ?>
					<li><?php printf(__('<span>Dimensions</span> : %s x %s'), $tpl->getImageStats('width'), $tpl->getImageStats('height')); ?></li>
<?php endif; ?>
<?php if ($tpl->disImageStats('hits')) : ?>
					<li><?php printf(__('<span>Visitée</span> : %s fois'), $tpl->getImageStats('hits')); ?></li>
<?php endif; ?>
<?php if ($tpl->disImageStats('favorites')) : ?>
					<li><?php printf(__('<span>Mis en favori</span> : %s fois'), $tpl->getImageStats('favorites')); ?></li>
<?php endif; ?>
<?php if ($tpl->disImageStats('comments')) : ?>
					<li><?php printf(__('<span>Commentaires</span> : %s'), $tpl->getImageStats('comments')); ?></li>
<?php endif; ?>
<?php if ($tpl->disImageStats('votes')) : ?>
					<li id="rate"><?php printf(__('<span>Note moyenne</span> :<br />%1$s<br />(%2$s - %3$s)'), $tpl->getImageStats('rate_visual'), $tpl->getImageStats('rate'), $tpl->getImageStats('votes')); ?></li>
<?php endif; ?>
<?php if ($tpl->disImageStats('added_date')) : ?>
					<li><?php printf(__('<span>Ajoutée le</span> : %s'), $tpl->getImageStats('added_date')); ?></li>
<?php endif; ?>
<?php if ($tpl->disImageStats('added_by')) : ?>
					<li><?php printf(__('<span>Ajoutée par</span> : %s'), $tpl->getImageStats('added_by')); ?></li>
<?php endif; ?>
<?php if ($tpl->disImageStats('created_date')) : ?>
					<li><?php printf(__('<span>Créée le</span> : %s'), $tpl->getImageStats('created_date')); ?></li>
<?php endif; ?>
				</ul>
			</div>
<?php endif; ?>
<?php if ($tpl->disExif()) : ?>
			<div class="image_column_bloc image_infos">
				<h2><?php printf(__('Informations %s'), 'EXIF'); ?></h2>
				<ul>
<?php while ($tpl->nextExif()) : ?>
					<li><?php printf('<span>%s</span> : %s', $tpl->getExif('name'), $tpl->getExif('value')); ?></li>
<?php endwhile; ?>
				</ul>
			</div>
<?php endif; ?>
<?php if ($tpl->disIptc()) : ?>
			<div class="image_column_bloc image_infos">
				<h2><?php printf(__('Informations %s'), 'IPTC'); ?></h2>
				<ul>
<?php while ($tpl->nextIptc()) : ?>
					<li><?php printf('<span>%s</span> : %s', $tpl->getIptc('name'), $tpl->getIptc('value')); ?></li>
<?php endwhile; ?>
				</ul>
			</div>
<?php endif; ?>
<?php if ($tpl->disXmp()) : ?>
			<div class="image_column_bloc image_infos">
				<h2><?php printf(__('Informations %s'), 'XMP'); ?></h2>
				<ul>
<?php while ($tpl->nextXmp()) : ?>
					<li><?php printf('<span>%s</span> : %s', $tpl->getXmp('name'), $tpl->getXmp('value')); ?></li>
<?php endwhile; ?>
				</ul>
			</div>
<?php endif; ?>
		</div>
<?php endif; ?>

		<div<?php if ($tpl->disImage('desc') || $tpl->disWidgets('geoloc') || $tpl->disImageTags() || $tpl->disWidgets('stats_images') || $tpl->disExif() || $tpl->disIptc() || $tpl->disXmp()) : ?> class="image_column"<?php endif; ?> id="image_ratecom">
<?php if ($tpl->disVote()) : ?>
			<div class="image_column_bloc" id="image_rate">
				<h2><?php echo __('Votre note'); ?></h2>
				<p><?php echo $tpl->getVote(); ?></p>
			</div>
<?php endif; ?>
<?php if ($tpl->disGallery('comments')) : ?>
			<div class="image_column_bloc" id="comments">
				<h2><?php echo __('Commentaires'); ?></h2>
<?php while ($tpl->nextComment()) : ?>
				<div id="co<?php echo $tpl->getComment('id'); ?>" class="comment">
					<p class="comment_num"><a href="#co<?php echo $tpl->getComment('id'); ?>"><?php echo $tpl->getComment('num'); ?></a></p>
					<p class="comment_date"><?php printf(__('Le %s à %s,'), $tpl->getComment('date'), $tpl->getComment('time')); ?></p>
<?php if ($tpl->disGallery('users') && $tpl->disGallery('avatars')) : ?>
					<p class="comment_avatar">
						<a<?php if (!$tpl->disComment('guest')) : ?> title="<?php printf(__('Profil de %s'), $tpl->getComment('author')); ?>" href="<?php echo $tpl->getLink('user/' . $tpl->getComment('user_id')); ?>"<?php endif; ?>>
							<img alt="<?php printf(__('Avatar de %s'), $tpl->getComment('author')); ?>" width="50" height="50" src="<?php echo $tpl->getComment('avatar'); ?>" />
						</a>
					</p>
<?php endif; ?>
					<p class="comment_author">
<?php if (!$tpl->disGallery('users') || $tpl->disComment('guest')) : ?>
					<?php printf(__('%s a écrit :'), '<span>' . $tpl->getComment('author_and_website') . '</span>'); ?>

<?php else : ?>
					<?php printf(__('%s a écrit :'), '<span><a title="' . sprintf(__('Profil de %s'), $tpl->getComment('author')) . '" href="' . $tpl->getLink('user/' . $tpl->getComment('user_id')) . '">' . $tpl->getComment('author') . '</a></span>'); ?>

<?php endif; ?>
					</p>
					<p class="comment_message">
						<?php echo $tpl->getComment('message'); ?>

					</p>
				</div>
<?php if (isset($_POST['preview']) && $tpl->getComment('id') == -1) : ?>				
				<p id="comment_preview"><span><?php echo __('Aperçu de votre commentaire.'); ?></span></p>
<?php endif; ?>
<?php endwhile; ?>
<?php if (!$tpl->disComment()) : ?>
				<p id="no_comment"><?php echo __('Aucun commentaire.'); ?></p>
<?php endif; ?>
<?php if ($tpl->disAddComment()) : ?>
				<form action="#comments" method="post" id="add_comment">
					<h3><?php echo __('Ajouter un commentaire'); ?></h3>
					<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

						<p style="display:none" class="field field_ftw">
							<label for="f_email">Email :</label>
							<input value="" maxlength="255" class="text" id="f_email" name="f_email" type="text" />
						</p>
<?php if (!$tpl->disAuthUser()) : ?>
						<p class="field field_ftw">
							<span class="required">*</span>
							<label for="author"><?php echo __('Auteur :'); ?></label>
							<input value="<?php echo $tpl->getAddComment('author'); ?>" maxlength="255" class="text" id="author" name="author" type="text" />
						</p>
						<p class="field field_ftw">
							<?php if ($tpl->disAddComment('required_email')) : ?><span class="required">*</span><?php endif; ?>
							<label for="email"><?php echo __('Courriel (ne sera pas publié) :'); ?></label>
							<input value="<?php echo $tpl->getAddComment('email'); ?>" maxlength="255" class="text" id="email" name="email" type="text" />
						</p>
						<p class="field field_ftw">
							<?php if ($tpl->disAddComment('required_website')) : ?><span class="required">*</span><?php endif; ?>
							<label for="website"><?php echo __('Site Web :'); ?></label>
							<input value="<?php echo $tpl->getAddComment('website') == '' ? 'http://' : $tpl->getAddComment('website'); ?>" maxlength="255" class="text" id="website" name="website" type="text" />
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getAddCommentRemember(); ?> id="remember" name="remember" type="checkbox" />
							<span><label for="remember"><?php echo __('Se souvenir de moi ?'); ?></label></span>
						</p>
<?php endif; ?>
						<p class="field">
<?php if (!$tpl->disAuthUser()) : ?>
							<span class="required">*</span>
<?php endif; ?>
							<label for="message"><?php echo __('Message :'); ?></label>
<?php if ($tpl->disAddComment('smilies')) : ?>
							<span id="smilies"><?php echo $tpl->getAddComment('smilies'); ?></span>
<?php endif; ?>
							<textarea<?php if ($tpl->disReport('error') || $tpl->disReport('warning')) : ?> class="focus"<?php endif; ?> id="message" name="message" rows="6" cols="40"><?php echo $tpl->getAddComment('message'); ?></textarea>
						</p>
<?php if ($tpl->disAddComment('captcha')) : ?>
						<p class="field">
							<span class="required">*</span>
							<label><?php echo __('Prouvez que vous êtes un humain :'); ?></label>
							<?php echo $tpl->getAddComment('captcha'); ?>

						</p>
<?php endif; ?>
						<br />
<?php if (!$tpl->disAuthUser()) : ?>
						<p class="message message_info"><?php echo __('Les champs marqués d\'un astérisque sont obligatoires.'); ?></p>
<?php endif; ?>
<?php if ($tpl->disGallery('comments_moderate')) : ?>
						<p class="message message_info"><?php echo __('Les commentaires sont modérés.'); ?></p>
<?php endif; ?>
						<p>
							<input class="submit" type="submit" value="<?php echo __('Prévisualiser'); ?>" name="preview" />
							<input class="submit<?php if (!empty($_POST['preview'])) : ?> focus<?php endif; ?>" type="submit" value="<?php echo __('Envoyer'); ?>" />
						</p>
					</div>
				</form>
<?php elseif ($tpl->disAddComment('closed')) : ?>
				<br /><br />
				<p id="closed"><span class="message message_info"><?php echo __('Les commentaires sont fermés pour cette image.'); ?></span></p>
<?php endif; ?>
			</div>
<?php endif; ?>
		</div>

		<hr class="sep" />

<?php if ($tpl->disNavigation('bottom')) : ?>
		<div class="nav" id="nav_bottom">

			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('image %s|%s'), $tpl->getAlbum('current_image'), $tpl->getAlbum('nb_images')); ?></div>

<?php if ($tpl->disNavigation('prev_inactive')) : ?>
			<span class="first inactive"><?php echo $tpl->getNavigation('first'); ?></span>
			<span class="prev inactive"><?php echo $tpl->getNavigation('prev'); ?></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('prev_active')) : ?>
			<span class="first"><a title="<?php echo __('Première image'); ?>" href="<?php echo $tpl->getNavigation('first_link'); ?>"><?php echo $tpl->getNavigation('first'); ?></a></span>
			<span class="prev"><a title="<?php echo __('Image précédente'); ?>" href="<?php echo $tpl->getNavigation('prev_link'); ?>"><?php echo $tpl->getNavigation('prev'); ?></a></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('next_active')) : ?>
			<span class="next"><a title="<?php echo __('Image suivante'); ?>" href="<?php echo $tpl->getNavigation('next_link'); ?>"><?php echo $tpl->getNavigation('next'); ?></a></span>
			<span class="last"><a title="<?php echo __('Dernière image'); ?>" href="<?php echo $tpl->getNavigation('last_link'); ?>"><?php echo $tpl->getNavigation('last'); ?></a></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('next_inactive')) : ?>
			<span class="next inactive"><?php echo $tpl->getNavigation('next'); ?></span>
			<span class="last inactive"><?php echo $tpl->getNavigation('last'); ?></span>
<?php endif; ?>

		</div>
<?php endif; ?>

		<script type="text/javascript">
		//<![CDATA[
		var anticsrf = "<?php echo $tpl->getGallery('anticsrf'); ?>";
		var img_id = <?php echo $tpl->getImage('image_id'); ?>;
		var q = "<?php echo $tpl->getGallery('q'); ?>";
		var q_md5 = "<?php echo $tpl->getGallery('q_md5'); ?>";
		var no_right_click = <?php echo $tpl->disGallery('images_anti_copy') ? 'true' : 'false'; ?>;
<?php if ($tpl->disVote()) : ?>
		var image_stat_rate = "<?php echo $tpl->getL10nJS(__('<span>Note moyenne</span> :<br />%1$s<br />(%2$s - %3$s)')); ?>";
<?php endif; ?>
<?php if ($tpl->disGallery('basket')) : ?>
		var msg_basket_add = "<?php echo $tpl->getL10nJS(__('Ajouter au panier')); ?>";
		var msg_basket_del = "<?php echo $tpl->getL10nJS(__('Retirer du panier')); ?>";
<?php endif; ?>
<?php if ($tpl->disAuthUser()) : ?>
		var msg_fav_add = "<?php echo $tpl->getL10nJS(__('Ajouter aux favoris')); ?>";
		var msg_fav_del = "<?php echo $tpl->getL10nJS(__('Retirer des favoris')); ?>";
<?php endif; ?>
<?php if ($tpl->disWidgets('geoloc')) : ?>
		var geoloc_lat = <?php echo $tpl->getImage('latitude'); ?>;
		var geoloc_long = <?php echo $tpl->getImage('longitude'); ?>;
		var geoloc_type = google.maps.MapTypeId.<?php echo $tpl->getGallery('geoloc_type'); ?>;
<?php endif; ?>
<?php if ($tpl->disEdit()) : ?>
		var text_desc = "<?php echo $tpl->getL10nJS(__('Description')); ?>";
<?php if ($tpl->disGallery('tags')) : ?>
		var text_tags = "<?php echo $tpl->getL10nJS(__('Tags')); ?>";
<?php endif; ?>
		var user_lang = "<?php echo $tpl->getGallery('lang_current_code'); ?>";
<?php endif; ?>
<?php if ($tpl->disDelete()) : ?>
		var confirm_delete_image = "<?php echo $tpl->getL10nJS(__('Étes-vous sûr de vouloir supprimer cette image, ainsi que tous les tags, votes et commentaires liés ?')); ?>";
<?php endif; ?>
		var image_width = <?php echo $tpl->getImageStats('width'); ?>;
		var image_height = <?php echo $tpl->getImageStats('height'); ?>;
		//]]>
		</script>
