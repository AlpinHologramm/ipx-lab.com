
		<h2><a href="<?php echo $tpl->getLink('logs'); ?>"><?php echo __('Activité des utilisateurs'); ?></a></h2>

		<div id="sub_menu_line"></div><div id="sub_menu_bg"></div>

		<script type="text/javascript">
		//<![CDATA[
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Êtes-vous sûr de vouloir supprimer les entrées sélectionnées ?')); ?>";
		//]]>
		</script>

		<div id="tools_browse">
<?php if ($tpl->disLogs()) : ?>
			<div class="browse">
				<label><?php echo __('Utilisateur :'); ?></label>

				<select onchange="window.location.href='<?php echo $tpl->getLink('logs'); ?>'+this.options[this.selectedIndex].value">
					<?php echo $tpl->getUsersBrowse(); ?>

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
					<label for="nb_per_page"><?php echo __('Nombre d\'entrées par page :'); ?></label>
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

		<form action="" method="post" class="tool" id="search" style="display:none">
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
							<input<?php echo $tpl->getSearch('log_ip'); ?> type="checkbox" name="search_options[log_ip]" id="search_log_ip" />
							<label for="search_log_ip"><?php echo __('IP'); ?></label>
						</p>
					</div>
					<p class="field checkbox">
						<span><label for="search_action"><?php echo __('Rechercher par action :'); ?></label></span>
						<select name="search_options[action]" id="search_action">
							<?php echo $tpl->getSearch('action'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<span><label for="search_result"><?php echo __('Rechercher par résultat :'); ?></label></span>
						<select name="search_options[result]" id="search_result">
							<?php echo $tpl->getSearch('result'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<input<?php echo $tpl->getSearch('date'); ?> id="search_date" type="checkbox" name="search_options[date]" />
						<input name="search_options[date_field]" type="hidden" value="log_date" />
						<span><label for="search_date"><?php echo __('Rechercher par date :'); ?></label></span>
					</p>
					<div class="field_second">
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

		<p id="position"><span class="current"><?php echo $tpl->getPosition(); ?></span><?php if ($tpl->disPosition('normal')) : ?> [<?php echo $tpl->getInfo('nbEntries'); ?>]<?php endif; ?></p>

<?php if ($tpl->disPosition('search')) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getSearch('section_link'); ?>"><?php echo __('sortir de la recherche'); ?></a></p>
		<p id="position_special"><?php printf(__('Résultat de votre recherche %s'), '<span id="position_query">' . $tpl->getSearch('query') . '</span>'); ?> <span>[<?php echo $tpl->getInfo('nbEntries'); ?>]</span></p>
<?php endif; ?>
<?php if ($tpl->disPosition('filter')) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getFilter('section_link'); ?>"><?php echo __('afficher toutes les entrées'); ?></a></p>
		<p id="position_special"><?php printf($tpl->getFilter('text'), '<span id="position_filter">' . $tpl->getFilter('value') . '</span>'); ?> <span>[<?php echo $tpl->getInfo('nbEntries'); ?>]</span></p>
<?php endif; ?>

<?php if ($tpl->disLogs()) : ?>
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
			</p>
			<p id="links_js_select">
				<a class="js" href="javascript:select_all();"><?php echo __('tout sélectionner'); ?></a>
				-
				<a class="js" href="javascript:select_invert();"><?php echo __('inverser la sélection'); ?></a>
			</p>
		</div>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form id="form_edit" action="" method="post">
			<table id="logs" class="default">
				<tr>
					<th><?php echo __('Utilisateur'); ?></th>
					<th><?php echo __('Action'); ?></th>
					<th><?php echo __('Date'); ?></th>
					<th><?php echo __('IP'); ?></th>
					<th><?php echo __('Résultat'); ?></th>
					<th class="null"></th>
					<th class="null"></th>
				</tr>
<?php $n = 1; while ($tpl->nextEntry()) : ?>
				<tr class="selectable_class<?php if (is_integer($n / 2)) : ?> even<?php endif; ?>">
					<td class="log_user">
						<a title="<?php echo __('Afficher toutes les entrées de cet utilisateur'); ?>" href="<?php echo $tpl->getEntry('logs_user_link'); ?>"><span class="icon icon_search"></span></a>
<?php if ($tpl->disEntry('user_member')) : ?>
						<a class="log_avatar_link" href="<?php echo $tpl->getEntry('user_link'); ?>"><img src="<?php echo $tpl->getEntry('user_avatar_src'); ?>" width="40" height="40" alt="<?php echo sprintf(__('Avatar de %s'), strip_tags($tpl->getEntry('user_login'))); ?>" /></a>
						<a href="<?php echo $tpl->getEntry('user_link'); ?>"><?php echo $tpl->getEntry('user_login'); ?></a>
<?php else : ?>
						<img src="<?php echo $tpl->getEntry('user_avatar_src'); ?>" width="40" height="40" alt="<?php echo sprintf(__('Avatar de %s'), strip_tags($tpl->getEntry('user_login'))); ?>" />
						<?php echo $tpl->getEntry('user_login'); ?>
<?php endif; ?>

					</td>
					<td class="log_action">
						<?php echo $tpl->getEntry('action'); ?>

					</td>
					<td class="log_date">
						<a title="<?php echo __('Afficher toutes les entrées de cette date'); ?>" href="<?php echo $tpl->getEntry('logs_date_link'); ?>"><span class="icon icon_search"></span></a>
						<?php echo $tpl->getEntry('date'); ?>
						<br />
						<?php echo $tpl->getEntry('time'); ?>

					</td>
					<td class="log_ip">
						<a title="<?php echo __('Afficher toutes les entrées de cette IP'); ?>" href="<?php echo $tpl->getEntry('logs_ip_link'); ?>"><span class="icon icon_search"></span></a>
						<?php echo $tpl->getEntry('ip'); ?>

					</td>
					<td class="log_result">
						<?php echo $tpl->getEntry('result'); ?>

					</td>
					<td>
						<span title="<?php echo __('Détails'); ?>" class="icon icon_details"><a rel="details_<?php echo $tpl->getEntry('id'); ?>" class="js" href="javascript:;"></a></span>
					</td>
					<td class="selectable_zone">
						<input id="obj_check_<?php echo $tpl->getEntry('id'); ?>" name="select[<?php echo $tpl->getEntry('id'); ?>]" class="selectable" type="checkbox" />
					</td>
				</tr>
				<tr class="log_details item_fold" id="details_<?php echo $tpl->getEntry('id'); ?>">
					<td colspan="5">
						<div>
<?php if ($tpl->disEntry('reject')) : ?>
							<div>
								<?php echo __('Cause du rejet :'); ?>
								<?php echo $tpl->getEntry('reject_cause'); ?>
								[<span class="log_match"><?php echo $tpl->getEntry('reject_match'); ?></span>]
							</div>
<?php endif; ?>
							<div>
								<?php echo $tpl->getEntry('page'); ?>

							</div>
<?php if ($tpl->disEntry('post')) : ?>
							<div>
								<table class="light">
								<tbody>
									<tr class="th">
										<th class="title"><?php echo __('paramètre'); ?></th>
										<th class="title"><?php echo __('valeur'); ?></th>
									</tr>
<?php while ($tpl->nextPost()) : ?>
									<tr>
										<td><?php echo $tpl->getPost('param'); ?></td>
										<td><?php echo $tpl->getPost('value'); ?></td>
									</tr>
<?php endwhile; ?>
								</tbody>
								</table>
							</div>
<?php endif; ?>
						</div>
					</td>
				</tr>
				<tr class="log_space"><td></td></tr>
<?php $n++; endwhile; ?>
			</table>

			<div id="actions">
				<p>
					<label for="selection_action"><?php echo __('Pour la sélection :'); ?></label>
					<select id="selection_action" name="action">
						<option value="delete"><?php echo __('supprimer'); ?></option>
					</select>
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input disabled="disabled" class="submit js_required" id="action_submit" name="selection" type="submit" value="<?php echo __('Valider'); ?>" />
				</p>
			</div>

		</form>

<?php if ($tpl->disNavigation()) : ?>
		<div class="nav" id="nav_bottom">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getInfo('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>

<?php else : ?>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<p class="report_zero_item report_msg report_info"><?php echo __('Aucune entrée.'); ?></p>

<?php endif; ?>
