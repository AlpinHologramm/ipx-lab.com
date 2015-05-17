
<?php include_once(dirname(__FILE__) . '/widgets_submenu.tpl.php'); ?>

		<p id="position"><a href="<?php echo $tpl->getLink('widgets'); ?>"><?php echo __('Widgets'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('widget/stats-categories'); ?>"><?php echo $tpl->getWidgetStatsCategories('title_default'); ?></a></span></p>

		<form class="obj_w_form" id="widget_stats_categories" action="" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<fieldset>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre (laissez vide pour utiliser le titre par défaut) :'); ?></label>
						<input value="<?php echo $tpl->getWidgetStatsCategories('title'); ?>" id="title_<?php echo $tpl->getLang('code'); ?>" name="title[<?php echo $tpl->getLang('code'); ?>]" type="text" class="text onload_focus" maxlength="128" size="40" />
					</p>
<?php endwhile; ?>
					<br />
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsCategories('images')) : ?> checked="checked"<?php endif; ?> id="images" name="images" type="checkbox" />
						<span><label for="images"><?php echo __('Nombre d\'images'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsCategories('albums')) : ?> checked="checked"<?php endif; ?> id="albums" name="albums" type="checkbox" />
						<span><label for="albums"><?php echo __('Nombre d\'albums'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsCategories('filesize')) : ?> checked="checked"<?php endif; ?> id="filesize" name="filesize" type="checkbox" />
						<span><label for="filesize"><?php echo __('Poids des images'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsCategories('recents')) : ?> checked="checked"<?php endif; ?> id="recents" name="recents" type="checkbox" />
						<span><label for="recents"><?php echo __('Nombre d\'images récentes'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsCategories('hits')) : ?> checked="checked"<?php endif; ?> id="hits" name="hits" type="checkbox" />
						<span><label for="hits"><?php echo __('Nombre de visites'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsCategories('comments')) : ?> checked="checked"<?php endif; ?> id="comments" name="comments" type="checkbox" />
						<span><label for="comments"><?php echo __('Nombre de commentaires'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsCategories('votes')) : ?> checked="checked"<?php endif; ?> id="votes" name="votes" type="checkbox" />
						<span><label for="votes"><?php echo __('Nombre de votes'); ?></label></span>
					</p>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>
