				<td id="online_users" class="widget_links">
					<p class="title">
<?php if ($tpl->disOnlineUsers('widget_title')) : ?>
						<?php echo $tpl->getOnlineUsers('widget_title'); ?>

<?php else : ?>
						<?php echo __('En ligne'); ?>

<?php endif; ?>
					</p>
					<ul>
<?php while ($tpl->nextOnlineUsers()) : ?>
						<li>
							<a title="<?php echo $tpl->getOnlineUsers('last_visited'); ?>"
								href="<?php echo $tpl->getOnlineUsers('user_link'); ?>">
								<?php echo $tpl->getOnlineUsers('user_login'); ?>

							</a>
						</li>
<?php endwhile; ?>
					</ul>
				</td>
