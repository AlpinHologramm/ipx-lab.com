<?php

$this->query("ALTER TABLE `comment`
    CHANGE `text` `text` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
");
