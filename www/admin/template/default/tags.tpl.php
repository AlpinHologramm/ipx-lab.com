		<h2><a href="<?php echo $tpl->getLink('tags'); ?>"><?php echo __('Tags'); ?></a></h2>

		<div id="sub_menu_line"></div><div id="sub_menu_bg"></div>

		<script type="text/javascript">
		//<![CDATA[
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Êtes-vous sûr de vouloir supprimer les tags sélectionnés ?')); ?>";
		//]]>
		</script>

		<div id="tools_browse">
			<div id="links_tools">
				<span class="icon icon_options show_tool"><a rel="options" class="js" href="javascript:;"><?php echo mb_strtolower(__('Options d\'affichage')); ?></a></span>
				-
				<span class="icon icon_add_tags show_tool"><a rel="new_tags" class="js" href="javascript:;"><?php echo mb_strtolower(__('Ajouter des tags')); ?></a></span>
				-
				<span class="icon icon_search show_tool"><a rel="search" class="js" href="javascript:;"><?php echo __('recherche'); ?></a></span>
			</div>
		</div>

		<form action="" method="post" style="display:none" class="tool" id="options">
			<fieldset>
				<legend><?php echo __('Options d\'affichage'); ?></legend>
				<p class="field">
					<label for="nb_per_page"><?php echo __('Nombre de tags par page :'); ?></label>
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

		<form action="" method="post" style="display:none" class="tool" id="new_tags">
			<fieldset>
				<legend><?php echo __('Ajouter des tags'); ?></legend>
				<p class="field">
					<span class="icon icon_add"><a id="add_tags_fields" class="js" href="javascript:;"><?php echo __('plus de tags'); ?></a></span>
				</p>
				<p class="field field_ftw">
					<input name="new_tags[]" type="text" class="focus text" maxlength="255" size="50" />
					<input name="new_tags[]" type="text" class="text" maxlength="255" size="50" />
					<input name="new_tags[]" type="text" class="text" maxlength="255" size="50" />
					<input name="new_tags[]" type="text" class="text" maxlength="255" size="50" />
				</p>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input class="submit" type="submit" value="<?php echo __('Enregistrer'); ?>" />
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
							<input<?php echo $tpl->getSearch('tag_name'); ?> type="checkbox" name="search_options[tag_name]" id="search_tag_name" />
							<label for="search_tag_name"><?php echo __('Nom'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('tag_url'); ?> type="checkbox" name="search_options[tag_url]" id="search_tag_url" />
							<label for="search_tag_url"><?php echo __('Nom d\'URL'); ?></label>
						</p>
					</div>
					<p class="field checkbox">
						<input<?php echo $tpl->getSearch('tag_nb_images'); ?> id="search_tag_nb_images" type="checkbox" name="search_options[tag_nb_images]" />
						<span><label for="search_tag_nb_images"><?php echo __('Rechercher par nombre d\'images liées :'); ?></label></span>
					</p>
					<div class="field_second">
						<p class="field">
							<label for="search_tag_nb_images_min"><?php echo __('entre'); ?></label>
							&nbsp;
							<input id="search_tag_nb_images_min" name="search_options[tag_nb_images_min]" class="text" type="text" size="6" maxlength="6" value="<?php echo $tpl->getSearch('tag_nb_images_min'); ?>" />
							&nbsp;
							<label for="search_tag_nb_images_max"><?php echo __('et'); ?></label>
							&nbsp;
							<input id="search_tag_nb_images_max" name="search_options[tag_nb_images_max]" class="text" type="text" size="6" maxlength="6" value="<?php echo $tpl->getSearch('tag_nb_images_max'); ?>" />
						</p>
					</div>
				</div>
				<p class="field">
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input name="search" class="submit" type="submit" value="<?php echo __('Chercher'); ?>" />
				</p>
			</fieldset>
		</form>

		<p id="position"><span class="current"><?php echo __('tags'); ?></span><?php if (!$tpl->disSearch()) : ?> <span>[<?php echo $tpl->getInfo('nbItems'); ?>]</span><?php endif; ?></p>

<?php if ($tpl->disSearch()) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getSearch('section_link'); ?>"><?php echo __('sortir de la recherche'); ?></a></p>
		<p id="position_special"><?php printf(__('Résultat de votre recherche %s'), '<span id="position_query">' . $tpl->getSearch('query') . '</span>'); ?> <span>[<?php echo $tpl->getInfo('nbItems'); ?>]</span></p>
<?php endif; ?>

<?php if ($tpl->disTags()) : ?>
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
			<table id="tags" class="default">
				<tr>
					<th class="m_name"><?php echo __('Nom'); ?></th>
					<th class="m_urlname"><?php echo __('Nom d\'URL'); ?></th>
					<th class="m_nb_images"><?php echo __('Images liées'); ?></th>
					<th class="null"></th>
				</tr>
<?php $n = 1; while ($tpl->nextTag()) : ?>
				<tr class="selectable_class<?php if (is_integer($n++ / 2)) : ?> even<?php endif; ?>">
					<td class="m_name">
						<input name="<?php echo $tpl->getTag('id'); ?>[name]" type="text" class="text" value="<?php echo $tpl->getTag('name'); ?>" maxlength="255" />
					</td>
					<td class="m_urlname">
						<input name="<?php echo $tpl->getTag('id'); ?>[url]" type="text" class="text" value="<?php echo $tpl->getTag('urlname'); ?>" maxlength="255" />
					</td>
					<td class="m_nb_images">
<?php if ($tpl->getTag('nb_images') > 0 && $tpl->disPerm('albums')) : ?>
						<a href="<?php echo $tpl->getTag('images_tag_link'); ?>">
<?php endif; ?>
							<?php echo $tpl->getTag('nb_images'); ?>
<?php if ($tpl->getTag('nb_images') > 0 && $tpl->disPerm('albums')) : ?>
						</a>
<?php endif; ?>
					</td>
					<td class="selectable_zone">
						<input id="obj_check_<?php echo $tpl->getTag('id'); ?>" name="select[<?php echo $tpl->getTag('id'); ?>]" class="selectable" type="checkbox" />
					</td>
				</tr>
<?php endwhile; ?>
			</table>

			<div id="actions">
				<p>
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input class="submit" name="save" type="submit" value="<?php echo __('Enregistrer les modifications'); ?>" />
				</p>
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

<?php elseif (empty($_POST)) : ?>
		<p class="report_zero_item report_msg report_info"><?php echo __('Aucun tag.'); ?></p>
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
