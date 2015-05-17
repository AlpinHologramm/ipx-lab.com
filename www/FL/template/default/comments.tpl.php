
		<div id="position">
			<?php echo $tpl->getPosition(); ?>

<?php if ($tpl->disPositionAlbumsList()) : ?>
			<div class="albums_list">
				<?php echo __('Parcourir :'); ?>

				<select name="browse" onchange="window.location.href='<?php echo $tpl->getGallery('gallery_base_url'); ?>'+this.options[this.selectedIndex].value">
					<?php echo $tpl->getPositionAlbumsList(); ?>

				</select>
			</div>
<?php endif; ?>

		</div>

<?php if ($tpl->disNavigation('top')) : ?>
		<div class="nav" id="nav_top">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getCommentsProperty('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>
			
		</div>
<?php endif; ?>

		<div id="page_comments">
<?php while ($tpl->nextComment(66)) : ?>
			<div class="comment">
				<div class="image">
					<a href="<?php echo $tpl->getComment('image_link'); ?>">
						<img style="padding:<?php echo $tpl->getComment('thumb_center'); ?>"
							<?php echo $tpl->getComment('thumb_size'); ?>

							alt="<?php echo $tpl->getComment('image_title'); ?>"
							src="<?php echo $tpl->getComment('thumb_src'); ?>" />
					</a>
				</div>
				<div class="infos">
					<p class="num"><a href="<?php echo $tpl->getComment('link'); ?>">#<?php echo $tpl->getComment('num'); ?></a></p>
					<p class="date"><?php printf(__('Le %s à %s,'), $tpl->getComment('date'), $tpl->getComment('time')); ?></p>
					<p class="author">
<?php if (!$tpl->disGallery('users') || $tpl->disComment('guest')) : ?>
						<?php printf(__('%s a écrit :'), '<span>' . $tpl->getComment('author_and_website') . '</span>'); ?>

<?php else : ?>
						<?php printf(__('%s a écrit :'), '<span><a title="' . sprintf(__('Profil de %s'), $tpl->getComment('author')) . '" href="' . $tpl->getLink('user/' . $tpl->getComment('user_id')) . '">' . $tpl->getComment('author') . '</a></span>'); ?>

<?php endif; ?>
					</p>
				</div>
				<p class="msg"><?php echo $tpl->getComment('message'); ?></p>
			</div>
<?php endwhile; ?>
		</div>

<?php if ($tpl->disNavigation('bottom')) : ?>
		<div class="nav" id="nav_bottom">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getCommentsProperty('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>
			
		</div>
<?php endif; ?>
