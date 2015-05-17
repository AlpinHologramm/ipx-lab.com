
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

<?php if ($tpl->disGallery('thumbs_cat_extended')) : ?>
<?php include(dirname(__FILE__) . '/thumbs_extended.tpl.php'); ?>
<?php else : ?>
<?php include(dirname(__FILE__) . '/thumbs_compact.tpl.php'); ?>
<?php endif; ?>

<?php if ($tpl->disNavigation('bottom')) : ?>
		<div class="nav" id="nav_bottom">
<?php if (!$tpl->disNavigation('top')) : ?>
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf('%s|%s', $_GET['page'], $tpl->getCategory('nb_pages')); ?></div>
<?php endif; ?>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>
