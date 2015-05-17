
<?php include_once(dirname(__FILE__) . '/widgets_submenu.tpl.php'); ?>

		<p id="position"><a href="<?php echo $tpl->getLink('widgets'); ?>"><?php echo __('Widgets'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('widget/stats-images'); ?>"><?php echo $tpl->getWidgetStatsImages('title_default'); ?></a></span></p>

		<form class="obj_w_form" id="widget_stats_images" action="" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<fieldset>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre (laissez vide pour utiliser le titre par défaut) :'); ?></label>
						<input value="<?php echo $tpl->getWidgetStatsImages('title'); ?>" id="title_<?php echo $tpl->getLang('code'); ?>" name="title[<?php echo $tpl->getLang('code'); ?>]" type="text" class="text onload_focus" maxlength="128" size="40" />
					</p>
<?php endwhile; ?>
					<br />
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsImages('filesize')) : ?> checked="checked"<?php endif; ?> id="filesize" name="filesize" type="checkbox" />
						<span><label for="filesize"><?php echo __('Poids'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsImages('size')) : ?> checked="checked"<?php endif; ?> id="size" name="size" type="checkbox" />
						<span><label for="size"><?php echo __('Dimensions'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsImages('hits')) : ?> checked="checked"<?php endif; ?> id="hits" name="hits" type="checkbox" />
						<span><label for="hits"><?php echo __('Nombre de visites'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsImages('favorites')) : ?> checked="checked"<?php endif; ?> id="favorites" name="favorites" type="checkbox" />
						<span><label for="favorites"><?php echo __('Nombre de favoris'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsImages('comments')) : ?> checked="checked"<?php endif; ?> id="comments" name="comments" type="checkbox" />
						<span><label for="comments"><?php echo __('Nombre de commentaires'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsImages('votes')) : ?> checked="checked"<?php endif; ?> id="votes" name="votes" type="checkbox" />
						<span><label for="votes"><?php echo __('Nombre de votes'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsImages('added_date')) : ?> checked="checked"<?php endif; ?> id="added_date" name="added_date" type="checkbox" />
						<span><label for="added_date"><?php echo __('Date d\'ajout'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsImages('added_by')) : ?> checked="checked"<?php endif; ?> id="added_by" name="added_by" type="checkbox" />
						<span><label for="added_by"><?php echo __('Nom de l\'utilisateur qui a ajouté l\'image'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if ($tpl->disWidgetStatsImages('created_date')) : ?> checked="checked"<?php endif; ?> id="created_date" name="created_date" type="checkbox" />
						<span><label for="created_date"><?php echo __('Date de création'); ?></label></span>
					</p>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>
