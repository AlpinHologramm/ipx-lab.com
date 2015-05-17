		<div id="position">
<?php if ($tpl->disDeconnect()) : ?>
			<form id="deconnect_form" action="" method="post">
				<p>
					<input type="hidden" name="deconnect_object" />
					<input id="deconnect_input" type="submit" value="<?php echo __('Déconnexion'); ?>" />
				</p>
			</form>
			<span id="deconnect_link"><a class="js_link" href="javascript:document.getElementById('deconnect_form').submit();"><?php echo mb_strtolower(__('Déconnexion')); ?></a></span>
			<script type="text/javascript">
			document.getElementById('deconnect_input').style.display = 'none';
			document.getElementById('deconnect_link').style.display = 'block';
			</script>
<?php endif; ?>
			<?php echo $tpl->getPosition(); ?>

		</div>
