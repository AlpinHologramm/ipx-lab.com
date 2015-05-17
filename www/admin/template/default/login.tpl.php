					<div id="connexion_login">
						<p class="field">
							<label for="login"><?php echo __('Nom d\'utilisateur :'); ?></label>
							<input<?php echo (isset($_POST['login'])) ? ' value="' . $_POST['login'] . '"' : ''; ?> class="text" id="login" name="login" type="text" maxlength="128" />
						</p>
						<p class="field">
							<label for="password"><?php echo __('Mot de passe :'); ?></label>
							<input class="text" id="password" name="password" type="password" maxlength="1024" />
						</p>
						<p id="submit"><input type="submit" value="<?php echo __('Valider'); ?>" /></p>
					</div>
					<p id="options_link">
						<a href="javascript:;"><?php echo mb_strtolower(__('Options')); ?></a>
					</p>
					<div id="options">
						<p class="field checkbox">
							<input id="remember" name="remember" type="checkbox" />
							<span><label for="remember"><?php echo __('Se souvenir de moi ?'); ?></label></span>
						</p>
						<p id="forgot" class="field">
							<a href="<?php echo $tpl->getLink('forgot'); ?>"><?php echo __('J\'ai oublié mon mot de passe !'); ?></a>
						</p>
					</div>
					<script type="text/javascript">document.getElementById('options').style.display = 'none';</script>
					<p id="link">
						<a href="<?php echo $tpl->getConnexion('gallery_path'); ?>/"><?php echo mb_strtolower(__('Galerie')); ?></a>
					</p>
