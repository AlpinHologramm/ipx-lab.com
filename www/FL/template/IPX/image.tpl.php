
<?php if ($tpl->disPositionSpecial()) : ?>
		<p id="position_special"><?php echo $tpl->getPositionSpecial(); ?></p>
<?php endif; ?>

<?php include(dirname(__FILE__) . '/position.tpl.php'); ?>

<?php if ($tpl->disNavigation('top')) : ?>
		<div class="nav" id="nav_top">

			<div class="nav_left"></div>
			<div class="nav_right"><?php printf('%s|%s', $tpl->getAlbum('current_image'), $tpl->getAlbum('nb_images')); ?></div>

<?php if ($tpl->disNavigation('prev_inactive')) : ?>
			<span class="first inactive"><?php echo $tpl->getNavigation('first'); ?></span>
			<span class="prev inactive"><?php echo $tpl->getNavigation('prev'); ?></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('prev_active')) : ?>
			<span class="first"><a title="<?php echo __('Première image'); ?>" href="<?php echo $tpl->getNavigation('first_link'); ?>"><?php echo $tpl->getNavigation('first'); ?></a></span>
			<span class="prev"><a title="<?php echo __('Image précédente'); ?>" href="<?php echo $tpl->getNavigation('prev_link'); ?>"><?php echo $tpl->getNavigation('prev'); ?></a></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('next_active')) : ?>
			<span class="next"><a title="<?php echo __('Image suivante'); ?>" href="<?php echo $tpl->getNavigation('next_link'); ?>"><?php echo $tpl->getNavigation('next'); ?></a></span>
			<span class="last"><a title="<?php echo __('Dernière image'); ?>" href="<?php echo $tpl->getNavigation('last_link'); ?>"><?php echo $tpl->getNavigation('last'); ?></a></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('next_inactive')) : ?>
			<span class="next inactive"><?php echo $tpl->getNavigation('next'); ?></span>
			<span class="last inactive"><?php echo $tpl->getNavigation('last'); ?></span>
<?php endif; ?>

		</div>
<?php endif; ?>


		<div id="image_container">
<?php if ($tpl->disImage('resize')) : ?>
			<span style="width:<?php echo $tpl->getImage('width'); ?>px;" id="image_resize_msg">
				<?php echo __('Cliquez sur l\'image pour l\'afficher en taille réelle.'); ?>

			</span>
<?php endif; ?>
			<div id="image">
<?php if ($tpl->disImage('resize')) : ?>
				<a href="<?php echo $tpl->getImage('link'); ?>">
<?php endif; ?>
<?php if ($tpl->disGallery('images_anti_copy')) : ?>
					<span style="position:absolute;width:<?php echo $tpl->getImage('width'); ?>px;height:<?php echo $tpl->getImage('height'); ?>px;background:url('data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');"></span>
<?php endif; ?>
					<img width="<?php echo $tpl->getImage('width'); ?>"
						height="<?php echo $tpl->getImage('height'); ?>"
						alt="<?php echo $tpl->getImage('title'); ?>"
						src="<?php echo $tpl->getImage('src'); ?>" />
<?php if ($tpl->disImage('resize')) : ?>
				</a>
<?php endif; ?>
			</div>
		</div>

<?php if ($tpl->disImage('desc') || $tpl->disWidgets('stats_images')) : ?>
		<div<?php if ($tpl->disVote() || $tpl->disReadComments()) : ?> class="image_column"<?php endif; ?> id="image_infos">
<?php if ($tpl->disImage('desc')) : ?>
			<div class="image_column_bloc" id="image_description">
				<p style="width:<?php echo $tpl->getImage('width'); ?>px"><?php echo $tpl->getImage('desc'); ?></p>
			</div>
<?php endif; ?>

		</div>
<?php endif; ?>

		<hr class="sep" />

<?php if ($tpl->disNavigation('bottom')) : ?>
		<div class="nav" id="nav_bottom">

<?php if (!$tpl->disNavigation('top')) : ?>
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf('%s|%s', $tpl->getAlbum('current_image'), $tpl->getAlbum('nb_images')); ?></div>
<?php endif; ?>

<?php if ($tpl->disNavigation('prev_inactive')) : ?>
			<span class="first inactive"><?php echo $tpl->getNavigation('first'); ?></span>
			<span class="prev inactive"><?php echo $tpl->getNavigation('prev'); ?></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('prev_active')) : ?>
			<span class="first"><a title="<?php echo __('Première image'); ?>" href="<?php echo $tpl->getNavigation('first_link'); ?>"><?php echo $tpl->getNavigation('first'); ?></a></span>
			<span class="prev"><a title="<?php echo __('Image précédente'); ?>" href="<?php echo $tpl->getNavigation('prev_link'); ?>"><?php echo $tpl->getNavigation('prev'); ?></a></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('next_active')) : ?>
			<span class="next"><a title="<?php echo __('Image suivante'); ?>" href="<?php echo $tpl->getNavigation('next_link'); ?>"><?php echo $tpl->getNavigation('next'); ?></a></span>
			<span class="last"><a title="<?php echo __('Dernière image'); ?>" href="<?php echo $tpl->getNavigation('last_link'); ?>"><?php echo $tpl->getNavigation('last'); ?></a></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('next_inactive')) : ?>
			<span class="next inactive"><?php echo $tpl->getNavigation('next'); ?></span>
			<span class="last inactive"><?php echo $tpl->getNavigation('last'); ?></span>
<?php endif; ?>

		</div>
<?php endif; ?>
