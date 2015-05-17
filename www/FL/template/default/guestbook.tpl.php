<h2>
	<?php echo __('Livre d\'or'); ?>
<?php if ($tpl->disComment()) : ?>
	<?php echo '<span id="guestbook_nb_comments">(' . sprintf(($tpl->getComments('nb_comments') > 1) ? __('%s commentaires') : __('%s commentaire'), $tpl->getComments('nb_comments')) . ')</span>'; ?>
<?php endif; ?>
</h2>
<?php if ($tpl->disGuestbook('message')) : ?>
<p id="guestbook_message"><?php echo $tpl->getGuestbook('message'); ?></p>
<?php endif; ?>
<div id="guestbook_comments">
	<div id="guestbook_comments_inner">
<?php if ($tpl->disNavigation('top')) : ?>
		<div class="nav" id="nav_top">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getComments('nb_pages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>
		
		</div>
<?php endif; ?>
<?php if (!$tpl->disComment()) : ?>
		<p class="report message message_info"><?php echo __('Aucun commentaire.'); ?></p>
<?php endif; ?>
<?php while ($tpl->nextComment()) : ?>
		<div id="co<?php echo $tpl->getComment('id'); ?>" class="comment">
			<div class="comment_top">
<?php if ($tpl->disGallery('users') && $tpl->disGallery('avatars')) : ?>
				<p class="comment_avatar">
					<a<?php if (!$tpl->disComment('guest')) : ?> title="<?php printf(__('Profil de %s'), $tpl->getComment('author')); ?>" href="<?php echo $tpl->getLink('user/' . $tpl->getComment('user_id')); ?>"<?php endif; ?>>
						<img alt="<?php printf(__('Avatar de %s'), $tpl->getComment('author')); ?>" width="50" height="50" src="<?php echo $tpl->getComment('avatar'); ?>" />
					</a>
				</p>
<?php endif; ?>
				<p class="comment_author">
<?php if (!$tpl->disGallery('users') || $tpl->disComment('guest')) : ?>
				<?php echo '<span>' . $tpl->getComment('author_and_website') . '</span>'; ?>

<?php else : ?>
				<?php echo '<span><a title="' . sprintf(__('Profil de %s'), $tpl->getComment('author')) . '" href="' . $tpl->getLink('user/' . $tpl->getComment('user_id')) . '">' . $tpl->getComment('author') . '</a></span>'; ?>

<?php endif; ?>
				</p>
				<p class="comment_date"><?php echo ucfirst(sprintf(__('%s à %s'), $tpl->getComment('date'), $tpl->getComment('time'))); ?></p>
			</div>
			<div class="comment_bottom">
<?php if ($tpl->disComment('rate')) : ?>
				<p class="guestbook_rate">
					<span title="<?php echo $tpl->getComment('rate'); ?>">
						<?php echo $tpl->getComment('rate_visual'); ?>

					</span>
				</p>
<?php endif; ?>
				<p class="comment_message">
					<?php echo $tpl->getComment('message'); ?>

				</p>
			</div>
		</div>
<?php if (isset($_POST['preview']) && $tpl->getComment('id') == -1) : ?>				
		<p id="comment_preview"><span><?php echo __('Aperçu de votre commentaire.'); ?></span></p>
<?php endif; ?>
<?php endwhile; ?>
<?php if ($tpl->disNavigation('bottom')) : ?>
		<div class="nav" id="nav_bottom">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getComments('nb_pages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>
			
		</div>
<?php endif; ?>
	</div>
</div>
<?php if ($tpl->disAddComment()) : ?>
<div id="guestbook_add_comment">
	<div id="guestbook_add_comment_inner">
		<form action="<?php echo $tpl->getAddComment('form_action'); ?>" method="post" id="add_comment">
			<h3><?php echo __('Signer le livre d\'or'); ?></h3>
			<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<p style="display:none" class="field field_ftw">
					<label for="f_email">Email :</label>
					<input value="" maxlength="255" class="text" id="f_email" name="f_email" type="text" />
				</p>
<?php if (!$tpl->disAuthUser()) : ?>
				<p class="field field_ftw">
					<span class="required">*</span>
					<label for="author"><?php echo __('Auteur :'); ?></label>
					<input value="<?php echo $tpl->getAddComment('author'); ?>" maxlength="255" class="text" id="author" name="author" type="text" />
				</p>
				<p class="field field_ftw">
					<?php if ($tpl->disAddComment('required_email')) : ?><span class="required">*</span><?php endif; ?>
					<label for="email"><?php echo __('Courriel (ne sera pas publié) :'); ?></label>
					<input value="<?php echo $tpl->getAddComment('email'); ?>" maxlength="255" class="text" id="email" name="email" type="text" />
				</p>
				<p class="field field_ftw">
					<?php if ($tpl->disAddComment('required_website')) : ?><span class="required">*</span><?php endif; ?>
					<label for="website"><?php echo __('Site Web :'); ?></label>
					<input value="<?php echo $tpl->getAddComment('website') == '' ? 'http://' : $tpl->getAddComment('website'); ?>" maxlength="255" class="text" id="website" name="website" type="text" />
				</p>
				<p class="field checkbox">
					<input<?php echo $tpl->getAddCommentRemember(); ?> id="remember" name="remember" type="checkbox" />
					<span><label for="remember"><?php echo __('Se souvenir de moi ?'); ?></label></span>
				</p>
<?php endif; ?>
				<p class="field">
					<label for="rate"><?php echo __('Note :'); ?></label>
					<select id="rate" name="rate">
						<?php echo $tpl->getAddComment('rate'); ?>

					</select>
				</p>
				<p class="field">
<?php if (!$tpl->disAuthUser()) : ?>
					<span class="required">*</span>
<?php endif; ?>
					<label for="message"><?php echo __('Message :'); ?></label>
<?php if ($tpl->disAddComment('smilies')) : ?>
					<span id="smilies"><?php echo $tpl->getAddComment('smilies'); ?></span>
<?php endif; ?>
					<textarea<?php if ($tpl->disReport('error') || $tpl->disReport('warning')) : ?> class="focus"<?php endif; ?> id="message" name="message" rows="6" cols="40"><?php echo $tpl->getAddComment('message'); ?></textarea>
				</p>
<?php if ($tpl->disAddComment('captcha')) : ?>
				<p class="field">
					<span class="required">*</span>
					<label><?php echo __('Prouvez que vous êtes un humain :'); ?></label>
					<?php echo $tpl->getAddComment('captcha'); ?>

				</p>
<?php endif; ?>
				<br />
<?php if (!$tpl->disAuthUser()) : ?>
				<p class="message message_info"><?php echo __('Les champs marqués d\'un astérisque sont obligatoires.'); ?></p>
<?php endif; ?>
<?php if ($tpl->disGallery('comments_moderate')) : ?>
				<p class="message message_info"><?php echo __('Les commentaires sont modérés.'); ?></p>
<?php endif; ?>
				<p>
					<input class="submit" type="submit" value="<?php echo __('Prévisualiser'); ?>" name="preview" />
					<input class="submit<?php if (!empty($_POST['preview'])) : ?> focus<?php endif; ?>" type="submit" value="<?php echo __('Envoyer'); ?>" />
				</p>
			</div>
		</form>
	</div>
</div>
<?php endif; ?>