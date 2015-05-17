
<?php include_once(dirname(__FILE__) . '/options_submenu.tpl.php'); ?>

		<div id="tools_browse">
			<div class="browse">
				<select id="functions_list" onchange="window.location.href='#'+this.options[this.selectedIndex].value">
					<option value=""><?php echo __('Parcourir :'); ?></option>
					<option value="database"><?php echo __('Base de données'); ?></option>
					<option value="debug"><?php echo __('Débogage'); ?></option>
					<option value="errors"><?php echo __('Erreurs'); ?></option>
					<option value="security"><?php echo __('Sécurité'); ?></option>
				</select>
			</div>
		</div>

		<br />

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<p class="report_msg report_exclamation"><?php echo __('Attention ! Ne modifiez ces options que si vous savez exactement à quoi elles correspondent.'); ?></p>
		<br />
		<form action="#top" method="post">
			<div>
				<div class="browse_anchor" id="database"></div>
				<fieldset>
					<legend><?php echo __('Base de données'); ?></legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('db_close_template')) : ?> checked="checked"<?php endif; ?> id="db_close_template" name="db_close_template" type="checkbox" />
							<span><label for="db_close_template"><?php echo __('Fermer la connexion à la base de données avant le chargement du template'); ?></label></span>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="debug"></div>
				<fieldset>
					<legend><?php echo __('Débogage'); ?></legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('debug')) : ?> checked="checked"<?php endif; ?> id="option_debug_mode" name="debug_mode" type="checkbox" />
							<span><label for="option_debug_mode"><?php echo __('Activer le mode débogage'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('exec_time')) : ?> checked="checked"<?php endif; ?> id="option_exec_time" name="exec_time" type="checkbox" />
							<span><label for="option_exec_time"><?php echo __('Afficher le temps d\'exécution'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('debug_sql')) : ?> checked="checked"<?php endif; ?> id="option_debug_sql" name="debug_sql" type="checkbox" />
							<span><label for="option_debug_sql"><?php echo __('Afficher les requêtes SQL'); ?></label></span>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="errors"></div>
				<fieldset>
					<legend><?php echo __('Erreurs'); ?></legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('errors_display')) : ?> checked="checked"<?php endif; ?> id="option_display_errors" name="display_errors" type="checkbox" />
							<span><label for="option_display_errors"><?php echo __('Afficher les erreurs (PHP et SQL)'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('errors_display_now')) : ?> checked="checked"<?php endif; ?> id="option_display_errors_now" name="display_errors_now" type="checkbox" />
							<span><label for="option_display_errors_now"><?php echo __('Afficher les erreurs au moment où elles se produisent'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('errors_display_trace')) : ?> checked="checked"<?php endif; ?> id="option_display_errors_trace" name="display_errors_trace" type="checkbox" />
							<span><label for="option_display_errors_trace"><?php echo __('Afficher les détails des erreurs (debug_backtrace)'); ?></label></span>
						</p>
						<div class="field_second">
							<p class="field checkbox">
								<input<?php if ($tpl->disOption('errors_trace_args')) : ?> checked="checked"<?php endif; ?> id="option_display_errors_args" name="display_errors_args" type="checkbox" />
								<span><label for="option_display_errors_args"><?php echo __('Afficher et enregistrer la valeur des arguments des fonctions'); ?></label></span>
							</p>
						</div>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('errors_log')) : ?> checked="checked"<?php endif; ?> id="option_logs_errors" name="logs_errors" type="checkbox" />
							<span><label for="option_logs_errors"><?php echo __('Enregistrer les erreurs'); ?></label></span>
						</p>
						<div class="field_second">
							<p class="field">
								<label for="option_logs_errors_max"><?php echo __('Nombre maximum d\'erreurs enregistrées :'); ?></label>
								<input value="<?php echo $tpl->getOption('errors_log_max'); ?>" id="option_logs_errors_max" name="logs_errors_max" maxlength="1024" size="5" type="text" class="text" />
							</p>
							<p class="field checkbox">
								<input<?php if ($tpl->disOption('admin_dashboard_errors')) : ?> checked="checked"<?php endif; ?> id="option_dashboard_errors" name="admin_dashboard_errors" type="checkbox" />
								<span><label for="option_dashboard_errors"><?php echo __('Notifier les erreurs sur le tableau de bord'); ?></label></span>
							</p>
						</div>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('errors_mail')) : ?> checked="checked"<?php endif; ?> id="option_notify_errors" name="notify_errors" type="checkbox" />
							<span><label for="option_notify_errors"><?php echo __('Notifier les erreurs par courriel au super-administrateur'); ?></label></span>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="security"></div>
				<fieldset>
					<legend><?php echo __('Sécurité'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<label for="users_password_minlength"><?php echo __('Longueur minimum des mots de passe utilisateurs :'); ?></label>
							<input value="<?php echo $tpl->getOption('users_password_minlength'); ?>" id="users_password_minlength" name="users_password_minlength" maxlength="2" size="2" type="text" class="text" />
						</p>
						<p class="field">
							<label for="passwords_usleep"><?php echo __('Durée de temporisation lors des connexions qui ont échouées :'); ?></label>
							<input value="<?php echo $tpl->getOption('passwords_usleep'); ?>" id="passwords_usleep" name="passwords_usleep" maxlength="10" size="10" type="text" class="text" />
							<?php echo __('microsecondes'); ?>
						</p>
						<p class="field">
							<label for="sessions_expire"><?php echo __('Durée de validité des sessions :'); ?></label>
							<input value="<?php echo $tpl->getOption('sessions_expire'); ?>" id="sessions_expire" name="sessions_expire" maxlength="10" size="10" type="text" class="text" />
							<?php echo __('secondes'); ?>
						</p>
						<p class="field">
							<label for="anticsrf_token_expire"><?php echo __('Durée de validité des jetons anti-CSRF :'); ?></label>
							<input value="<?php echo $tpl->getOption('anticsrf_token_expire'); ?>" id="anticsrf_token_expire" name="anticsrf_token_expire" maxlength="10" size="10" type="text" class="text" />
							<?php echo __('secondes'); ?>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('anticsrf_token_unique')) : ?> checked="checked"<?php endif; ?> id="anticsrf_token_unique" name="anticsrf_token_unique" type="checkbox" />
							<span><label for="anticsrf_token_unique"><?php echo __('Utiliser chaque jeton anti-CSRF qu\'une seule fois'); ?></label></span>
						</p>
					</div>
				</fieldset>
<?php if ($tpl->disAdmin('password_protect')) : ?>
				<br />
				<fieldset>
					<p id="current_pwd_required" class="field field_ftw">
						<label for="current_pwd"><strong><?php echo __('Votre mot de passe :'); ?></strong></label>
						<input maxlength="512" id="current_pwd" name="current_pwd" type="password" class="text" />
					</p>
				</fieldset>
<?php endif; ?>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>