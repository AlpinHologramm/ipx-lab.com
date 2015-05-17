		<div class="thumbs thumbs_extended thumbs_cat l<?php echo $tpl->getThumbLinesNumber(); ?>">

<?php while ($tpl->nextThumb()) : ?>
			<dl<?php if (!$tpl->disThumb('auth')) : ?> class="protect"<?php elseif ($tpl->disThumb('recent')) : ?> class="new"<?php endif; ?>>
				<dt style="width:<?php echo $tpl->getThumb('thumb_width'); ?>px;">
					<a class="thumb_link" style="width:<?php echo $tpl->getThumb('thumb_width'); ?>px;height:<?php echo $tpl->getThumb('thumb_height'); ?>px;"
<?php if ($tpl->disThumb('auth')) : ?>
						title="<?php if ($tpl->disThumb('category')) : ?><?php printf(__('Entrez dans la catégorie \'%s\''), $tpl->getThumb('title')); ?><?php endif; ?><?php if ($tpl->disThumb('album')) : ?><?php printf(__('Entrez dans l\'album \'%s\''), $tpl->getThumb('title')); ?><?php endif; ?>"
<?php endif; ?>
						href="<?php echo $tpl->getThumb('link'); ?>">
<?php if ($tpl->disThumb('auth')) : ?>
						<img <?php echo $tpl->getThumb('thumb_size'); ?> style="padding:<?php echo $tpl->getThumb('thumb_center'); ?>;" alt="<?php echo $tpl->getThumb('title'); ?>" src="<?php echo $tpl->getThumb('thumb_src'); ?>" />
<?php endif; ?>
					</a>
				</dt>
<?php if ($tpl->disThumb('infos')) : ?>
				<dd>
					<ul style="min-height:<?php echo $tpl->getThumb('thumb_height'); ?>px;">
<?php if ($tpl->disThumb('title')) : ?>
						<li title="<?php echo $tpl->getThumb('title'); ?>" class="title">
							<a href="<?php echo $tpl->getThumb('link'); ?>"><?php echo $tpl->getThumb('title'); ?></a>
						</li>
<?php endif; ?>
<?php if ($tpl->disThumb('images')) : ?>
						<li title="<?php echo $tpl->getThumb('images'); ?>" class="images">
							<?php echo $tpl->getThumb('images_linked'); ?><?php if ($tpl->disThumb('recent_images') && $tpl->disThumb('auth')) : ?>&nbsp;<span><?php echo $tpl->getThumb('recent_images'); ?></span><?php endif; ?>

						</li>
<?php endif; ?>
<?php if ($tpl->disThumb('albums')) : ?>
						<li title="<?php echo $tpl->getThumb('albums'); ?>" class="albums"><?php echo $tpl->getThumb('albums'); ?></li>
<?php endif; ?>
<?php if ($tpl->disThumb('filesize')) : ?>
						<li title="<?php echo $tpl->getThumb('filesize'); ?>" class="filesize"><?php echo $tpl->getThumb('filesize'); ?></li>
<?php endif; ?>
<?php if ($tpl->disThumb('hits')) : ?>
						<li title="<?php echo $tpl->getThumb('hits'); ?>" class="hits"><?php echo $tpl->getThumb('hits_linked'); ?></li>
<?php endif; ?>
<?php if ($tpl->disThumb('comments')) : ?>
						<li title="<?php echo $tpl->getThumb('comments'); ?>" class="comments"><?php echo $tpl->getThumb('comments_linked'); ?></li>
<?php endif; ?>
<?php if ($tpl->disThumb('rate')) : ?>
						<li title="<?php echo $tpl->getThumb('rate'); ?>" class="rate">
							<?php echo $tpl->getThumb('rate_visual_small'); ?> <span>(<?php echo $tpl->getThumb('votes_linked'); ?>)</span>
						</li>
<?php endif; ?>
<?php if ($tpl->disThumb('desc')) : ?>
						<li class="description"><?php echo $tpl->getThumb('desc'); ?></li>
<?php endif; ?>
					</ul>
				</dd>
<?php endif; ?>
			</dl>
<?php endwhile; ?>

		<hr class="sep" />
		</div>