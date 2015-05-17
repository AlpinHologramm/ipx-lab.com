
<?php include_once(dirname(__FILE__) . '/widgets_submenu.tpl.php'); ?>

		<p id="position">
			<a href="<?php echo $tpl->getLink('widgets'); ?>"><?php echo __('Widgets'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('widget/online-users'); ?>"><?php echo $tpl->getWidgetOnlineUsers('title_default'); ?></a></span>
<?php if ($tpl->disHelp()) : ?>
			<a rel="h_widget_online_users" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
		</p>

		<form class="obj_w_form" id="widget_online_users" action="" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<fieldset id="fieldset_title">
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre (laissez vide pour utiliser le titre par défaut) :'); ?></label>
						<input value="<?php echo $tpl->getWidgetOnlineUsers('title'); ?>" id="title_<?php echo $tpl->getLang('code'); ?>" name="title[<?php echo $tpl->getLang('code'); ?>]" type="text" class="text onload_focus" maxlength="128" size="40" />
					</p>
<?php endwhile; ?>
					<br />
					<p class="field">
						<label for="duration"><?php echo wordwrap(__('Durée pendant laquelle un utilisateur est considéré comme en ligne depuis sa dernière visite :'), 70, '<br />'); ?></label>
						<input value="<?php echo $tpl->getWidgetOnlineUsers('duration'); ?>" id="duration" name="duration" size="4" maxlength="4" type="text" class="text" />
						<?php echo __('secondes'); ?>
					</p>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>
