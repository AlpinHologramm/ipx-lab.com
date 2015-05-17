<?php include_once(dirname(__FILE__) . '/stats_submenu.tpl.php'); ?>

		<table id="objects_stats" summary="" class="default">
			<thead>
				<tr>
					<th></th>
					<th><?php echo __('Activés'); ?></th>
					<th><?php echo __('Désactivés'); ?></th>
					<th><?php echo __('Protégés'); ?></th>
					<th><?php echo __('En attente'); ?></th>
					<th><?php echo __('Total'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th></th>
					<th><?php echo __('Activés'); ?></th>
					<th><?php echo __('Désactivés'); ?></th>
					<th><?php echo __('Protégés'); ?></th>
					<th><?php echo __('En attente'); ?></th>
					<th><?php echo __('Total'); ?></th>
				</tr>
			</tfoot>
			<tbody>
				<tr class="objects_stats_sub"><td><?php echo __('Images'); ?></td><td></td><td></td><td></td><td></td><td class="objects_stats_sub_last"></td></tr>
				<tr>
					<td><?php echo __('Nombre d\'images'); ?></td>
					<td><?php echo $tpl->getActivate('nb_images'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_images'); ?></td>
					<td><?php echo $tpl->getProtected('nb_images'); ?></td>
					<td><?php echo $tpl->getPending('nb_images'); ?></td>
					<td><?php echo $tpl->getTotal('nb_images'); ?></td>
				</tr>
				<tr>
					<td><?php echo __('Poids total'); ?></td>
					<td><?php echo $tpl->getActivate('filesize_total'); ?></td>
					<td><?php echo $tpl->getDeactivate('filesize_total'); ?></td>
					<td><?php echo $tpl->getProtected('filesize_total'); ?></td>
					<td><?php echo $tpl->getPending('filesize_total'); ?></td>
					<td><?php echo $tpl->getTotal('filesize_total'); ?></td>
				</tr>
				<tr class="objects_stats_last">
					<td><?php echo __('Poids moyen'); ?></td>
					<td><?php echo $tpl->getActivate('filesize_average'); ?></td>
					<td><?php echo $tpl->getDeactivate('filesize_average'); ?></td>
					<td><?php echo $tpl->getProtected('filesize_average'); ?></td>
					<td><?php echo $tpl->getPending('filesize_average'); ?></td>
					<td><?php echo $tpl->getTotal('filesize_average'); ?></td>
				</tr>

				<tr class="objects_stats_sub"><td><?php echo __('Catégories'); ?></td><td></td><td></td><td></td><td></td><td class="objects_stats_sub_last"></td></tr>
				<tr>
					<td><?php echo __('Nombre d\'albums'); ?></td>
					<td><?php echo $tpl->getActivate('nb_albums'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_albums'); ?></td>
					<td><?php echo $tpl->getProtected('nb_albums'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_albums'); ?></td>
				</tr>
				<tr class="objects_stats_last">
					<td><?php echo __('Nombre de catégories'); ?></td>
					<td><?php echo $tpl->getActivate('nb_categories'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_categories'); ?></td>
					<td><?php echo $tpl->getProtected('nb_categories'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_categories'); ?></td>
				</tr>

				<tr class="objects_stats_sub"><td><?php echo __('Visites'); ?></td><td></td><td></td><td></td><td></td><td class="objects_stats_sub_last"></td></tr>
				<tr>
					<td><?php echo __('Nombre de visites'); ?></td>
					<td><?php echo $tpl->getActivate('nb_hits'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_hits'); ?></td>
					<td><?php echo $tpl->getProtected('nb_hits'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_hits'); ?></td>
				</tr>
				<tr>
					<td><?php echo __('Nombre d\'images visitées'); ?></td>
					<td><?php echo $tpl->getActivate('nb_images_hits'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_images_hits'); ?></td>
					<td><?php echo $tpl->getProtected('nb_images_hits'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_images_hits'); ?></td>
				</tr>
				<tr class="objects_stats_last">
					<td><?php echo __('Nombre de visites par image'); ?></td>
					<td><?php echo $tpl->getActivate('nb_hits_per_image'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_hits_per_image'); ?></td>
					<td><?php echo $tpl->getProtected('nb_hits_per_image'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_hits_per_image'); ?></td>
				</tr>

				<tr class="objects_stats_sub"><td><?php echo __('Commentaires'); ?></td><td></td><td></td><td></td><td></td><td class="objects_stats_sub_last"></td></tr>
				<tr>
					<td><?php echo __('Nombre de commentaires'); ?></td>
					<td><?php echo $tpl->getActivate('nb_comments'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_comments'); ?></td>
					<td><?php echo $tpl->getProtected('nb_comments'); ?></td>
					<td><?php echo $tpl->getPending('nb_comments'); ?></td>
					<td><?php echo $tpl->getTotal('nb_comments'); ?></td>
				</tr>
				<tr>
					<td><?php echo __('Nombre d\'images commentées'); ?></td>
					<td><?php echo $tpl->getActivate('nb_images_comments'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_images_comments'); ?></td>
					<td><?php echo $tpl->getProtected('nb_images_comments'); ?></td>
					<td><?php echo $tpl->getPending('nb_images_comments'); ?></td>
					<td><?php echo $tpl->getTotal('nb_images_comments'); ?></td>
				</tr>
				<tr class="objects_stats_last">
					<td><?php echo __('Nombre de commentaires par image'); ?></td>
					<td><?php echo $tpl->getActivate('nb_comments_per_image'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_comments_per_image'); ?></td>
					<td><?php echo $tpl->getProtected('nb_comments_per_image'); ?></td>
					<td><?php echo $tpl->getPending('nb_comments_per_image'); ?></td>
					<td><?php echo $tpl->getTotal('nb_comments_per_image'); ?></td>
				</tr>

				<tr class="objects_stats_sub"><td><?php echo __('Votes'); ?></td><td></td><td></td><td></td><td></td><td class="objects_stats_sub_last"></td></tr>
				<tr>
					<td><?php echo __('Nombre de votes'); ?></td>
					<td><?php echo $tpl->getActivate('nb_votes'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_votes'); ?></td>
					<td><?php echo $tpl->getProtected('nb_votes'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_votes'); ?></td>
				</tr>
				<tr>
					<td><?php echo __('Nombre d\'images notées'); ?></td>
					<td><?php echo $tpl->getActivate('nb_images_votes'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_images_votes'); ?></td>
					<td><?php echo $tpl->getProtected('nb_images_votes'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_images_votes'); ?></td>
				</tr>
				<tr>
					<td><?php echo __('Nombre de votes par image'); ?></td>
					<td><?php echo $tpl->getActivate('nb_votes_per_image'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_votes_per_image'); ?></td>
					<td><?php echo $tpl->getProtected('nb_votes_per_image'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_votes_per_image'); ?></td>
				</tr>
				<tr class="objects_stats_last">
					<td><?php echo __('Note moyenne par image'); ?></td>
					<td><?php echo $tpl->getActivate('average_rate'); ?></td>
					<td><?php echo $tpl->getDeactivate('average_rate'); ?></td>
					<td><?php echo $tpl->getProtected('average_rate'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('average_rate'); ?></td>
				</tr>

				<tr class="objects_stats_sub"><td><?php echo __('Tags'); ?></td><td></td><td></td><td></td><td></td><td class="objects_stats_sub_last"></td></tr>
				<tr>
					<td><?php echo __('Nombre de tags'); ?></td>
					<td><?php echo $tpl->getActivate('nb_tags'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_tags'); ?></td>
					<td><?php echo $tpl->getProtected('nb_tags'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_tags'); ?></td>
				</tr>
				<tr>
					<td><?php echo __('Nombre d\'images taggées'); ?></td>
					<td><?php echo $tpl->getActivate('nb_images_tags'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_images_tags'); ?></td>
					<td><?php echo $tpl->getProtected('nb_images_tags'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_images_tags'); ?></td>
				</tr>
				<tr class="objects_stats_last">
					<td><?php echo __('Nombre de tags par image'); ?></td>
					<td><?php echo $tpl->getActivate('nb_tags_per_image'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_tags_per_image'); ?></td>
					<td><?php echo $tpl->getProtected('nb_tags_per_image'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_tags_per_image'); ?></td>
				</tr>

				<tr class="objects_stats_sub"><td><?php echo __('Utilisateurs'); ?></td><td></td><td></td><td></td><td></td><td class="objects_stats_sub_last"></td></tr>
				<tr>
					<td><?php echo __('Nombre d\'administrateurs'); ?></td>
					<td><?php echo $tpl->getActivate('nb_admins'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_admins'); ?></td>
					<td>/</td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_admins'); ?></td>
				</tr>
				<tr>
					<td><?php echo __('Nombre de membres'); ?></td>
					<td><?php echo $tpl->getActivate('nb_members'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_members'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getPending('nb_members'); ?></td>
					<td><?php echo $tpl->getTotal('nb_members'); ?></td>
				</tr>
				<tr class="objects_stats_last">
					<td><?php echo __('Nombre de groupes'); ?></td>
					<td><?php echo $tpl->getActivate('nb_groups'); ?></td>
					<td>/</td>
					<td>/</td>
					<td>/</td>
					<td><?php echo $tpl->getActivate('nb_groups'); ?></td>
				</tr>

				<tr class="objects_stats_sub"><td><?php echo __('Favoris'); ?></td><td></td><td></td><td></td><td></td><td class="objects_stats_sub_last"></td></tr>
				<tr>
					<td><?php echo __('Nombre de favoris'); ?></td>
					<td><?php echo $tpl->getActivate('nb_favorites'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_favorites'); ?></td>
					<td><?php echo $tpl->getProtected('nb_favorites'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_favorites'); ?></td>
				</tr>
				<tr>
					<td><?php echo __('Nombre d\'images mis en favoris'); ?></td>
					<td><?php echo $tpl->getActivate('nb_images_favorites'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_images_favorites'); ?></td>
					<td><?php echo $tpl->getProtected('nb_images_favorites'); ?></td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_images_favorites'); ?></td>
				</tr>
				<tr class="objects_stats_last">
					<td><?php echo __('Nombre de favoris par utilisateur'); ?></td>
					<td><?php echo $tpl->getActivate('nb_favorites_per_user'); ?></td>
					<td><?php echo $tpl->getDeactivate('nb_favorites_per_user'); ?></td>
					<td>/</td>
					<td>/</td>
					<td><?php echo $tpl->getTotal('nb_favorites_per_user'); ?></td>
				</tr>
			</tbody>
		</table>
