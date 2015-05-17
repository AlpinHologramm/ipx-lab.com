		<h2><a href="<?php echo $tpl->getLink('camera-models'); ?>"><?php echo __('Gestion des appareils photos'); ?></a></h2>

		<ul id="sub_menu">
			<li<?php if ($_GET['section'] == 'camera-models') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('camera-models'); ?>"><?php echo __('Modèles'); ?></a></li>
			<li<?php if ($_GET['section'] == 'camera-brands') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('camera-brands'); ?>"><?php echo __('Marques'); ?></a></li>
		</ul><div id="sub_menu_line"></div><div id="sub_menu_bg"></div>

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
					<label for="nb_per_page"><?php echo ($_GET['section'] == 'camera-models') ? __('Nombre de modèles par page :') : __('Nombre de marques par page :'); ?></label>
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
							<input<?php echo $tpl->getSearch('name'); ?> type="checkbox" name="search_options[<?php echo $tpl->getSearch('pref_col'); ?>_name]" id="search_name" />
							<label for="search_name"><?php echo __('Nom'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('url'); ?> type="checkbox" name="search_options[<?php echo $tpl->getSearch('pref_col'); ?>_url]" id="search_url" />
							<label for="search_url"><?php echo __('Nom d\'URL'); ?></label>
						</p>
					</div>
					<p class="field checkbox">
						<input<?php echo $tpl->getSearch('nb_images'); ?> id="search_nb_images" type="checkbox" name="search_options[<?php echo $tpl->getSearch('pref_col'); ?>_nb_images]" />
						<span><label for="search_nb_images"><?php echo __('Rechercher par nombre d\'images liées :'); ?></label></span>
					</p>
					<div class="field_second">
						<p class="field">
							<label for="search_nb_images_min"><?php echo __('entre'); ?></label>
							&nbsp;
							<input id="search_nb_images_min" name="search_options[<?php echo $tpl->getSearch('pref_col'); ?>_nb_images_min]" class="text" type="text" size="6" maxlength="6" value="<?php echo $tpl->getSearch('nb_images_min'); ?>" />
							&nbsp;
							<label for="search_nb_images_max"><?php echo __('et'); ?></label>
							&nbsp;
							<input id="search_nb_images_max" name="search_options[<?php echo $tpl->getSearch('pref_col'); ?>_nb_images_max]" class="text" type="text" size="6" maxlength="6" value="<?php echo $tpl->getSearch('nb_images_max'); ?>" />
						</p>
					</div>
				</div>
				<p class="field">
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input name="search" class="submit" type="submit" value="<?php echo __('Chercher'); ?>" />
				</p>
			</fieldset>
		</form>

		<p id="position"><span class="current"><?php echo ($_GET['section'] == 'camera-models') ? __('modèles d\'appareils photos') : __('marques d\'appareils photos'); ?></span><?php if (!$tpl->disSearch()) : ?> <span>[<?php echo $tpl->getInfo('nbItems'); ?>]</span><?php endif; ?></p>

<?php if ($tpl->disSearch()) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getSearch('section_link'); ?>"><?php echo __('sortir de la recherche'); ?></a></p>
		<p id="position_special"><?php printf(__('Résultat de votre recherche %s'), '<span id="position_query">' . $tpl->getSearch('query') . '</span>'); ?> <span>[<?php echo $tpl->getInfo('nbItems'); ?>]</span></p>
<?php endif; ?>

<?php if ($tpl->disCam()) : ?>
<?php if ($tpl->disNavigation()) : ?>
		<div class="nav" id="nav_top">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getInfo('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>

		<div id="links_js"></div>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form id="form_edit" action="" method="post">
			<table id="cameras" class="default">
				<tr>
					<th class="m_name"><?php echo __('Nom'); ?></th>
					<th class="m_urlname"><?php echo __('Nom d\'URL'); ?></th>
					<th class="m_nb_images"><?php echo __('Images liées'); ?></th>
				</tr>
<?php $n = 1; while ($tpl->nextCam()) : ?>
				<tr class="selectable_class<?php if (is_integer($n++ / 2)) : ?> even<?php endif; ?>">
					<td class="m_name">
						<input name="<?php echo $tpl->getCam('id'); ?>[name]" type="text" class="text" value="<?php echo $tpl->getCam('name'); ?>" maxlength="255" />
					</td>
					<td class="m_urlname">
						<input name="<?php echo $tpl->getCam('id'); ?>[url]" type="text" class="text" value="<?php echo $tpl->getCam('urlname'); ?>" maxlength="255" />
					</td>
					<td class="m_nb_images">
<?php if ($tpl->getCam('nb_images') > 0 && $tpl->disPerm('albums')) : ?>
						<a href="<?php echo $tpl->getCam('images_link'); ?>">
<?php endif; ?>
							<?php echo $tpl->getCam('nb_images'); ?>
<?php if ($tpl->getCam('nb_images') > 0 && $tpl->disPerm('albums')) : ?>
						</a>
<?php endif; ?>
					</td>
				</tr>
<?php endwhile; ?>
			</table>

			<div id="actions">
				<p>
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input class="submit" name="save" type="submit" value="<?php echo __('Enregistrer les modifications'); ?>" />
				</p>
			</div>

		</form>

<?php elseif (empty($_POST)) : ?>
		<p class="report_zero_item report_msg report_info"><?php echo __('Aucun appareil photos.'); ?></p>
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
