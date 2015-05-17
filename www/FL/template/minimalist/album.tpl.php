
<?php include(dirname(__FILE__) . '/position.tpl.php'); ?>

<?php if ($tpl->disCategory('desc')) : ?>
		<div id="cat_description"><p><?php echo $tpl->getCategory('desc'); ?></p></div>
<?php endif; ?>

<?php if ($tpl->disNavigation('top')) : ?>
		<div class="nav" id="nav_top">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf('%s|%s', $_GET['page'], $tpl->getCategory('nb_pages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>
			
		</div>
<?php endif; ?>


		<div class="thumbs thumbs_alb l<?php echo $tpl->getThumbLinesNumber(); ?>">

<?php while ($tpl->nextThumb()) : ?>
			<dl>
				<dt style="width:<?php echo $tpl->getThumb('width'); ?>px;">
					<a style="width:<?php echo $tpl->getThumb('width'); ?>px;height:<?php echo $tpl->getThumb('height'); ?>px;" title="<?php echo $tpl->getThumb('title'); ?>" href="<?php echo $tpl->getThumb('link'); ?>">
						<img <?php echo $tpl->getThumb('size'); ?> style="padding:<?php echo $tpl->getThumb('center'); ?>;" alt="<?php echo $tpl->getThumb('title'); ?>" src="<?php echo $tpl->getThumb('src'); ?>" />
					</a>
				</dt>
<?php if ($tpl->disThumb('infos')) : ?>
				<dd>
					<ul style="width:<?php echo $tpl->getThumb('width'); ?>px;">
<?php if ($tpl->disThumb('title')) : ?>
						<li title="<?php echo $tpl->getThumb('title'); ?>" class="title">
							<a href="<?php echo $tpl->getThumb('link'); ?>"><?php echo $tpl->getThumb('title'); ?></a>
						</li>
<?php endif; ?>
<?php if ($tpl->disThumb('filesize')) : ?>
						<li title="<?php echo $tpl->getThumb('filesize'); ?>" class="filesize"><?php echo $tpl->getThumb('filesize'); ?></li>
<?php endif; ?>
<?php if ($tpl->disThumb('image_size')) : ?>
						<li title="<?php echo $tpl->getThumb('image_size'); ?>" class="size"><?php echo $tpl->getThumb('image_size'); ?></li>
<?php endif; ?>
<?php if ($tpl->disThumb('added_date')) : ?>
						<li title="<?php echo $tpl->getThumb('added_date'); ?>" class="added_date<?php if ($tpl->disThumb('added_date_special')) : ?> special<?php endif; ?>"><?php echo $tpl->getThumb('added_date'); ?></li>
<?php endif; ?>
<?php if ($tpl->disThumb('hits')) : ?>
						<li title="<?php echo $tpl->getThumb('hits'); ?>" class="hits<?php if ($tpl->disThumb('hits_special')) : ?> special<?php endif; ?>"><?php echo $tpl->getThumb('hits'); ?></li>
<?php endif; ?>
					</ul>
				</dd>
<?php endif; ?>
			</dl>
<?php endwhile; ?>

			<hr class="sep" />
		</div>

<?php if ($tpl->disNavigation('bottom')) : ?>
		<div class="nav" id="nav_bottom">
<?php if (!$tpl->disNavigation('top')) : ?>
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf('%s|%s', $_GET['page'], $tpl->getCategory('nb_pages')); ?></div>
<?php endif; ?>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>
			
		</div>
<?php endif; ?>
