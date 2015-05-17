		<div class="box box_notools">
			<table>
				<tr><td class="box_title"><div><h2><?php echo __('Validation de compte'); ?></h2></div></td></tr>
<?php if ($tpl->disReport('success')) : ?>
				<tr><td class="box_success"><?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?></td></tr>
<?php else : ?>
				<tr>
					<td class="box_edit">
						<form action="" method="post">
							<div>
<?php if ($tpl->disReport('warning')) : ?>
								<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php endif; ?>
								<p>
									<?php echo __('Veuillez valider votre compte en renseignant les champs suivants par vos informations de connexion et le code qui vous a été fourni par courriel :'); ?>
								</p>
								<p class="field field_ftw">
									<label for="login"><?php echo __('Nom d\'utilisateur :'); ?></label>
									<input maxlength="255" id="login" name="login" type="text" class="focus text" value="" />
								</p>
								<p class="field field_ftw">
									<label for="password"><?php echo __('Mot de passe :'); ?></label>
									<input maxlength="1024" id="password" name="password" type="password" class="text" />
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
