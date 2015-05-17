<?php

$this->query("
    ALTER TABLE `application`
        MODIFY description longtext NULL DEFAULT NULL;
");
