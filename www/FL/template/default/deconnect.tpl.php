
<?php if ($tpl->disDeconnect()) : ?>
		<form id="deconnect_object" action="" method="post">
			<p>
				<input id="deconnect_object_input" name="deconnect_object" type="submit" value="<?php echo __('Déconnexion'); ?>" />
				<script type="text/javascript">document.getElementById('deconnect_object_input').style.display = 'none';</script>
			</p>
		</form>
<?php endif; ?>
