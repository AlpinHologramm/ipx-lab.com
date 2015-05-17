
<?php include_once(dirname(__FILE__) . '/users_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php if (!$tpl->disReport('success')) : ?>
<?php if ($tpl->disFieldError()) : ?>
		<script type="text/javascript">var field_error = '<?php echo $tpl->getFieldError(); ?>';</script>
<?php endif; ?>
		<form action="" method="post">
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
						<p class="field field_ftw">
							<?php echo __('Destinataires :'); ?>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getInfo('users_all'); ?> id="users_all" name="users_all" type="checkbox" />
							<label for="users_all"><?php echo __('Tous les utilisateurs'); ?></label>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getInfo('users_activate'); ?> id="users_activate" name="users_activate" type="checkbox" />
							<label for="users_activate"><?php echo __('Tous les utilisateurs activés'); ?></label>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getInfo('users_deactivate'); ?> id="users_deactivate" name="users_deactivate" type="checkbox" />
							<label for="users_deactivate"><?php echo __('Tous les utilisateurs suspendus'); ?></label>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getInfo('users_pending'); ?> id="users_pending" name="users_pending" type="checkbox" />
							<label for="users_pending"><?php echo __('Tous les utilisateurs en attente de validation'); ?></label>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getInfo('users_select'); ?> id="users_select" name="users_select" type="checkbox" />
							<label for="users_select"><?php echo __('Les utilisateurs suivants :'); ?></label>
						</p>
						<div class="field_second">
							<p class="field">
								<select name="users_list[]" class="multiple" multiple="multiple">
									<?php echo $tpl->getUsers(); ?>

								</select>
							</p>
						</div>
						<p class="field checkbox">
							<input<?php echo $tpl->getInfo('groups_select'); ?> id="groups_select" name="groups_select" type="checkbox" />
							<label for="groups_select"><?php echo __('Les utilisateurs des groupes suivants :'); ?></label>
						</p>
						<div class="field_second">
							<p class="field">
								<select name="groups_list[]" class="multiple" multiple="multiple">
									<?php echo $tpl->getGroups(); ?>

								</select>
							</p>
						</div>
					</div>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Envoyer'); ?>" />
			</div>
		</form>
<?php else : ?>
		<p><a href=""><?php echo __('Écrire un nouveau message'); ?></a></p>
<?php endif; ?>
