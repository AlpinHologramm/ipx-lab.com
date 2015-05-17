
<?php include_once(dirname(__FILE__) . '/widgets_submenu.tpl.php'); ?>

		<p id="position"><a href="<?php echo $tpl->getLink('widgets'); ?>"><?php echo __('Widgets'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('widget/options'); ?>"><?php echo $tpl->getWidgetOptions('title_default'); ?></a></span></p>

		<form id="widget_options" action="" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<fieldset id="fieldset_title">
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre (laissez vide pour utiliser le titre par défaut) :'); ?></label>
						<input value="<?php echo $tpl->getWidgetOptions('title'); ?>" id="title_<?php echo $tpl->getLang('code'); ?>" name="title[<?php echo $tpl->getLang('code'); ?>]" type="text" class="text onload_focus" maxlength="128" size="40" />
					</p>
<?php endwhile; ?>
				</fieldset>
				<br />
				<fieldset>
					<legend><?php echo __('Apparence'); ?></legend>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('styles')) : ?> checked="checked"<?php endif; ?> id="styles" name="styles" type="checkbox" />
						<span><label for="styles"><?php echo __('Style'); ?></label></span>
					</p>
				</fieldset>
				<br />
				<fieldset>
					<legend><?php echo __('Images'); ?></legend>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('recent')) : ?> checked="checked"<?php endif; ?> id="recent" name="recent" type="checkbox" />
						<span><label for="recent"><?php echo __('Images récentes'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('image_size')) : ?> checked="checked"<?php endif; ?> id="image_size" name="image_size" type="checkbox" />
						<span><label for="image_size"><?php echo __('Ajustement de la taille des images'); ?></label></span>
					</p>
				</fieldset>
				<br />
				<fieldset>
					<legend><?php echo __('Vignettes'); ?></legend>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('nb_thumbs')) : ?> checked="checked"<?php endif; ?> id="nb_thumbs" name="nb_thumbs" type="checkbox" />
						<span><label for="nb_thumbs"><?php echo __('Nombre de vignettes par page'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('order_by')) : ?> checked="checked"<?php endif; ?> id="order_by" name="order_by" type="checkbox" />
						<span><label for="order_by"><?php echo __('Ordre des vignettes'); ?></label></span>
					</p>
				</fieldset>
				<br />
				<fieldset>
					<legend><?php echo __('Informations sous les vignettes'); ?></legend>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('thumbs_category_title')) : ?> checked="checked"<?php endif; ?> id="thumbs_category_title" name="thumbs_category_title" type="checkbox" />
						<span><label for="thumbs_category_title"><?php echo __('Titre des catégories'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('thumbs_images')) : ?> checked="checked"<?php endif; ?> id="thumbs_images" name="thumbs_images" type="checkbox" />
						<span><label for="thumbs_images"><?php echo __('Nombre d\'images'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('thumbs_albums')) : ?> checked="checked"<?php endif; ?> id="thumbs_albums" name="thumbs_albums" type="checkbox" />
						<span><label for="thumbs_albums"><?php echo __('Nombre d\'albums'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('thumbs_image_title')) : ?> checked="checked"<?php endif; ?> id="thumbs_image_title" name="thumbs_image_title" type="checkbox" />
						<span><label for="thumbs_image_title"><?php echo __('Titre des images'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('thumbs_date')) : ?> checked="checked"<?php endif; ?> id="thumbs_date" name="thumbs_date" type="checkbox" />
						<span><label for="thumbs_date"><?php echo __('Date d\'ajout'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('thumbs_size')) : ?> checked="checked"<?php endif; ?> id="thumbs_size" name="thumbs_size" type="checkbox" />
						<span><label for="thumbs_size"><?php echo __('Taille'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('thumbs_filesize')) : ?> checked="checked"<?php endif; ?> id="thumbs_filesize" name="thumbs_filesize" type="checkbox" />
						<span><label for="thumbs_filesize"><?php echo __('Poids'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('thumbs_hits')) : ?> checked="checked"<?php endif; ?> id="thumbs_hits" name="thumbs_hits" type="checkbox" />
						<span><label for="thumbs_hits"><?php echo __('Nombre de visites'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('thumbs_comments')) : ?> checked="checked"<?php endif; ?> id="thumbs_comments" name="thumbs_comments" type="checkbox" />
						<span><label for="thumbs_comments"><?php echo __('Nombre de commentaires'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetOptions('thumbs_votes')) : ?> checked="checked"<?php endif; ?> id="thumbs_votes" name="thumbs_votes" type="checkbox" />
						<span><label for="thumbs_votes"><?php echo __('Note moyenne'); ?></label></span>
					</p>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>
