		<h2><a href="<?php echo $tpl->getLink('options-gallery'); ?>"><?php echo __('Options'); ?></a></h2>

		<ul id="sub_menu">
			<li<?php if ($_GET['section'] == 'options-gallery') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('options-gallery'); ?>"><?php echo __('Galerie'); ?></a></li>
			<li<?php if ($_GET['section'] == 'options-thumbs') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('options-thumbs'); ?>"><?php echo __('Vignettes'); ?></a></li>
			<li<?php if ($_GET['section'] == 'options-images') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('options-images'); ?>"><?php echo __('Images'); ?></a></li>
			<li<?php if ($_GET['section'] == 'options-descriptions') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('options-descriptions'); ?>"><?php echo __('Descriptions'); ?></a></li>
			<li<?php if ($_GET['section'] == 'options-email') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('options-email'); ?>"><?php echo __('Courriel'); ?></a></li>
			<li<?php if ($_GET['section'] == 'options-blacklists') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('options-blacklists'); ?>"><?php echo __('Listes noires'); ?></a></li>
			<li<?php if ($_GET['section'] == 'options-advanced') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('options-advanced'); ?>"><?php echo __('Avancé'); ?></a></li>
		</ul><div id="sub_menu_line"></div><div id="sub_menu_bg"></div>
