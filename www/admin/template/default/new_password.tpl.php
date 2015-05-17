<?php if ($tpl->disReport('success')) : ?>
					<p id="new_password" class="field"><?php echo $tpl->getNewPassword(); ?></p>
<?php else : ?>
					<p class="field">
						<?php echo __('Pour obtenir un nouveau mot de passe, veuillez entrer les informations suivantes.'); ?>
					</p>
					<br />
					<p class="field">
						<label for="login"><?php echo __('Nom d\'utilisateur :'); ?></label>
						<input class="text" id="login" name="login" type="text" maxlength="128" />
					</p>
					<p class="field">
						<label for="email"><?php echo __('Courriel :'); ?></label>
						<input class="text" id="email" name="email" type="text" maxlength="255" />
					</p>
					<p class="field">
						<label for="code"><?php echo __('Code :'); ?></label>
						<input maxlength="1024" id="code" name="code" type="password" class="text" />
					</p>
					<p id="submit"><input type="submit" value="<?php echo __('Valider'); ?>" /></p>
<?php endif; ?>
					<br />
					<p id="link"><a href="<?php echo $tpl->getLink(); ?>"><?php echo mb_strtolower(__('retour')); ?></a></p>
