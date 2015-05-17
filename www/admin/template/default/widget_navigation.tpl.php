
<?php include_once(dirname(__FILE__) . '/widgets_submenu.tpl.php'); ?>

		<p id="position"><a href="<?php echo $tpl->getLink('widgets'); ?>"><?php echo __('Widgets'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('widget/navigation'); ?>"><?php echo $tpl->getWidgetNavigation('title_default'); ?></a></span></p>

		<form class="obj_w_form" id="widget_navigation" action="" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<fieldset>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre (laissez vide pour utiliser le titre par défaut) :'); ?></label>
						<input value="<?php echo $tpl->getWidgetNavigation('title'); ?>" id="title_<?php echo $tpl->getLang('code'); ?>" name="title[<?php echo $tpl->getLang('code'); ?>]" type="text" class="text onload_focus" maxlength="128" size="40" />
					</p>
<?php endwhile; ?>
					<br />
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetNavigation('categories')) : ?> checked="checked"<?php endif; ?> id="categories" name="categories" type="checkbox" />
						<span><label for="categories"><?php echo __('Liste des albums'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetNavigation('search')) : ?> checked="checked"<?php endif; ?> id="search" name="search" type="checkbox" />
						<span><label for="search"><?php echo __('Moteur de recherche'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetNavigation('neighbours')) : ?> checked="checked"<?php endif; ?> id="neighbours" name="neighbours" type="checkbox" />
						<span><label for="neighbours"><?php echo __('Catégories voisines'); ?></label></span>
					</p>
					<br />
					<p class="field">
						<a href="<?php echo $tpl->getLink('pages'); ?>"><?php echo __('gestion des pages'); ?></a>
					</p>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>
