<?php include_once(dirname(__FILE__) . '/functions_submenu.tpl.php'); ?>

		<div id="tools_browse">
			<div class="browse">
				<select id="functions_list" onchange="window.location.href='#'+this.options[this.selectedIndex].value">
					<option value="section_functions"><?php echo __('Parcourir :'); ?></option>
					<option value="f_comments"><?php echo __('Commentaires'); ?></option>
					<option value="f_diaporama"><?php echo __('Diaporama'); ?></option>
					<option value="f_members"><?php echo __('Espace membres'); ?></option>
					<option value="f_watermark"><?php echo __('Filigrane'); ?></option>
					<option value="f_geoloc"><?php echo __('Géolocalisation'); ?></option>
					<option value="f_exif"><?php echo __('Métadonnées EXIF'); ?></option>
					<option value="f_iptc"><?php echo __('Métadonnées IPTC'); ?></option>
					<option value="f_xmp"><?php echo __('Métadonnées XMP'); ?></option>
					<option value="f_search"><?php echo __('Moteur de recherche'); ?></option>
					<option value="f_basket"><?php echo __('Panier'); ?></option>
					<option value="f_rss">RSS</option>
					<option value="f_tags"><?php echo __('Tags'); ?></option>
					<option value="f_download_albums"><?php echo __('Téléchargement d\'albums'); ?></option>
					<option value="f_votes"><?php echo __('Votes'); ?></option>
				</select>
			</div>
		</div>

		<p id="position"><span class="current"><a href="<?php echo $tpl->getLink('functions'); ?>"><?php echo __('Fonctionnalités'); ?></a></span></p>

<?php if ($tpl->disReport()) : ?>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php else : ?>
				<br />
<?php endif; ?>

		<form action="#top" method="post">
			<div>
<?php if (!$tpl->disReport()) : ?>
				<br  />
<?php endif; ?>
				<div class="browse_anchor" id="f_comments"></div>
				<fieldset>
					<legend><?php echo __('Commentaires'); ?></legend>
					<div class="fielditems">
<?php if ($tpl->disDisabledConfig('comments')) : ?>
						<p class="field">
							<span class="report_msg report_info"><?php echo __('Cette fonctionnalité n\'est pas disponible avec le thème actuel.'); ?></span>
						</p>
<?php endif; ?>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('comments')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('comments')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('comments'); ?> id="comments" name="comments" type="checkbox" />
							<label for="comments"><?php echo __('Activer les commentaires'); ?></label>
						</p>
						<p class="field<?php if ($tpl->disDisabledConfig('comments')) : ?> f_disabled<?php endif; ?>">
							<a href="<?php echo $tpl->getLink('comments-options'); ?>"><?php echo __('options des commentaires'); ?></a>
						</p>
					</div>
				</fieldset>
				<br  />
				<div class="browse_anchor" id="f_diaporama"></div>
				<fieldset>
					<legend><?php echo __('Diaporama'); ?></legend>
					<div class="fielditems">
<?php if ($tpl->disDisabledConfig('diaporama')) : ?>
						<p class="field">
							<span class="report_msg report_info"><?php echo __('Cette fonctionnalité n\'est pas disponible avec le thème actuel.'); ?></span>
						</p>
<?php endif; ?>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('diaporama')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('diaporama')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('diaporama'); ?> id="diaporama" name="diaporama" type="checkbox" />
							<label for="diaporama"><?php echo __('Activer le diaporama'); ?></label>
						</p>
						<div class="field_second<?php if ($tpl->disDisabledConfig('diaporama')) : ?> f_disabled<?php endif; ?>">
							<p class="field checkbox">
								<input<?php if ($tpl->disDisabledConfig('diaporama')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('diaporama_auto_start'); ?> id="diaporama_auto_start" name="diaporama_auto_start" type="checkbox" />
								<label for="diaporama_auto_start"><?php echo __('Démarrer la lecture automatique au lancement du diaporama'); ?></label>
							</p>
							<p class="field checkbox">
								<input<?php if ($tpl->disDisabledConfig('diaporama')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('diaporama_carousel'); ?> id="diaporama_carousel" name="diaporama_carousel" type="checkbox" />
								<label for="diaporama_carousel"><?php echo __('Afficher le carrousel par défaut'); ?></label>
							</p>
							<p class="field checkbox">
								<input<?php if ($tpl->disDisabledConfig('diaporama')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('diaporama_hits'); ?> id="diaporama_hits" name="diaporama_hits" type="checkbox" />
								<label for="diaporama_hits"><?php echo __('Comptabiliser les visites'); ?></label>
							</p>
							<p class="field checkbox">
								<input<?php if ($tpl->disDisabledConfig('diaporama')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('diaporama_resize_gd'); ?> id="diaporama_resize_gd" name="diaporama_resize_gd" type="checkbox" />
								<label for="diaporama_resize_gd"><?php echo __('Redimensionner les images avec GD :'); ?></label>
							</p>
							<div class="field_second">
								<p class="field">
									<label><?php echo __('Dimensions : '); ?></label>
									<input<?php if ($tpl->disDisabledConfig('diaporama')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getFunction('diaporama_resize_gd_width'); ?>" name="diaporama_resize_gd_width" class="text" maxlength="4" type="text" size="4" />
									X
									<input<?php if ($tpl->disDisabledConfig('diaporama')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getFunction('diaporama_resize_gd_height'); ?>" name="diaporama_resize_gd_height" class="text" maxlength="4" type="text" size="4" />
									<?php echo __('pixels'); ?>
								</p>
								<p class="field">
									<label for="diaporama_resize_gd_quality"><?php echo __('Qualité (entre 0 et 100) :'); ?></label>
									<input<?php if ($tpl->disDisabledConfig('diaporama')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getFunction('diaporama_resize_gd_quality'); ?>" id="diaporama_resize_gd_quality" name="diaporama_resize_gd_quality" class="text" maxlength="4" type="text" size="4" />
								</p>
							</div>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="f_members"></div>
				<fieldset>
					<legend><?php echo __('Espace membres'); ?></legend>
					<div class="fielditems">
<?php if ($tpl->disDisabledConfig('users')) : ?>
						<p class="field">
							<span class="report_msg report_info"><?php echo __('Cette fonctionnalité n\'est pas disponible avec le thème actuel.'); ?></span>
						</p>
<?php endif; ?>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('users')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('users')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('users'); ?> id="users" name="users" type="checkbox" />
							<label for="users"><?php echo __('Activer l\'espace membres'); ?></label>
						</p>
						<p class="field">
							<a href="<?php echo $tpl->getLink('users-options'); ?>"><?php echo __('options des utilisateurs'); ?></a>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="f_watermark"></div>
				<fieldset>
					<legend>
						<?php echo __('Filigrane'); ?>
<?php if ($tpl->disHelp()) : ?>
						<a rel="h_watermark" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
					</legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getFunction('watermark'); ?> id="watermark" name="watermark" type="checkbox" />
							<label for="watermark"><?php echo __('Activer le filigrane global'); ?></label>
						</p>
						<div class="field_second">
							<p class="field">
								<a href="<?php echo $tpl->getLink('watermark'); ?>"><?php echo __('options du filigrane'); ?></a>
							</p>
						</div>
						<p class="field checkbox">
							<input<?php echo $tpl->getFunction('watermark_categories'); ?> id="watermark_categories" name="watermark_categories" type="checkbox" />
							<label for="watermark_categories"><?php echo __('Activer le filigrane des catégories'); ?></label>
						</p>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('users')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('users')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('watermark_users'); ?> id="watermark_users" name="watermark_users" type="checkbox" />
							<label for="watermark_users"><?php echo __('Activer le filigrane des utilisateurs'); ?></label>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="f_geoloc"></div>
				<fieldset>
					<legend><?php echo __('Géolocalisation'); ?></legend>
					<div class="fielditems">
<?php if ($tpl->disDisabledConfig('geoloc')) : ?>
						<p class="field">
							<span class="report_msg report_info"><?php echo __('Cette fonctionnalité n\'est pas disponible avec le thème actuel.'); ?></span>
						</p>
<?php endif; ?>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('geoloc')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('geoloc')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('geoloc'); ?> id="geoloc" name="geoloc" type="checkbox" />
							<label for="geoloc"><?php echo __('Activer la géolocalisation (Google Maps)'); ?></label>
						</p>
						<div class="field_second<?php if ($tpl->disDisabledConfig('geoloc')) : ?> f_disabled<?php endif; ?>">
							<p class="field">
								<label><?php echo __('Type de carte par défaut :'); ?></label>
								<select<?php if ($tpl->disDisabledConfig('geoloc')) : ?> disabled="disabled"<?php endif; ?> name="geoloc_type">
									<?php echo $tpl->getFunction('geoloc_type'); ?>

								</select>
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="f_exif"></div>
				<fieldset>
					<legend><?php echo __('Métadonnées EXIF'); ?></legend>
					<div class="fielditems">
<?php if (!$tpl->disFunction('exif')) : ?>
						<p class="field">
							<span class="report_msg report_exclamation"><?php printf(__('Cette fonctionnalité n\'est pas disponible car l\'extension %s n\'est pas chargée.'), 'exif'); ?></span>
						</p>
<?php endif; ?>
<?php if ($tpl->disDisabledConfig('exif')) : ?>
						<p class="field">
							<span class="report_msg report_info"><?php echo __('Cette fonctionnalité n\'est pas disponible avec le thème actuel.'); ?></span>
						</p>
<?php endif; ?>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('exif') || !$tpl->disFunction('exif')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('exif') || !$tpl->disFunction('exif')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('exif'); ?> id="exif" name="exif" type="checkbox" />
							<label for="exif"><?php echo __('Afficher les informations disponibles'); ?></label>
						</p>
						<div class="field_second<?php if ($tpl->disDisabledConfig('exif') || !$tpl->disFunction('exif')) : ?> f_disabled<?php endif; ?>">
							<p class="field">
								<a href="<?php echo $tpl->getLink('exif'); ?>"><?php echo __('choix des informations à afficher'); ?></a>
							</p>
						</div>
						<p class="field<?php if (!$tpl->disFunction('exif')) : ?> f_disabled<?php endif; ?>">
							<?php echo __('Récupérer et associer aux images ces informations lors de l\'ajout de nouvelles images :'); ?>
						</p>
						<div class="field_second<?php if (!$tpl->disFunction('exif')) : ?> f_disabled<?php endif; ?>">
							<p class="field checkbox">
								<input<?php if (!$tpl->disFunction('exif')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('exif_crtdt'); ?> id="exif_crtdt" name="exif_crtdt" type="checkbox" />
								<label for="exif_crtdt"><?php echo __('date de création'); ?></label>
							</p>
							<p class="field checkbox">
								<input<?php if (!$tpl->disFunction('exif')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('exif_camera'); ?> id="exif_camera" name="exif_camera" type="checkbox" />
								<label for="exif_camera"><?php echo __('marque et modèle de l\'appareil'); ?></label>
							</p>
							<p class="field checkbox">
								<input<?php if (!$tpl->disFunction('exif')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('exif_gps'); ?> id="exif_gps" name="exif_gps" type="checkbox" />
								<label for="exif_gps"><?php echo __('coordonnées GPS'); ?></label>
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="f_iptc"></div>
				<fieldset>
					<legend><?php echo __('Métadonnées IPTC'); ?></legend>
					<div class="fielditems">
<?php if ($tpl->disDisabledConfig('iptc')) : ?>
						<p class="field">
							<span class="report_msg report_info"><?php echo __('Cette fonctionnalité n\'est pas disponible avec le thème actuel.'); ?></span>
						</p>
<?php endif; ?>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('iptc')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('iptc')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('iptc'); ?> id="iptc" name="iptc" type="checkbox" />
							<label for="iptc"><?php echo __('Afficher les informations disponibles'); ?></label>
						</p>
						<div class="field_second<?php if ($tpl->disDisabledConfig('iptc')) : ?> f_disabled<?php endif; ?>">
							<p class="field">
								<a href="<?php echo $tpl->getLink('iptc'); ?>"><?php echo __('choix des informations à afficher'); ?></a>
							</p>
						</div>
						<p class="field">
							<?php echo __('Récupérer et associer aux images ces informations lors de l\'ajout de nouvelles images :'); ?>
						</p>
						<div class="field_second">
							<p class="field checkbox">
								<input<?php echo $tpl->getFunction('iptc_title'); ?> id="iptc_title" name="iptc_title" type="checkbox" />
								<label for="iptc_title"><?php echo __('titre'); ?></label>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getFunction('iptc_description'); ?> id="iptc_description" name="iptc_description" type="checkbox" />
								<label for="iptc_description"><?php echo __('description'); ?></label>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getFunction('iptc_keywords'); ?> id="iptc_keywords" name="iptc_keywords" type="checkbox" />
								<label for="iptc_keywords"><?php echo __('mots-clés'); ?></label>
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="f_xmp"></div>
				<fieldset>
					<legend><?php echo __('Métadonnées XMP'); ?></legend>
					<div class="fielditems">
<?php if ($tpl->disDisabledConfig('xmp')) : ?>
						<p class="field">
							<span class="report_msg report_info"><?php echo __('Cette fonctionnalité n\'est pas disponible avec le thème actuel.'); ?></span>
						</p>
<?php endif; ?>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('xmp')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('xmp')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('xmp'); ?> id="xmp" name="xmp" type="checkbox" />
							<label for="xmp"><?php echo __('Afficher les informations disponibles'); ?></label>
						</p>
						<div class="field_second<?php if ($tpl->disDisabledConfig('xmp')) : ?> f_disabled<?php endif; ?>">
							<p class="field">
								<a href="<?php echo $tpl->getLink('xmp'); ?>"><?php echo __('choix des informations à afficher'); ?></a>
							</p>
						</div>
						<p class="field">
							<?php echo __('Récupérer et associer aux images ces informations lors de l\'ajout de nouvelles images :'); ?>
						</p>
						<div class="field_second">
							<p class="field checkbox">
								<input<?php echo $tpl->getFunction('xmp_crtdt'); ?> id="xmp_crtdt" name="xmp_crtdt" type="checkbox" />
								<label for="xmp_crtdt"><?php echo __('date de création'); ?></label>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getFunction('xmp_title'); ?> id="xmp_title" name="xmp_title" type="checkbox" />
								<label for="xmp_title"><?php echo __('titre'); ?></label>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getFunction('xmp_description'); ?> id="xmp_description" name="xmp_description" type="checkbox" />
								<label for="xmp_description"><?php echo __('description'); ?></label>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getFunction('xmp_keywords'); ?> id="xmp_keywords" name="xmp_keywords" type="checkbox" />
								<label for="xmp_keywords"><?php echo __('mots-clés'); ?></label>
							</p>
						</div>
						<p class="field checkbox">
							<input<?php echo $tpl->getFunction('xmp_priority'); ?> id="xmp_priority" name="xmp_priority" type="checkbox" />
							<label for="xmp_priority"><?php echo __('XMP est prioritaire sur IPTC et EXIF'); ?></label>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="f_search"></div>
				<fieldset>
					<legend><?php echo __('Moteur de recherche'); ?></legend>
					<div class="fielditems">
<?php if ($tpl->disDisabledConfig('search')) : ?>
						<p class="field">
							<span class="report_msg report_info"><?php echo __('Cette fonctionnalité n\'est pas disponible avec le thème actuel.'); ?></span>
						</p>
<?php endif; ?>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('search')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('search')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('search'); ?> id="search" name="search" type="checkbox" />
							<label for="search"><?php echo __('Activer le moteur de recherche'); ?></label>
						</p>
						<div class="field_second<?php if ($tpl->disDisabledConfig('search_advanced')) : ?> f_disabled<?php endif; ?>">
							<p class="field checkbox">
								<input<?php if ($tpl->disDisabledConfig('search_advanced')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('search_advanced'); ?> id="search_advanced" name="search_advanced" type="checkbox" />
								<label for="search_advanced"><?php echo __('Activer la recherche avancée'); ?></label>
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="f_basket"></div>
				<fieldset>
					<legend><?php echo __('Panier'); ?></legend>
					<div class="fielditems">
<?php if (!$tpl->disFunction('zip')) : ?>
						<p class="field">
							<span class="report_msg report_exclamation"><?php printf(__('Cette fonctionnalité n\'est pas disponible car l\'extension %s n\'est pas chargée.'), 'zip'); ?></span>
						</p>
<?php endif; ?>
<?php if ($tpl->disDisabledConfig('basket')) : ?>
						<p class="field">
							<span class="report_msg report_info"><?php echo __('Cette fonctionnalité n\'est pas disponible avec le thème actuel.'); ?></span>
						</p>
<?php endif; ?>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('basket') || !$tpl->disFunction('zip')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('basket') || !$tpl->disFunction('zip')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('basket'); ?> id="basket" name="basket" type="checkbox" />
							<label for="basket"><?php echo __('Activer le panier'); ?></label>
						</p>
						<div class="field_second<?php if ($tpl->disDisabledConfig('basket') || !$tpl->disFunction('zip')) : ?> f_disabled<?php endif; ?>">
							<p class="field">
								<label for="basket_max_images"><?php echo __('Nombre maximum d\'images :'); ?></label>
								<input<?php if ($tpl->disDisabledConfig('basket') || !$tpl->disFunction('zip')) : ?> disabled="disabled"<?php endif; ?> id="basket_max_images" name="basket_max_images" type="text" class="text" size="6" maxlength="5" value="<?php echo $tpl->getFunction('basket_max_images'); ?>" />
							</p>
							<p class="field">
								<label for="basket_max_filesize"><?php echo __('Poids maximum :'); ?></label>
								<input<?php if ($tpl->disDisabledConfig('basket') || !$tpl->disFunction('zip')) : ?> disabled="disabled"<?php endif; ?> id="basket_max_filesize" name="basket_max_filesize" type="text" class="text" size="9" maxlength="8" value="<?php echo $tpl->getFunction('basket_max_filesize'); ?>" /> <?php echo __('Ko'); ?>
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="f_rss"></div>
				<fieldset>
					<legend>RSS</legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getFunction('rss'); ?> id="rss" name="rss" type="checkbox" />
							<label for="rss"><?php echo __('Activer les flux RSS'); ?></label>
						</p>
						<div class="field_second">
							<p class="field">
								<label for="rss_max_items"><?php echo __('Nombre maximum d\'objets dans les flux :'); ?></label>
								<input id="rss_max_items" name="rss_max_items" type="text" class="text" size="3" maxlength="2" value="<?php echo $tpl->getFunction('rss_max_items'); ?>" />
							</p>
							<p class="field"><?php echo __('Pour le flux des images sur la page des catégories, notifier :'); ?></p>
							<div class="field_second">
								<p class="field checkbox">
									<input<?php echo $tpl->getFunction('rss_notify_albums_1'); ?> id="rss_notify_albums_1" value="1" name="rss_notify_albums" type="radio" />
									<label for="rss_notify_albums_1"><?php echo __('les derniers albums ajoutés et mis à jour'); ?></label>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getFunction('rss_notify_albums_0'); ?> id="rss_notify_albums_0" value="0" name="rss_notify_albums" type="radio" />
									<label for="rss_notify_albums_0"><?php echo __('les dernières images'); ?></label>
								</p>
							</div>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="f_tags"></div>
				<fieldset>
					<legend><?php echo __('Tags'); ?></legend>
					<div class="fielditems">
<?php if ($tpl->disDisabledConfig('tags')) : ?>
						<p class="field">
							<span class="report_msg report_info"><?php echo __('Cette fonctionnalité n\'est pas disponible avec le thème actuel.'); ?></span>
						</p>
<?php endif; ?>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('tags')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('tags')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('tags'); ?> id="tags" name="tags" type="checkbox" />
							<label for="tags"><?php echo __('Activer les tags'); ?></label>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="f_download_albums"></div>
				<fieldset>
					<legend><?php echo __('Téléchargement d\'albums'); ?></legend>
					<div class="fielditems">
<?php if (!$tpl->disFunction('zip')) : ?>
						<p class="field">
							<span class="report_msg report_exclamation"><?php printf(__('Cette fonctionnalité n\'est pas disponible car l\'extension %s n\'est pas chargée.'), 'zip'); ?></span>
						</p>
<?php endif; ?>
<?php if ($tpl->disDisabledConfig('download_zip_albums')) : ?>
						<p class="field">
							<span class="report_msg report_info"><?php echo __('Cette fonctionnalité n\'est pas disponible avec le thème actuel.'); ?></span>
						</p>
<?php endif; ?>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('download_zip_albums') || !$tpl->disFunction('zip')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('download_zip_albums') || !$tpl->disFunction('zip')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('download_zip_albums'); ?> id="download_zip_albums" name="download_zip_albums" type="checkbox" />
							<label for="download_zip_albums"><?php echo __('Activer le téléchargement d\'albums (archives Zip)'); ?></label>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="f_votes"></div>
				<fieldset>
					<legend><?php echo __('Votes'); ?></legend>
					<div class="fielditems">
<?php if ($tpl->disDisabledConfig('votes')) : ?>
						<p class="field">
							<span class="report_msg report_info"><?php echo __('Cette fonctionnalité n\'est pas disponible avec le thème actuel.'); ?></span>
						</p>
<?php endif; ?>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('votes')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('votes')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getFunction('votes'); ?> id="votes" name="votes" type="checkbox" />
							<label for="votes"><?php echo __('Activer les votes pour les images'); ?></label>
						</p>
					</div>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>
