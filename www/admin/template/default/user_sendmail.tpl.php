
<?php include_once(dirname(__FILE__) . '/users_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/user_related.tpl.php'); ?>

<?php if (!$tpl->disReport('success')) : ?>
<?php if ($tpl->disUser('sendmail')) : ?>
<?php if ($tpl->disFieldError()) : ?>
		<script type="text/javascript">var field_error = '<?php echo $tpl->getFieldError(); ?>';</script>
<?php endif; ?>
		<form class="form_page" action="" method="post">
			<div>
				<fieldset>
					<legend><?php echo __('Nouveau message'); ?></legend>
					<div class="fielditems">
						<p class="field field_ftw">
							<label for="name"><?php echo __('Nom d\'utilisateur :'); ?></label>
							<input value="<?php echo $tpl->getInfo('login'); ?>" id="name" name="name" class="text" type="text" maxlength="255" />
						</p>
						<p class="field field_ftw">
							<label for="email"><?php echo __('Adresse de l\'expéditeur :'); ?></label>
							<input value="<?php echo $tpl->getInfo('email'); ?>" id="email" name="email" class="text" type="text" maxlength="255" />
						</p>
						<p class="field field_ftw">
							<label for="subject"><?php echo __('Sujet du message :'); ?></label>
							<input value="<?php echo $tpl->getInfo('subject'); ?>" id="subject" name="subject" class="text" type="text" maxlength="255" />
						</p>
						<p class="field field_ftw">
							<label for="message"><?php echo __('Message :'); ?></label>
							<textarea id="message" name="message" cols="50" rows="10"><?php echo $tpl->getInfo('message'); ?></textarea>
						</p>
					</div>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Envoyer'); ?>" />
			</div>
		</form>
<?php else : ?>
		<p class="report_msg report_info"><?php echo __('Cet utilisateur ne possède pas d\'adresse de courriel.'); ?></p>
<?php endif; ?>
<?php else : ?>
		<p><a href=""><?php echo __('Écrire un nouveau message'); ?></a></p>
<?php endif; ?>
