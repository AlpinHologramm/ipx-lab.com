
<?php include_once(dirname(__FILE__) . '/pages_submenu.tpl.php'); ?>

		<p id="position"><a href="<?php echo $tpl->getLink('pages'); ?>"><?php echo __('Pages'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('page/guestbook'); ?>"><?php echo $tpl->getPageGuestbook('title_default'); ?></a></span></p>

		<form class="obj_w_form" id="page_guestobook" action="" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<fieldset>
					<p class="field">
						<label for="nb_per_page"><?php echo __('Nombre de commentaires par page :'); ?></label>
						<input value="<?php echo $tpl->getPageGuestbook('nb_per_page'); ?>" maxlength="3" id="nb_per_page" name="nb_per_page" class="text" size="3" type="text" />
					</p>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw field_html">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="message_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Message :'); ?></label>
						<span class="field_html_tag">
							<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
						</span>
						<span class="field_html_textarea">
							<textarea class="resizable" rows="8" cols="50" id="message_<?php echo $tpl->getLang('code'); ?>" name="message[<?php echo $tpl->getLang('code'); ?>]"><?php echo $tpl->getPageGuestbook('message'); ?></textarea>
						</span>
					</p>
<?php endwhile; ?>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>
