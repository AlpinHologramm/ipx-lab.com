<?php

$this->query("
    ALTER TABLE `media_library_image`
        ADD `app_id` int(11) UNSIGNED NULL DEFAULT NULL AFTER `option_id`;
");
