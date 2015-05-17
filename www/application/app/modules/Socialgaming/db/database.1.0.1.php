<?php

$this->query("
    ALTER TABLE `socialgaming_game`
        ADD `value_id` INT(11) UNSIGNED NOT NULL AFTER `game_id`,
        ADD FOREIGN KEY `FK_APPLICATION_OPTION_VALUE_VALUE_ID` (value_id) references `application_option_value` (`value_id`) ON UPDATE CASCADE ON DELETE CASCADE
    ;
");
