				<td class="stats">
					<p class="title">
<?php if ($tpl->disStats('title')) : ?>
					<?php echo $tpl->getStats('title'); ?>

<?php else : ?>
					<?php echo __('Statistiques'); ?>

<?php endif; ?>
					</p>
					<ul>
<?php if ($tpl->disStats('images')) : ?>
						<li><?php echo $tpl->getStats('images'); ?></li>
<?php endif; ?>
<?php if ($tpl->disStats('albums')) : ?>
						<li><?php echo $tpl->getStats('albums'); ?></li>
<?php endif; ?>
<?php if ($tpl->disStats('filesize')) : ?>
						<li><?php echo $tpl->getStats('filesize'); ?></li>
<?php endif; ?>
<?php if ($tpl->disStats('recents')) : ?>
						<li><?php echo $tpl->getStats('recents'); ?></li>
<?php endif; ?>
<?php if ($tpl->disStats('hits')) : ?>
						<li><?php echo $tpl->getStats('hits'); ?></li>
<?php endif; ?>
<?php if ($tpl->disStats('comments')) : ?>
<?php if ($tpl->disGallery('comments_category')) : ?>
						<li class="stat_icon">
							<?php echo $tpl->getStats('comments'); ?>

							<a title="<?php echo __('Lire les commentaires'); ?>" id="comments_category" href="<?php echo $tpl->getLink('comments_category'); ?>">
								<img width="16" height="16" src="<?php echo $tpl->getGallery('style_path'); ?>/icons/comments.png" alt="<?php echo __('Lire les commentaires'); ?>" />
							</a>
						</li>
<?php else : ?>
						<li><?php echo $tpl->getStats('comments'); ?></li>
<?php endif; ?>
						
<?php endif; ?>
<?php if ($tpl->disStats('votes')) : ?>
						<li><?php echo $tpl->getStats('votes'); ?></li>
<?php endif; ?>
					</ul>
				</td>
