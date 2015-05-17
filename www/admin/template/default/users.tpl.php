
<?php include_once(dirname(__FILE__) . '/users_submenu.tpl.php'); ?>

		<script type="text/javascript">
		//<![CDATA[
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Êtes-vous sûr de vouloir supprimer les utilisateurs sélectionnés ?')); ?>";
		//]]>
		</script>

		<div id="tools_browse">
<?php if ($tpl->disUsers()) : ?>
			<div class="browse">
				<label><?php echo __('Groupe :'); ?></label>

				<select onchange="window.location.href='<?php echo $tpl->getLink('users'); ?>'+this.options[this.selectedIndex].value">
					<?php echo $tpl->getGroupsBrowse(); ?>

				</select>
			</div>
<?php endif; ?>
			<div id="links_tools">
				<span class="icon icon_options show_tool"><a rel="options" class="js" href="javascript:;"><?php echo mb_strtolower(__('Options d\'affichage')); ?></a></span>
				-
				<span class="icon icon_search show_tool"><a rel="search" class="js" href="javascript:;"><?php echo __('recherche'); ?></a></span>
			</div>
		</div>

		<form action="" method="post" style="display:none" class="tool" id="options">
			<fieldset>
				<legend><?php echo __('Options d\'affichage'); ?></legend>
				<p class="field">
					<label for="nb_per_page"><?php echo __('Nombre d\'utilisateurs par page :'); ?></label>
					<input maxlength="3" size="3" value="<?php echo $tpl->getOptions('nb_per_page'); ?>" name="nb_per_page" id="nb_per_page" type="text" class="text focus" />
				</p>
				<p class="field">
					<label for="sortby"><?php echo __('Trier par :'); ?></label>
					<select name="sortby" id="sortby">
						<?php echo $tpl->getOptions('sortby'); ?>

					</select>
					<select name="orderby">
						<?php echo $tpl->getOptions('orderby'); ?>

					</select>
				</p>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input name="options" class="submit" type="submit" value="<?php echo __('Valider'); ?>" />
			</fieldset>
		</form>

		<form action="<?php echo $tpl->getSearch('section_link'); ?>" method="post" class="tool" id="search" style="display:none">
			<fieldset>
				<legend><?php echo __('Moteur de recherche'); ?></legend>
				<p class="field">
					<input value="<?php echo $tpl->getSearch('query'); ?>" class="focus text" type="text" name="search_query" id="search_query" maxlength="255" size="50" />
<?php if ($tpl->disHelp()) : ?>
					<a rel="h_search" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
				</p>
				<p class="field">
					<span class="icon icon_search_adv" onclick="javascript:showhide('#adv_search');"><a href="javascript:;" class="js"><?php echo __('options de recherche'); ?></a></span>
				</p>
				<div id="adv_search" style="display:none">
					<p class="field checkbox">
						<input<?php echo $tpl->getSearch('all_words'); ?> type="checkbox" name="search_options[all_words]" id="search_all_words" />
						<span><label for="search_all_words"><?php echo __('Rechercher tous les mots'); ?></label></span>
					</p>
					<p class="field">
						<?php echo __('Rechercher dans :'); ?>
					</p>
					<div class="field_second">
						<p class="field">
							<input<?php echo $tpl->getSearch('user_login'); ?> type="checkbox" name="search_options[user_login]" id="search_user_login" />
							<label for="search_user_login"><?php echo __('Nom d\'utilisateur'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('user_email'); ?> type="checkbox" name="search_options[user_email]" id="search_user_email" />
							<label for="search_user_email"><?php echo ucfirst(__('courriel')); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('user_website'); ?> type="checkbox" name="search_options[user_website]" id="search_user_website" />
							<label for="search_user_website"><?php echo ucfirst(__('site Web')); ?></label>
						</p>
						<p class="field">
							<input<?php echo $tpl->getSearch('user_crtip'); ?> type="checkbox" name="search_options[user_crtip]" id="search_user_crtip" />
							<label for="search_user_crtip"><?php echo __('IP d\'inscription'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('user_lastvstip'); ?> type="checkbox" name="search_options[user_lastvstip]" id="search_user_lastvstip" />
							<label for="search_user_lastvstip"><?php echo __('IP de dernière visite'); ?></label>
						</p>
					</div>
					<p class="field checkbox">
						<span><label for="search_status"><?php echo __('Rechercher par statut :'); ?></label></span>
						<select name="search_options[status]" id="search_status">
							<?php echo $tpl->getSearch('status'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<input<?php echo $tpl->getSearch('date'); ?> id="search_date" type="checkbox" name="search_options[date]" />
						<span><label for="search_date"><?php echo __('Rechercher par date :'); ?></label></span>
					</p>
					<div class="field_second">
						<p class="field">
							<input<?php echo $tpl->getSearch('date_field_user_crtdt'); ?> id="search_date_field_crtdt" type="radio" name="search_options[date_field]" value="user_crtdt" />
							<label for="search_date_field_crtdt"><?php echo __('Date d\'inscription'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('date_field_user_lastvstdt'); ?> id="search_date_field_lastvstdt" type="radio" name="search_options[date_field]" value="user_lastvstdt" />
							<label for="search_date_field_lastvstdt"><?php echo __('Date de dernière visite'); ?></label>
						</p>
						<p class="field">
							<?php echo __('du'); ?>
							&nbsp;
							<select name="search_options[date_start_day]">
								<?php echo $tpl->getSearch('date_start_day'); ?>

							</select>
							<select name="search_options[date_start_month]">
								<?php echo $tpl->getSearch('date_start_month'); ?>

							</select>
							<select name="search_options[date_start_year]">
								<?php echo $tpl->getSearch('date_start_year'); ?>

							</select>
							&nbsp;
							<?php echo __('au'); ?>
							&nbsp;
							<select name="search_options[date_end_day]">
								<?php echo $tpl->getSearch('date_end_day'); ?>

							</select>
							<select name="search_options[date_end_month]">
								<?php echo $tpl->getSearch('date_end_month'); ?>

							</select>
							<select name="search_options[date_end_year]">
								<?php echo $tpl->getSearch('date_end_year'); ?>

							</select>
						</p>
					</div>
				</div>
				<p class="field">
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input name="search" class="submit" type="submit" value="<?php echo __('Chercher'); ?>" />
				</p>
			</fieldset>
		</form>

		<a id="new_member" href="<?php echo $tpl->getLink('new-user'); ?>"><?php echo __('créer un nouvel utilisateur'); ?></a>

		<p id="position"><span class="current"><?php echo $tpl->getPosition(); ?></span><?php if (!$tpl->disSearch() && !isset($_GET['status'])) : ?> [<?php echo $tpl->getInfo('nbItems'); ?>]<?php endif; ?></p>

<?php if ($tpl->disSearch()) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getSearch('section_link'); ?>"><?php echo __('sortir de la recherche'); ?></a></p>
		<p id="position_special"><?php printf(__('Résultat de votre recherche %s'), '<span id="position_query">' . $tpl->getSearch('query') . '</span>'); ?> <span>[<?php echo $tpl->getInfo('nbItems'); ?>]</span></p>
<?php endif; ?>

<?php if (isset($_GET['status'])) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getFilter('section_link'); ?>"><?php echo __('afficher tous les utilisateurs'); ?></a></p>
		<p id="position_special"><?php echo __('Utilisateurs en attente'); ?> <span>[<?php echo $tpl->getInfo('nbItems'); ?>]</span></p>
<?php endif; ?>

<?php if ($tpl->disUsers()) : ?>
<?php if ($tpl->disNavigation()) : ?>
		<div class="nav" id="nav_top">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getInfo('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>

		<div id="links_js">
			<p id="links_js_show">
				<a class="js" href="javascript:show_all();"><?php echo __('tout montrer'); ?></a>
				-
				<a class="js" href="javascript:hide_all();"><?php echo __('tout cacher'); ?></a>
				&nbsp;<?php echo __('pour'); ?>&nbsp;
				<select id="show_mode">
					<option value=".obj_fold"><?php echo __('tout'); ?></option>
					<option value=".obj_infos"><?php echo __('informations'); ?></option>
					<option value=".obj_stats"><?php echo __('statistiques'); ?></option>
				</select>
			</p>
			<p id="links_js_select">
				<a class="js" href="javascript:select_all();"><?php echo __('tout sélectionner'); ?></a>
				-
				<a class="js" href="javascript:select_invert();"><?php echo __('inverser la sélection'); ?></a>
			</p>
		</div>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form id="users_form" action="" method="post">

<?php $size = 50; while ($tpl->nextUser($size)) : ?>
			<div id="obj_<?php echo $tpl->getUser('id'); ?>" class="selectable_class obj<?php if ($tpl->disUser('pending')) : ?> obj_pending<?php endif; ?><?php if ($tpl->disUser('deactivate')) : ?> obj_desactived<?php endif; ?><?php if (!$tpl->disUser('activate')) : ?> obj_invisible<?php endif; ?>">
				<div class="obj_image">
					<a title="<?php echo __('Modifier l\'avatar'); ?>" href="<?php echo $tpl->getLink('user-avatar/' . $tpl->getUser('id')); ?>">
						<img width="<?php echo $size; ?>" height="<?php echo $size; ?>"
							alt="<?php echo sprintf(__('Avatar de %s'), $tpl->getUser('login')); ?>"
							src="<?php echo $tpl->getUser('avatar_thumb_src'); ?>" />
					</a>
				</div>
				<div class="obj_top">
					<span class="obj_checkbox selectable_zone">
						<input<?php if (!$tpl->disUser('selectable')) : ?> disabled="disabled"<?php endif; ?> id="obj_check_<?php echo $tpl->getUser('id'); ?>" name="select[<?php echo $tpl->getUser('id'); ?>]"<?php if ($tpl->disUser('selectable')) : ?> class="selectable"<?php endif; ?> type="checkbox" />
					</span>
					<div class="obj_right">
						<span class="obj_status">
							<span><?php echo $tpl->getUser('status_msg'); ?></span>
<?php if ($tpl->disUser('superadmin')) : ?>
							<img class="obj_superadmin" width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/crown.png" alt="<?php echo __('Super-administrateur'); ?>" title="<?php echo __('Super-administrateur'); ?>" />
<?php elseif ($tpl->disUser('admin')) : ?>
							<img class="obj_admin" width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/medal.png" alt="<?php echo __('Administrateur'); ?>" title="<?php echo __('Administrateur'); ?>" />
<?php endif; ?>
						</span>
						<span class="obj_group">
<?php if ($tpl->disPerm('users_groups')) : ?>
							<a title="<?php echo sprintf(__('%s fait partie du groupe \'%s\''), $tpl->getUser('login'), $tpl->getUser('group_name')); ?>" href="<?php echo $tpl->getLink('group/' . $tpl->getUser('group_id')); ?>">
<?php endif; ?>
							<?php echo utils::strLimit($tpl->getUser('group_title'), 40); ?>

<?php if ($tpl->disPerm('users_groups')) : ?>
							</a>
<?php endif; ?>
						</span>
					</div>
					<div class="obj_left">
						<p class="obj_basics">
							<span class="obj_title">
								<a class="obj_title_link" title="<?php echo __('Éditer le profil'); ?>" href="<?php echo $tpl->getUser('link'); ?>"><?php echo utils::strLimit($tpl->getUser('login'), 30); ?></a>
							</span>
<?php if ($tpl->disAdmin('users') && $tpl->disUser('activate')) : ?>
							<a title="<?php echo __('Voir dans la galerie'); ?>" class="obj_gallery_link" href="<?php echo $tpl->getUser('gallery_link'); ?>">&nbsp;</a>
<?php endif; ?>
						</p>
						<p class="obj_links">
							<span class="icon icon_profile show_parts"><a rel="obj_infos_<?php echo $tpl->getUser('id'); ?>" class="js" href="javascript:;"><?php echo __('informations'); ?></a></span>
							-
							<span class="icon icon_stats show_parts"><a rel="obj_stats_<?php echo $tpl->getUser('id'); ?>" class="js" href="javascript:;"><?php echo __('statistiques'); ?></a></span>
						</p>
					</div>
				</div>
				<div style="display:none" class="obj_fold obj_infos" id="obj_infos_<?php echo $tpl->getUser('id'); ?>">
					<div class="obj_fold_inner">
						<table class="light">
<?php while ($tpl->nextProfileInfo()) : ?>
							<tr><td><?php echo $tpl->getProfileInfo('name'); ?></td><td><?php echo $tpl->getProfileInfo('value'); ?></td></tr>
<?php endwhile; ?>
<?php while ($tpl->nextProfilePerso()) : ?>
							<tr><td><?php echo $tpl->getProfilePerso('name'); ?></td><td><?php echo $tpl->getProfilePerso('value'); ?></td></tr>
<?php endwhile; ?>
						</table>
					</div>
				</div>
				<div style="display:none" class="obj_fold obj_stats" id="obj_stats_<?php echo $tpl->getUser('id'); ?>">
					<div class="obj_fold_inner">
						<table class="light">
							<tr>
								<td><?php echo __('Date d\'inscription'); ?></td>
								<td><?php echo $tpl->getUser('crtdt'); ?></td>
							</tr>
							<tr>
								<td><?php echo __('IP d\'inscription'); ?></td>
								<td><?php echo $tpl->getUser('crtip'); ?></td>
							</tr>
							<tr>
								<td><?php echo __('Date de dernière visite'); ?></td>
								<td><?php echo $tpl->getUser('lastvstdt'); ?></td>
							</tr>
							<tr>
								<td><?php echo __('IP de dernière visite'); ?></td>
								<td><?php echo $tpl->getUser('lastvstip'); ?></td>
							</tr>
<?php if ($tpl->disAdmin('superadmin')) : ?>
							<tr>
								<td><?php echo __('Activité'); ?></td>
								<td><?php echo $tpl->getUser('nb_logs'); ?></td>
							</tr>
<?php endif; ?>
							<tr>
								<td><?php echo __('Nombre d\'images'); ?></td>
								<td><?php echo $tpl->getUser('nb_images'); ?></td>
							</tr>
							<tr>
								<td><?php echo __('Nombre d\'images en attente'); ?></td>
								<td><?php echo $tpl->getUser('nb_images_pending'); ?></td>
							</tr>
							<tr>
								<td><?php echo __('Nombre de commentaires'); ?></td>
								<td><?php echo $tpl->getUser('nb_comments'); ?></td>
							</tr>
							<tr>
								<td><?php echo __('Nombre de votes'); ?></td>
								<td><?php echo $tpl->getUser('nb_votes'); ?></td>
							</tr>
							<tr>
								<td><?php echo __('Nombre de favoris'); ?></td>
								<td><?php echo $tpl->getUser('nb_favorites'); ?></td>
							</tr>
							<tr>
								<td><?php echo __('Nombre d\'images dans le panier'); ?></td>
								<td><?php echo $tpl->getUser('nb_basket'); ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
<?php endwhile; ?>

			<div id="actions">
				<p>
					<label for="selection_action"><?php echo __('Pour la sélection :'); ?></label>
					<select id="selection_action" name="action">
						<option value="activate"><?php echo __('activer'); ?></option>
						<option value="deactivate"><?php echo __('suspendre'); ?></option>
						<option value="delete"><?php echo __('supprimer'); ?></option>
						<option value="group"><?php echo __('nouveau groupe'); ?></option>
					</select>
					<select class="list_action" id="list_groups" name="group">
						<?php echo $tpl->getGroupsChange(); ?>

					</select>
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input disabled="disabled" class="submit js_required" id="action_submit" name="selection" type="submit" value="<?php echo __('Valider'); ?>" />
				</p>
			</div>

		</form>
<?php else : ?>
		<p class="report_zero_item report_msg report_info"><?php echo __('Aucun utilisateur.'); ?></p>
<?php endif; ?>

<?php if ($tpl->disNavigation()) : ?>
		<div class="nav" id="nav_bottom">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getInfo('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>
