		<h2><a href="<?php echo $tpl->getLink('themes'); ?>"><?php echo __('Thèmes'); ?></a></h2>

		<div id="sub_menu_line"></div>

		<form action="" id="themes" method="post">

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

			<div id="theme_css">
				<span class="icon icon_style show_tool"><a rel="theme_css_textarea" class="js" href="javascript:;"><?php echo __('Style additionnel'); ?></a></span>
<?php if ($tpl->disHelp()) : ?>
				<span><a rel="h_theme_css" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a></span>
<?php endif; ?>
				<div id="theme_css_textarea">
					<textarea class="focus resizable" rows="15" cols="40" name="theme_css"><?php echo $tpl->getCSS(); ?></textarea>
				</div>
			</div>

<?php while ($tpl->nextTheme()) : ?>
			<div id="<?php echo $tpl->getTheme('name'); ?>" class="theme<?php if ($tpl->disTheme('current')) : ?> selected<?php endif; ?>">
				<div class="theme_inner">
					<div class="theme_select">
						<input value="<?php echo $tpl->getTheme('name'); ?>"<?php if ($tpl->disTheme('current')) : ?> checked="checked"<?php endif; ?> name="theme" type="radio" />
					</div>
					<div class="theme_screenshot">
						<img src="<?php echo $tpl->getTheme('screenshot'); ?>" alt="<?php echo __('Aperçu'); ?>" width="160" height="160" />
					</div>
<?php if ($tpl->disTheme('current')) : ?>
					<p class="current"><?php echo __('Actuel'); ?></p>
<?php endif; ?>
					<div class="theme_infos">
						<p class="theme_name"><span><?php echo $tpl->getTheme('name'); ?></span></p>
						<p class="theme_style field">
							<label><?php echo __('Style :'); ?></label>
							<select name="style[<?php echo $tpl->getTheme('name'); ?>]">
								<?php echo $tpl->getTheme('styles'); ?>

							</select>
							,
<?php while ($tpl->nextStyle()) : ?>
							<span<?php if (!$tpl->disStyle('current')) : ?> style="display:none"<?php endif; ?> id="author_<?php echo $tpl->getTheme('name'); ?>_<?php echo $tpl->getStyle('name'); ?>" class="theme_author"><?php printf(__('par %s'), $tpl->getStyle('author')); ?></span>
<?php endwhile; ?>
						</p>
<?php while ($tpl->nextStyle()) : ?>
						<p<?php if (!$tpl->disStyle('current')) : ?> style="display:none"<?php endif; ?> id="desc_<?php echo $tpl->getTheme('name'); ?>_<?php echo $tpl->getStyle('name'); ?>" class="theme_desc"><?php echo $tpl->getStyle('description'); ?></p>
<?php endwhile; ?>
					</div>
				</div>
			</div>
<?php endwhile; ?>

			<div id="submit">
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>
