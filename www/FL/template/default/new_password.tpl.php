
		<div class="box box_notools">
			<table>
				<tr><td class="box_title"><div><h2><?php echo __('Nouveau mot de passe'); ?></h2></div></td></tr>
<?php if ($tpl->disReport('success')) : ?>
				<tr>
					<td class="box_success">
						<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

						<p id="new_password"><?php echo $tpl->getNewPassword(); ?></p>
					</td>
				</tr>
<?php else : ?>
				<tr>
					<td class="box_edit">
						<form action="" method="post">
							<div>
<?php if ($tpl->disReport('warning')) : ?>
								<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php endif; ?>
								<p class="field">
									<?php echo __('Pour obtenir un nouveau mot de passe, veuillez entrer les informations suivantes.'); ?>
								</p>
								<br />
								<p class="field field_ftw">
									<label for="login"><?php echo __('Nom d\'utilisateur :'); ?></label>
									<input maxlength="255" id="login" name="login" type="text" class="focus text" value="" />
								</p>
								<p class="field field_ftw">
									<label for="email"><?php echo __('Courriel :'); ?></label>
									<input maxlength="255" id="email" name="email" type="text" class="text" value="" />
								</p>
								<p class="field field_ftw">
									<label for="code"><?php echo __('Code :'); ?></label>
									<input maxlength="1024" id="code" name="code" type="password" class="text" />
								</p>
								<input type="submit" class="submit" value="<?php echo __('Valider'); ?>" />
							</div>
						</form>
					</td>
				</tr>
<?php endif; ?>
			</table>
		</div>
