
<?php include_once(dirname(__FILE__) . '/widgets_submenu.tpl.php'); ?>

		<p id="position" class="position_help">
			<a href="<?php echo $tpl->getLink('widgets'); ?>"><?php echo __('Widgets'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('widget/image'); ?>"><?php echo $tpl->getWidgetImage('title_default'); ?></a></span>
<?php if ($tpl->disHelp()) : ?>
			<a rel="h_widget_image" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
		</p>

		<form class="obj_w_form" id="widget_image" action="" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<fieldset>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre (laissez vide pour utiliser le titre par défaut) :'); ?></label>
						<input value="<?php echo $tpl->getWidgetImage('title'); ?>" id="title_<?php echo $tpl->getLang('code'); ?>" name="title[<?php echo $tpl->getLang('code'); ?>]" type="text" class="text onload_focus" maxlength="128" size="40" />
					</p>
<?php endwhile; ?>
					<br />
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetImage('last')) : ?> checked="checked"<?php endif; ?> id="last" name="mode" value="last" type="radio" />
						<span><label for="last"><?php echo __('Dernières images'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetImage('fixed')) : ?> checked="checked"<?php endif; ?> id="fixed" name="mode" value="fixed" type="radio" />
						<span><label for="fixed"><?php echo __('Images fixes'); ?></label></span>
					</p>
					<div class="field_second">
						<p class="field">
							<label for="images"><?php echo __('Identifiants des images (séparés par une virgule) :'); ?></label>
							<input value="<?php echo $tpl->getWidgetImage('images'); ?>" id="images" name="images" size="40" maxlength="128" type="text" class="text" />
						</p>
					</div>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetImage('random')) : ?> checked="checked"<?php endif; ?> id="random" name="mode" value="random" type="radio" />
						<span><label for="random"><?php echo __('Images aléatoires'); ?></label></span>
					</p>
					<div class="field_second">
						<p class="field">
							<label for="albums"><?php echo __('Sélectionner les images parmi ces albums (identifiants séparés par une virgule) :'); ?></label>
							<input value="<?php echo $tpl->getWidgetImage('albums'); ?>" id="albums" name="albums" size="40" maxlength="128" type="text" class="text" />
						</p>
					</div>
					<br />
					<p class="field">
						<label for="nb_images"><?php echo __('Nombre de vignettes :'); ?></label>
						<input value="<?php echo $tpl->getWidgetImage('nb_images'); ?>" id="nb_images" name="nb_images" size="2" maxlength="2" type="text" class="text" />
					</p>
					<p class="field">
						<?php echo __('Dimensions des vignettes (entre 50 et 300 pixels) :'); ?>
					</p>
					<div class="field_second">
						<p class="field">
							<input<?php echo $tpl->getWidgetImage('thumbs_wid_method_prop'); ?> id="thumbs_wid_prop" type="radio" name="thumbs_wid_method" value="prop" />
							<label for="thumbs_wid_prop"><?php echo __('Taille maximale :'); ?></label>
							<input value="<?php echo $tpl->getWidgetImage('thumbs_wid_size'); ?>" type="text" class="text" maxlength="3" size="3" name="thumbs_wid_size" />
							<?php echo __('pixels'); ?>
						</p>
						<p class="field">
							<input<?php echo $tpl->getWidgetImage('thumbs_wid_method_crop'); ?> id="thumbs_wid" type="radio" name="thumbs_wid_method" value="crop" />
							<label for="thumbs_wid"><?php echo __('Rogner les vignettes :'); ?></label>
							<input value="<?php echo $tpl->getWidgetImage('thumbs_wid_width'); ?>" type="text" class="text" maxlength="3" size="3" name="thumbs_wid_width" />
							X
							<input value="<?php echo $tpl->getWidgetImage('thumbs_wid_height'); ?>" type="text" class="text" maxlength="3" size="3" name="thumbs_wid_height" />
							<?php echo __('pixels'); ?>
						</p>
					</div>
					<p class="field">
						<label for="thumbs_wid_quality"><?php echo __('Qualité (entre 0 et 100) :'); ?></label>
						<input value="<?php echo $tpl->getWidgetImage('thumbs_wid_quality'); ?>" id="thumbs_wid_quality" type="text" class="text" maxlength="3" size="3" name="thumbs_wid_quality" />
					</p>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>
