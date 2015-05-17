
<?php include_once(dirname(__FILE__) . '/comments_submenu.tpl.php'); ?>

		<script type="text/javascript">
		//<![CDATA[
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Êtes-vous sûr de vouloir supprimer les commentaires sélectionnés ?')); ?>";
		//]]>
		</script>

		<div id="tools_browse">
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
					<label for="nb_per_page"><?php echo __('Nombre de commentaires par page :'); ?></label>
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
							<input<?php echo $tpl->getSearch('guestbook_message'); ?> type="checkbox" name="search_options[guestbook_message]" id="search_guestbook_message" />
							<label for="search_guestbook_message"><?php echo __('Message'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('guestbook_author'); ?> type="checkbox" name="search_options[guestbook_author]" id="search_guestbook_author" />
							<label for="search_guestbook_author"><?php echo __('Auteur'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('guestbook_email'); ?> type="checkbox" name="search_options[guestbook_email]" id="search_guestbook_email" />
							<label for="search_guestbook_email"><?php echo ucfirst(__('courriel')); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('guestbook_website'); ?> type="checkbox" name="search_options[guestbook_website]" id="search_guestbook_website" />
							<label for="search_guestbook_website"><?php echo ucfirst(__('site Web')); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('guestbook_ip'); ?> type="checkbox" name="search_options[guestbook_ip]" id="search_guestbook_ip" />
							<label for="search_guestbook_ip"><?php echo __('IP'); ?></label>
						</p>
					</div>
					<p class="field checkbox">
						<span><label for="search_rate"><?php echo __('Rechercher par note :'); ?></label></span>
						<select name="search_options[rate]" id="search_rate">
							<?php echo $tpl->getSearch('rate'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<span><label for="search_status"><?php echo __('Rechercher par statut :'); ?></label></span>
						<select name="search_options[status]" id="search_status">
							<?php echo $tpl->getSearch('status'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<span><label for="search_user"><?php echo __('Rechercher par utilisateur :'); ?></label></span>
						<select name="search_options[user]" id="search_user">
							<?php echo $tpl->getSearch('users'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<input<?php echo $tpl->getSearch('date'); ?> id="search_date" type="checkbox" name="search_options[date]" />
						<span><label for="search_date"><?php echo __('Rechercher par date :'); ?></label></span>
					</p>
					<div class="field_second">
						<p class="field">
							<input<?php echo $tpl->getSearch('date_field_guestbook_crtdt'); ?> id="search_date_field_crtdt" type="radio" name="search_options[date_field]" value="guestbook_crtdt" />
							<label for="search_date_field_crtdt"><?php echo __('Date d\'ajout'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('date_field_guestbook_lastupddt'); ?> id="search_date_field_lastupddt" type="radio" name="search_options[date_field]" value="guestbook_lastupddt" />
							<label for="search_date_field_lastupddt"><?php echo __('Date de dernière modification'); ?></label>
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

		<p id="position"><span class="current"><?php echo __('livre d\'or'); ?></span><?php if ($tpl->disPosition('normal')) : ?> [<?php echo $tpl->getInfo('nbItems'); ?>]<?php endif; ?></p>

<?php if ($tpl->disPosition('search')) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getSearch('section_link'); ?>"><?php echo __('sortir de la recherche'); ?></a></p>
		<p id="position_special"><?php printf(__('Résultat de votre recherche %s'), '<span id="position_query">' . $tpl->getSearch('query') . '</span>'); ?> <span>[<?php echo $tpl->getInfo('nbItems'); ?>]</span></p>
<?php endif; ?>
<?php if ($tpl->disPosition('filter')) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getFilter('section_link'); ?>"><?php echo __('afficher tous les commentaires'); ?></a></p>
		<p id="position_special"><?php printf($tpl->getFilter('text'), '<span id="position_filter">' . $tpl->getFilter('value') . '</span>'); ?> <span>[<?php echo $tpl->getInfo('nbItems'); ?>]</span></p>
<?php endif; ?>

<?php if ($tpl->disComments()) : ?>
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
					<option value=".obj_infos"><?php echo __('statistiques'); ?></option>
					<option value=".obj_edition"><?php echo __('édition'); ?></option>
				</select>
			</p>
			<p id="links_js_select">
				<a class="js" href="javascript:select_all();"><?php echo __('tout sélectionner'); ?></a>
				-
				<a class="js" href="javascript:select_invert();"><?php echo __('inverser la sélection'); ?></a>
			</p>
		</div>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form id="form_edit" action="" method="post">
<?php $size = 50; while ($tpl->nextComment($size)) : ?>
			<div id="obj_<?php echo $tpl->getComment('id'); ?>" class="selectable_class obj<?php if ($tpl->disComment('pending')) : ?> obj_pending<?php endif; ?><?php if ($tpl->disComment('deactivate')) : ?> obj_desactived<?php endif; ?><?php if (!$tpl->disComment('publish')) : ?> obj_invisible<?php endif; ?>">
				<div class="obj_image">
					<div>
					<a<?php if (!$tpl->disComment('guest')) : ?> href="<?php echo $tpl->getComment('user_link'); ?>"<?php endif; ?>>
						<img width="50" height="50"
							alt="<?php echo $tpl->getComment('author'); ?>"
							src="<?php echo $tpl->getComment('user_avatar_src'); ?>" />
					</a>
					</div>
				</div>
				<div class="obj_top">
					<span class="obj_checkbox selectable_zone">
						<input id="obj_check_<?php echo $tpl->getComment('id'); ?>" name="select[<?php echo $tpl->getComment('id'); ?>]" class="selectable" type="checkbox" />
					</span>
					<div class="obj_right">
						<span class="obj_status"><?php echo $tpl->getComment('status_msg'); ?></span>
						<span class="obj_group"><?php echo $tpl->getComment('object_type'); ?></span>
					</div>
					<div class="obj_left">
						<p class="obj_basics">
							<span class="obj_title">
<?php if (!$tpl->disComment('guest')) : ?><a href="<?php echo $tpl->getComment('user_link'); ?>"><?php endif; ?>
								<?php echo $tpl->getComment('author'); ?>

<?php if (!$tpl->disComment('guest')) : ?></a><?php endif; ?>
							</span>
							<span class="obj_title">&nbsp;-&nbsp;</span>
<?php if ($tpl->disComment('rate')) : ?>
							<span class="obj_title obj_title_rate_visual" title="<?php printf(__('note : %s'), $tpl->getComment('rate')); ?>"><?php echo $tpl->getComment('rate_visual'); ?></span>
							<span class="obj_title">&nbsp;-&nbsp;</span>
<?php endif; ?>
							<span class="obj_title obj_title_link"><?php echo $tpl->getComment('message_preview'); ?></span>
						</p>
						<p class="obj_links">
							<span class="icon icon_stats show_parts"><a rel="obj_infos_<?php echo $tpl->getComment('id'); ?>" class="js" href="javascript:;"><?php echo __('statistiques'); ?></a></span>
							-
							<span class="icon icon_edit show_parts"><a rel="obj_edition_<?php echo $tpl->getComment('id'); ?>" class="js" href="javascript:;"><?php echo __('édition'); ?></a></span>
						</p>
					</div>
				</div>
				<div style="display:none" class="obj_infos obj_fold" id="obj_infos_<?php echo $tpl->getComment('id'); ?>">
					<div class="obj_fold_inner">
						<table class="light">
							<tr>
								<td><?php echo __('Date d\'ajout'); ?></td>
								<td>
									<?php echo $tpl->getComment('crtdt'); ?>

									<a title="<?php echo __('Afficher tous les commentaires de cette date'); ?>" class="icon_link" href="<?php echo $tpl->getComment('comment_date_link'); ?>"><span class="icon icon_search"></span></a>
								</td>
							</tr>
							<tr>
								<td><?php echo __('Date de dernière modification'); ?></td>
								<td><?php echo $tpl->getComment('lastupddt'); ?></td>
							</tr>
							<tr class="sep">
								<td><?php echo __('Auteur'); ?></td>
								<td>
<?php if ($tpl->disComment('guest')) : ?>
									<?php echo $tpl->getComment('author'); ?>

<?php else : ?>
									<a href="<?php echo $tpl->getComment('user_link'); ?>">
										<?php echo $tpl->getComment('author'); ?>

									</a>
									<a title="<?php echo __('Afficher tous les commentaires de cet utilisateur'); ?>" class="icon_link" href="<?php echo $tpl->getComment('comment_user_link'); ?>"><span class="icon icon_search"></span></a>
<?php endif; ?>
								</td>
							</tr>
							<tr>
								<td><?php echo __('IP'); ?></td>
								<td>
									<?php echo $tpl->getComment('ip'); ?>

									<a title="<?php echo __('Afficher tous les commentaires de cette IP'); ?>" class="icon_link" href="<?php echo $tpl->getComment('comment_ip_link'); ?>"><span class="icon icon_search"></span></a>
								</td>
							</tr>
							<tr>
								<td><?php echo ucfirst(__('courriel')); ?></td>
								<td><?php echo $tpl->getComment('email_link'); ?></td>
							</tr>
							<tr>
								<td><?php echo ucfirst(__('site Web')); ?></td>
								<td><?php echo $tpl->getComment('website_link'); ?></td>
							</tr>
						</table>
					</div>
				</div>
				<div style="display:none" class="obj_edition obj_fold" id="obj_edition_<?php echo $tpl->getComment('id'); ?>">
					<div class="obj_fold_inner">
<?php if ($tpl->disComment('guest')) : ?>
						<p class="field_ftw">
							<label for="author_<?php echo $tpl->getComment('id'); ?>"><?php echo __('Auteur :'); ?></label>
							<input id="author_<?php echo $tpl->getComment('id'); ?>" name="<?php echo $tpl->getComment('id'); ?>[author]" class="text" type="text" maxlength="255" value="<?php echo $tpl->getComment('author'); ?>" />
						</p>
						<p class="field_ftw">
							<label for="email_<?php echo $tpl->getComment('id'); ?>"><?php echo __('Courriel :'); ?></label>
							<input id="email_<?php echo $tpl->getComment('id'); ?>" name="<?php echo $tpl->getComment('id'); ?>[email]" class="text" type="text" maxlength="255" value="<?php echo $tpl->getComment('email'); ?>" />
						</p>
						<p class="field_ftw">
							<label for="website_<?php echo $tpl->getComment('id'); ?>"><?php echo __('Site Web :'); ?></label>
							<input id="website_<?php echo $tpl->getComment('id'); ?>" name="<?php echo $tpl->getComment('id'); ?>[website]" class="text" type="text" maxlength="255" value="<?php echo $tpl->getComment('website'); ?>" />
						</p>
<?php endif; ?>
						<p class="field_ftw">
							<label for="rate_<?php echo $tpl->getComment('id'); ?>"><?php echo __('Note :'); ?></label>
							<select id="rate_<?php echo $tpl->getComment('id'); ?>" name="<?php echo $tpl->getComment('id'); ?>[rate]">
								<?php echo $tpl->getComment('rate_select'); ?>

							</select>
						</p>
						<p class="field_ftw">
							<label for="message_<?php echo $tpl->getComment('id'); ?>"><?php echo __('Message :'); ?></label>
							<textarea class="resizable" name="<?php echo $tpl->getComment('id'); ?>[message]" rows="8" cols="50" id="message_<?php echo $tpl->getComment('id'); ?>"><?php echo $tpl->getComment('message'); ?></textarea>
						</p>
					</div>
				</div>
			</div>
<?php endwhile; ?>

			<div id="actions">
				<p>
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input class="submit" name="save" type="submit" value="<?php echo __('Enregistrer les modifications'); ?>" />
				</p>
				<p>
					<label for="selection_action"><?php echo __('Pour la sélection :'); ?></label>
					<select id="selection_action" name="action">
						<option value="publish"><?php echo __('publier'); ?></option>
						<option value="unpublish"><?php echo __('hors ligne'); ?></option>
						<option value="delete"><?php echo __('supprimer'); ?></option>
					</select>
					<input disabled="disabled" class="submit js_required" id="action_submit" name="selection" type="submit" value="<?php echo __('Valider'); ?>" />
				</p>
			</div>

		</form>

<?php elseif (empty($_POST)) : ?>
		<p class="report_zero_item report_msg report_info"><?php echo __('Aucun commentaire.'); ?></p>
<?php else : ?>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php endif; ?>

<?php if ($tpl->disNavigation()) : ?>
		<div class="nav" id="nav_bottom">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getInfo('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>
