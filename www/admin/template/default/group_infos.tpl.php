
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form action="" method="post" id="group_edit">
			<div>
				<input name="token" type="hidden" value="<?php echo $tpl->getAdmin('token'); ?>" />
				<fieldset>
					<legend><?php echo __('Informations'); ?></legend>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="name_<?php echo $tpl->getLang('code'); ?>"><?php echo (isset($_GET['object_id']) && $_GET['object_id'] < 4) ? __('Nom (laissez vide pour utiliser le nom par défaut) :') : __('Nom :'); ?></label>
						<input value="<?php echo $tpl->getGroup('name_edit'); ?>" maxlength="64" id="name_<?php echo $tpl->getLang('code'); ?>" name="name[<?php echo $tpl->getLang('code'); ?>]" type="text" class="text onload_focus" />
					</p>
<?php endwhile; ?>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getLang('code'); ?>"><?php echo (isset($_GET['object_id']) && $_GET['object_id'] < 4) ? __('Titre (laissez vide pour utiliser le titre par défaut) :') : __('Titre :'); ?></label>
						<input value="<?php echo $tpl->getGroup('title_edit'); ?>" maxlength="64" id="title_<?php echo $tpl->getLang('code'); ?>" name="title[<?php echo $tpl->getLang('code'); ?>]" type="text" class="text" />
					</p>
<?php endwhile; ?>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="desc_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Description :'); ?></label>
						<textarea class="resizable" rows="4" cols="50" name="desc[<?php echo $tpl->getLang('code'); ?>]" id="desc_<?php echo $tpl->getLang('code'); ?>"><?php echo $tpl->getGroup('desc_edit'); ?></textarea>
					</p>
<?php endwhile; ?>
				</fieldset>
<?php if ($tpl->disGroup('admin_perms')) : ?>
				<br />
				<fieldset>
					<legend><span class="show_parts"><a rel="admin_rights" class="js" href="javascript:;"><?php echo __('Administration'); ?></a></span></legend>
					<div<?php if (!$tpl->disGroup('admin_perms_show')) : ?> style="display:none"<?php endif; ?> id="admin_rights">
						<div class="group_perms">
							<h4><?php echo __('Permissions'); ?></h4>
							<div class="group_perms_left">
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('all'); ?> name="admin[perms][all]" id="all_perms" type="checkbox" />
									<span><label for="all_perms"><?php echo __('Accès total'); ?></label></span>
								</p>
								<p class="field checkbox">
									<a class="js" href="javascript:select_all();"><?php echo __('tout sélectionner'); ?></a>
									-
									<a class="js" href="javascript:select_invert();"><?php echo __('inverser la sélection'); ?></a>
								</p>
								<h5><span class="ftp"><?php echo __('FTP'); ?></span></h5>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('ftp'); ?> class="selectable ftp" name="admin[perms][ftp]" id="ftp" type="checkbox" />
									<span><label for="ftp"><?php echo __('Ajout d\'images par FTP'); ?></label></span>
								</p>
								<h5><span class="objects"><?php echo __('Objets'); ?></span></h5>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('albums_modif'); ?> class="selectable objects" name="admin[perms][albums_modif]" id="albums_modif" type="checkbox" />
									<span><label for="albums_modif"><?php echo __('Modification des images'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('albums_edit'); ?> class="selectable objects" name="admin[perms][albums_edit]" id="albums_edit" type="checkbox" />
									<span><label for="albums_edit"><?php echo __('Édition des informations des images et catégories'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('albums_pending'); ?> class="selectable objects" name="admin[perms][albums_pending]" id="albums_pending" type="checkbox" />
									<span><label for="albums_pending"><?php echo __('Gestion des images en attente de validation'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('albums_add'); ?> class="selectable objects" name="admin[perms][albums_add]" id="albums_add" type="checkbox" />
									<span><label for="albums_add"><?php echo __('Ajout d\'images par le navigateur'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('comments_edit'); ?> class="selectable objects" name="admin[perms][comments_edit]" id="comments_edit" type="checkbox" />
									<span><label for="comments_edit"><?php echo __('Gestion et édition des commentaires'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('comments_options'); ?> class="selectable objects" name="admin[perms][comments_options]" id="comments_options" type="checkbox" />
									<span><label for="comments_options"><?php echo __('Options sur les commentaires'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('admin_votes'); ?> class="selectable objects" name="admin[perms][admin_votes]" id="admin_votes" type="checkbox" />
									<span><label for="admin_votes"><?php echo __('Gestion des votes'); ?></label></span>
								</p>
							</div>
							<div class="group_perms_right">
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('tags'); ?> class="selectable objects" name="admin[perms][tags]" id="tags" type="checkbox" />
									<span><label for="tags"><?php echo __('Gestion des tags et des appareils photos'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('users_members'); ?> class="selectable objects" name="admin[perms][users_members]" id="users_members" type="checkbox" />
									<span><label for="users_members"><?php echo __('Gestion des utilisateurs'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('users_options'); ?> class="selectable objects" name="admin[perms][users_options]" id="users_options" type="checkbox" />
									<span><label for="users_options"><?php echo __('Options sur les utilisateurs'); ?></label></span>
								</p>
								<h5><span class="settings"><?php echo __('Réglages'); ?></span></h5>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('settings_pages'); ?> class="selectable settings" name="admin[perms][settings_pages]" id="settings_pages" type="checkbox" />
									<span><label for="settings_pages"><?php echo __('Gestion des pages'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('settings_widgets'); ?> class="selectable settings" name="admin[perms][settings_widgets]" id="settings_widgets" type="checkbox" />
									<span><label for="settings_widgets"><?php echo __('Gestion des widgets'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('settings_functions'); ?> class="selectable settings" name="admin[perms][settings_functions]" id="settings_functions" type="checkbox" />
									<span><label for="settings_functions"><?php echo __('Gestion des fonctionnalités'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('settings_options'); ?> class="selectable settings" name="admin[perms][settings_options]" id="settings_options" type="checkbox" />
									<span><label for="settings_options"><?php echo __('Options générales'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('settings_themes'); ?> class="selectable settings" name="admin[perms][settings_themes]" id="settings_themes" type="checkbox" />
									<span><label for="settings_themes"><?php echo __('Gestion des thèmes'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('settings_maintenance'); ?> class="selectable settings" name="admin[perms][settings_maintenance]" id="settings_maintenance" type="checkbox" />
									<span><label for="settings_maintenance"><?php echo __('Accès aux outils de maintenance'); ?></label></span>
								</p>
								<h5><span class="infos"><?php echo __('Informations'); ?></span></h5>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('infos_incidents'); ?> class="selectable infos" name="admin[perms][infos_incidents]" id="infos_incidents" type="checkbox" />
									<span><label for="infos_incidents"><?php echo __('Accès aux rapports d\'incidents'); ?></label></span>
								</p>
							</div>
						</div>
						<div class="group_access">
							<h4><?php echo __('Déverrouiller les albums protégés pour :'); ?></h4>
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('admin_access_mode_0'); ?> id="admin_access_nothing" type="radio" name="admin[access_mode]" value="0" />
								<span><label for="admin_access_nothing"><?php echo __('aucun album'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('admin_access_mode_1'); ?> id="admin_access_all" type="radio" name="admin[access_mode]" value="1" />
								<span><label for="admin_access_all"><?php echo __('tous les albums'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('admin_access_mode_2'); ?> id="admin_access_select" type="radio" name="admin[access_mode]" value="2" />
								<span><label for="admin_access_select"><?php echo __('les albums suivants :'); ?></label></span>
							</p>
							<div class="double_select_multiple">
								<div class="double_select_multiple_left">
									<label for="admin_albums_allowed"><?php echo __('Albums déverrouillés :'); ?></label>
									<select class="multiple" size="8" id="admin_albums_allowed" name="admin[albums_allowed][]" multiple="multiple">
<?php if ($tpl->disGroup('admin_albums_allowed')) : ?>
										<?php echo $tpl->getGroup('admin_albums_allowed'); ?>

<?php else : ?>
										<option disabled="disabled">&nbsp;</option>
<?php endif; ?>
									</select>
									<p class="checkbox">
										<input<?php echo $tpl->getGroup('admin_forbid'); ?> type="checkbox" name="admin[forbid]" id="admin_forbid" />
										<span><label for="admin_forbid"><?php echo __('Verrouiller les albums sélectionnés'); ?></label></span>
									</p>
								</div>
								<div class="double_select_multiple_right">
									<label for="admin_albums_forbidden"><?php echo __('Albums verrouillés :'); ?></label>
									<select class="multiple" size="8" id="admin_albums_forbidden" name="admin[albums_forbidden][]" multiple="multiple">
<?php if ($tpl->disGroup('admin_albums_forbidden')) : ?>
										<?php echo $tpl->getGroup('admin_albums_forbidden'); ?>

<?php else : ?>
										<option disabled="disabled">&nbsp;</option>
<?php endif; ?>
									</select>
									<p class="checkbox">
										<input<?php echo $tpl->getGroup('admin_allow'); ?> type="checkbox" name="admin[allow]" id="admin_allow" />
										<span><label for="admin_allow"><?php echo __('Déverrouiller les albums sélectionnés'); ?></label></span>
									</p>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
<?php endif; ?>
<?php if ($tpl->disGroup('gallery_perms')) : ?>
				<br />
				<fieldset>
					<legend><?php echo __('Galerie'); ?></legend>
					<div class="group_perms">
						<h4>
							<?php echo __('Permissions'); ?>
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_gallery_perms" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
						</h4>
						<div class="group_perms_left">
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('read_comments'); ?> name="gallery[perms][read_comments]" id="read_comments" type="checkbox" />
								<span><label for="read_comments"><?php echo __('Lecture des commentaires'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('add_comments'); ?> name="gallery[perms][add_comments]" id="add_comments" type="checkbox" />
								<span><label for="add_comments"><?php echo __('Ajout de commentaires :'); ?></label></span>
							</p>
							<div class="field_second">
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('add_comments_mode_1'); ?> name="gallery[perms][add_comments_mode]" value="1" id="add_comments_direct" type="radio" />
									<span><label for="add_comments_direct"><?php echo __('directement'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('add_comments_mode_0'); ?> name="gallery[perms][add_comments_mode]" value="0" id="add_comments_pending" type="radio" />
									<span><label for="add_comments_pending"><?php echo __('en attente de validation'); ?></label></span>
								</p>
							</div>
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('votes'); ?> name="gallery[perms][votes]" id="votes" type="checkbox" />
								<span><label for="votes"><?php echo __('Votes'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('options'); ?> name="gallery[perms][options]" id="options" type="checkbox" />
								<span><label for="options"><?php echo __('Options d\'affichage'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('adv_search'); ?> name="gallery[perms][adv_search]" id="adv_search" type="checkbox" />
								<span><label for="adv_search"><?php echo __('Recherche avancée'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('members_list'); ?> name="gallery[perms][members_list]" id="members_list" type="checkbox" />
								<span><label for="members_list"><?php echo __('Liste des membres'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('image_original'); ?> name="gallery[perms][image_original]" id="image_original" type="checkbox" />
								<span><label for="image_original"><?php echo __('Accès aux images originales'); ?></label></span>
							</p>
						</div>
<?php if ((isset($_GET['object_id']) && $_GET['object_id'] == 2) === FALSE) : ?>
						<div class="group_perms_right">
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('alert_email'); ?> name="gallery[perms][alert_email]" id="alert_email" type="checkbox" />
								<span><label for="alert_email"><?php echo __('Notifications par courriel'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('upload'); ?> name="gallery[perms][upload]" id="upload" type="checkbox" />
								<span><label for="upload"><?php echo __('Ajout d\'images :'); ?></label></span>
							</p>
							<div class="field_second">
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('upload_mode_1'); ?> name="gallery[perms][upload_mode]" value="1" id="upload_direct" type="radio" />
									<span><label for="upload_direct"><?php echo __('directement'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('upload_mode_0'); ?> name="gallery[perms][upload_mode]" value="0" id="upload_pending" type="radio" />
									<span><label for="upload_pending"><?php echo __('en attente de validation'); ?></label></span>
								</p>
							</div>
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('create_albums'); ?> name="gallery[perms][create_albums]" id="create_albums" type="checkbox" />
								<span><label for="create_albums"><?php echo __('Création d\'albums'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('upload_create_owner'); ?> name="gallery[perms][upload_create_owner]" value="1" id="upload_create_owner" type="checkbox" />
								<span><label for="upload_create_owner"><?php echo __('Ajout d\'images et création d\'albums uniquement dans les catégories dont l\'utilisateur est propriétaire'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGroup('edit'); ?> name="gallery[perms][edit]" id="edit_infos" type="checkbox" />
								<span><label for="edit_infos"><?php echo __('Édition des informations des images et catégories'); ?></label></span>
							</p>
							<div class="field_second">
								<p class="field checkbox">
									<input<?php echo $tpl->getGroup('edit_owner'); ?> name="gallery[perms][edit_owner]" value="1" id="edit_owner" type="checkbox" />
									<span><label for="edit_owner"><?php echo __('uniquement pour celles dont l\'utilisateur est propriétaire'); ?></label></span>
								</p>
							</div>
						</div>
<?php endif; ?>
					</div>
					<div class="group_access">
						<h4><?php echo __('Déverrouiller les albums protégés pour :'); ?></h4>
						<p class="field checkbox">
							<input<?php echo $tpl->getGroup('gallery_access_mode_0'); ?> id="gallery_access_nothing" type="radio" name="gallery[access_mode]" value="0" />
							<span><label for="gallery_access_nothing"><?php echo __('aucun album'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getGroup('gallery_access_mode_1'); ?> id="gallery_access_all" type="radio" name="gallery[access_mode]" value="1" />
							<span><label for="gallery_access_all"><?php echo __('tous les albums'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getGroup('gallery_access_mode_2'); ?> id="gallery_access_select" type="radio" name="gallery[access_mode]" value="2" />
							<span><label for="gallery_access_select"><?php echo __('les albums suivants :'); ?></label></span>
						</p>
						<div class="double_select_multiple">
							<div class="double_select_multiple_left">
								<label for="gallery_albums_allowed"><?php echo __('Albums déverrouillés :'); ?></label>
								<select class="multiple" size="8" id="gallery_albums_allowed" name="gallery[albums_allowed][]" multiple="multiple">
<?php if ($tpl->disGroup('gallery_albums_allowed')) : ?>
									<?php echo $tpl->getGroup('gallery_albums_allowed'); ?>

<?php else : ?>
									<option disabled="disabled">&nbsp;</option>
<?php endif; ?>
								</select>
								<p class="checkbox">
									<input<?php echo $tpl->getGroup('gallery_forbid'); ?> type="checkbox" name="gallery[forbid]" id="gallery_forbid" />
									<span><label for="gallery_forbid"><?php echo __('Verrouiller les albums sélectionnés'); ?></label></span>
								</p>
							</div>
							<div class="double_select_multiple_right">
								<label for="gallery_albums_forbidden"><?php echo __('Albums verrouillés :'); ?></label>
								<select class="multiple" size="8" id="gallery_albums_forbidden" name="gallery[albums_forbidden][]" multiple="multiple">
<?php if ($tpl->disGroup('gallery_albums_forbidden')) : ?>
									<?php echo $tpl->getGroup('gallery_albums_forbidden'); ?>

<?php else : ?>
									<option disabled="disabled">&nbsp;</option>
<?php endif; ?>
								</select>
								<p class="checkbox">
									<input<?php echo $tpl->getGroup('gallery_allow'); ?> type="checkbox" name="gallery[allow]" id="gallery_allow" />
									<span><label for="gallery_allow"><?php echo __('Déverrouiller les albums sélectionnés'); ?></label></span>
								</p>
							</div>
						</div>
					</div>
				</fieldset>
<?php endif; ?>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>
