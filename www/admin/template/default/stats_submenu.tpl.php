		<h2><a href="<?php echo $tpl->getLink('stats-objects'); ?>"><?php echo __('Statistiques de la galerie'); ?></a></h2>

		<ul id="sub_menu">
			<li<?php if ($_GET['section'] == 'stats-objects') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('stats-objects'); ?>"><?php echo __('Objets'); ?></a></li>
			<li<?php if ($_GET['section'] == 'stats-users') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('stats-users'); ?>"><?php echo __('Utilisateurs'); ?></a></li>
			<!--<li<?php if ($_GET['section'] == 'stats-history') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('stats-history'); ?>"><?php echo __('Historique'); ?></a></li>-->
		</ul><div id="sub_menu_line"></div><div id="sub_menu_bg"></div>
