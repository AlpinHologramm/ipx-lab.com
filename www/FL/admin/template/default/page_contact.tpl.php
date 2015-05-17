
<?php include_once(dirname(__FILE__) . '/pages_submenu.tpl.php'); ?>

		<p id="position"><a href="<?php echo $tpl->getLink('pages'); ?>"><?php echo __('Pages'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('page/contact'); ?>"><?php echo $tpl->getPageContact('title_default'); ?></a></span></p>

		<form class="obj_w_form" id="page_contact" action="" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<fieldset>
					<p class="field field_ftw">
						<label for="email"><?php echo __('Courriel :'); ?></label>
						<input value="<?php echo $tpl->getPageContact('email'); ?>" id="email" name="email" type="text" class="text onload_focus" maxlength="128" size="50" />
					</p>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw field_html">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="message_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Message :'); ?></label>
						<span class="field_html_tag">
							<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
						</span>
						<span class="field_html_textarea">
							<textarea class="resizable" rows="8" cols="50" id="message_<?php echo $tpl->getLang('code'); ?>" name="message[<?php echo $tpl->getLang('code'); ?>]"><?php echo $tpl->getPageContact('message'); ?></textarea>
						</span>
					</p>
<?php endwhile; ?>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>
