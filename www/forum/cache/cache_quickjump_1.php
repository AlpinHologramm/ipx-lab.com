<?php

if (!defined('PUN')) exit;
define('PUN_QJ_LOADED', 1);
$forum_id = isset($forum_id) ? $forum_id : 0;

?>				<form id="qjump" method="get" action="viewforum.php">
					<div><label><span><?php echo $lang_common['Jump to'] ?><br /></span>
					<select name="id" onchange="window.location=('viewforum.php?id='+this.options[this.selectedIndex].value)">
						<optgroup label="00. Association (Administratif)">
							<option value="19"<?php echo ($forum_id == 19) ? ' selected="selected"' : '' ?>>00. Master Création Association (à Valider pour envoi)</option>
							<option value="9"<?php echo ($forum_id == 9) ? ' selected="selected"' : '' ?>>01. Statuts (OK)</option>
							<option value="10"<?php echo ($forum_id == 10) ? ' selected="selected"' : '' ?>>02. Exos (OK)</option>
							<option value="11"<?php echo ($forum_id == 11) ? ' selected="selected"' : '' ?>>03. Prochaine Réunion / Ordre du Jour</option>
							<option value="3"<?php echo ($forum_id == 3) ? ' selected="selected"' : '' ?>>04. Biographie</option>
							<option value="12"<?php echo ($forum_id == 12) ? ' selected="selected"' : '' ?>>05. Visuel</option>
							<option value="13"<?php echo ($forum_id == 13) ? ' selected="selected"' : '' ?>>06. Conseil D&#039;administration</option>
							<option value="18"<?php echo ($forum_id == 18) ? ' selected="selected"' : '' ?>>07. Réglement intérieur</option>
							<option value="20"<?php echo ($forum_id == 20) ? ' selected="selected"' : '' ?>>07. Contact Subvention</option>
							<option value="24"<?php echo ($forum_id == 24) ? ' selected="selected"' : '' ?>>08. Inventaire</option>
						</optgroup>
						<optgroup label="01. Association (Artistique)">
							<option value="4"<?php echo ($forum_id == 4) ? ' selected="selected"' : '' ?>>01. Workshops [IPW]</option>
							<option value="5"<?php echo ($forum_id == 5) ? ' selected="selected"' : '' ?>>02. Sofware [IPS]</option>
							<option value="6"<?php echo ($forum_id == 6) ? ' selected="selected"' : '' ?>>03. Press [IPP]</option>
						</optgroup>
						<optgroup label="04. Observatoire">
							<option value="22"<?php echo ($forum_id == 22) ? ' selected="selected"' : '' ?>>01. Observatoire D&#039;Application</option>
							<option value="23"<?php echo ($forum_id == 23) ? ' selected="selected"' : '' ?>>02. Observatoire des Edition Electronique</option>
						</optgroup>
						<optgroup label="02. Projets">
							<option value="1"<?php echo ($forum_id == 1) ? ' selected="selected"' : '' ?>>01. Dire / Faire</option>
							<option value="7"<?php echo ($forum_id == 7) ? ' selected="selected"' : '' ?>>02. Editions d&#039;artistes</option>
						</optgroup>
						<optgroup label="03. Site Internet">
							<option value="15"<?php echo ($forum_id == 15) ? ' selected="selected"' : '' ?>>01. Adresse Mail Site</option>
							<option value="14"<?php echo ($forum_id == 14) ? ' selected="selected"' : '' ?>>02. Pagination</option>
							<option value="17"<?php echo ($forum_id == 17) ? ' selected="selected"' : '' ?>>03. Accès - Login</option>
							<option value="16"<?php echo ($forum_id == 16) ? ' selected="selected"' : '' ?>>04. Manuel / Protocol</option>
						</optgroup>
						<optgroup label="04.Z.E.N">
							<option value="21"<?php echo ($forum_id == 21) ? ' selected="selected"' : '' ?>>[pl]</option>
						</optgroup>
					</select></label>
					<input type="submit" value="<?php echo $lang_common['Go'] ?>" accesskey="g" />
					</div>
				</form>
