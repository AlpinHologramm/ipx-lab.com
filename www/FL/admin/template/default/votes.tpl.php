		<h2><a href="<?php echo $tpl->getLink('votes'); ?>"><?php echo __('Gestion des votes'); ?></a></h2>

		<div id="sub_menu_line"></div><div id="sub_menu_bg"></div>

		<script type="text/javascript">
		//<![CDATA[
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Êtes-vous sûr de vouloir supprimer les votes sélectionnés ?')); ?>";
		//]]>
		</script>

		<div id="tools_browse">
			<div class="browse browse_wlimit">
				<label><?php echo __('Parcourir :'); ?></label>

				<select onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
					<?php echo $tpl->getMap(); ?>

				</select>
			</div>
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
					<label for="nb_per_page"><?php echo __('Nombre de votes par page :'); ?></label>
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
							<input<?php echo $tpl->getSearch('vote_ip'); ?> type="checkbox" name="search_options[vote_ip]" id="search_vote_ip" />
							<label for="search_vote_ip"><?php echo __('IP'); ?></label>
						</p>
					</div>
					<p class="field checkbox">
						<span><label for="search_user"><?php echo __('Rechercher par utilisateur :'); ?></label></span>
						<select name="search_options[user]" id="search_user">
							<?php echo $tpl->getSearch('users'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<span><label for="search_rate"><?php echo __('Rechercher par note :'); ?></label></span>
						<select name="search_options[rate]" id="search_rate">
							<?php echo $tpl->getSearch('rate'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<input<?php echo $tpl->getSearch('date'); ?> id="search_date" type="checkbox" name="search_options[date]" />
						<input name="search_options[date_field]" type="hidden" value="vote_date" />
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

<?php if ($tpl->disImagesList()) : ?>
		<div class="browse browse_wlimit">
			<label><?php echo __('Images :'); ?></label>
			<select onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<?php echo $tpl->getImagesList(); ?>

			</select>
		</div>
<?php endif; ?>

		<p id="position"><?php echo $tpl->getPosition(); ?><?php if ($tpl->disPosition('normal')) : ?> [<?php echo $tpl->getInfo('nbItems'); ?>]<?php endif; ?></p>

<?php if ($tpl->disPosition('search')) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getSearch('section_link'); ?>"><?php echo __('sortir de la recherche'); ?></a></p>
		<p id="position_special"><?php printf(__('Résultat de votre recherche %s'), '<span id="position_query">' . $tpl->getSearch('query') . '</span>'); ?> <span>[<?php echo $tpl->getInfo('nbItems'); ?>]</span></p>
<?php endif; ?>
<?php if ($tpl->disPosition('filter')) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getFilter('section_link'); ?>"><?php echo __('afficher tous les votes'); ?></a></p>
		<p id="position_special"><?php printf($tpl->getFilter('text'), '<span id="position_filter">' . $tpl->getFilter('value') . '</span>'); ?> <span>[<?php echo $tpl->getInfo('nbItems'); ?>]</span></p>
<?php endif; ?>


<?php if ($tpl->disVotes()) : ?>
<?php if ($tpl->disNavigation()) : ?>
		<div class="nav" id="nav_top">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getInfo('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>

		<div id="links_js">
			<p id="links_js_select">
				<a class="js" href="javascript:select_all();"><?php echo __('tout sélectionner'); ?></a>
				-
				<a class="js" href="javascript:select_invert();"><?php echo __('inverser la sélection'); ?></a>
			</p>
		</div>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form id="form_edit" action="" method="post">
			<table id="votes" class="default">
				<tr>
					<th><?php echo __('Image'); ?></th>
					<th><?php echo __('Album'); ?></th>
					<th><?php echo __('Date'); ?></th>
					<th><?php echo __('Utilisateur'); ?></th>
					<th><?php echo __('IP'); ?></th>
					<th><?php echo __('Note'); ?></th>
					<th class="null"></th>
				</tr>
<?php $n = 1; $size = 80; while ($tpl->nextVote($size)) : ?>
				<tr class="selectable_class<?php if (is_integer($n++ / 2)) : ?> even<?php endif; ?>">
					<td class="thumb">
						<div class="thumb_icon">
							<a title="<?php echo __('Afficher tous les votes de cette image'); ?>" class="icon_link" href="<?php echo $tpl->getVote('vote_image_link'); ?>"><span class="icon icon_search"></span></a>
						</div>
						<div class="thumb_image">
<?php if ($tpl->disPerm('albums_modif')) : ?>
							<a href="<?php echo $tpl->getVote('image_edit_link'); ?>">
<?php endif; ?>
								<img style="padding:<?php echo $tpl->getvote('thumb_center'); ?>"
									<?php echo $tpl->getVote('thumb_size'); ?>
									alt="<?php echo $tpl->getVote('image_name'); ?>"
									src="<?php echo $tpl->getVote('thumb_src'); ?>" />
<?php if ($tpl->disPerm('albums_modif')) : ?>
							</a>
<?php endif; ?>
						</div>
					</td>
					<td>
						<a title="<?php echo __('Afficher tous les votes de cet album'); ?>" class="icon_link" href="<?php echo $tpl->getVote('vote_album_link'); ?>"><span class="icon icon_search"></span></a>
<?php if ($tpl->disPerm('albums_edit')) : ?>
						<a href="<?php echo $tpl->getVote('album_edit_link'); ?>">
<?php endif; ?>
							<?php echo $tpl->getStrLimit($tpl->getVote('album_name'), 30); ?>
<?php if ($tpl->disPerm('albums_edit')) : ?>
						</a>
<?php endif; ?>
					</td>
					<td class="date">
						<a title="<?php echo __('Afficher tous les votes de cette date'); ?>" class="icon_link" href="<?php echo $tpl->getVote('vote_date_link'); ?>"><span class="icon icon_search"></span></a>
						<?php echo $tpl->getVote('date'); ?>
						<br />
						<?php echo $tpl->getVote('time'); ?>

					</td>
					<td>
<?php if (!$tpl->disVote('guest')) : ?>
						<a title="<?php echo __('Afficher tous les votes de cet utilisateur'); ?>" class="icon_link" href="<?php echo $tpl->getVote('vote_user_link'); ?>"><span class="icon icon_search"></span></a>
<?php endif; ?>
<?php if ($tpl->disPerm('users_members') && !$tpl->disVote('guest')) : ?>
						<a href="<?php echo $tpl->getVote('user_edit_link'); ?>">
<?php endif; ?>
							<?php echo $tpl->getVote('user_name'); ?>
<?php if ($tpl->disPerm('users_members') && !$tpl->disVote('guest')) : ?>
						</a>
<?php endif; ?>
					</td>
					<td>
						<a title="<?php echo __('Afficher tous les votes de cette IP'); ?>" class="icon_link" href="<?php echo $tpl->getVote('vote_ip_link'); ?>"><span class="icon icon_search"></span></a>
						<?php echo $tpl->getVote('ip'); ?>

					</td>
					<td>
						<span title="<?php echo $tpl->getVote('rate'); ?>"><?php echo $tpl->getVote('rate_visual'); ?></span>
					</td>
					<td class="selectable_zone">
						<input id="obj_check_<?php echo $tpl->getVote('id'); ?>" name="select[<?php echo $tpl->getVote('id'); ?>]" class="selectable" type="checkbox" />
					</td>
				</tr>
<?php endwhile; ?>
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

<?php elseif (empty($_POST)) : ?>
		<p class="report_zero_item report_msg report_info"><?php echo __('Aucun vote.'); ?></p>
<?php else : ?>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php endif; ?>
