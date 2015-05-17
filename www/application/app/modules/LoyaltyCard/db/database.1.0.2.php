<?php

$option = new Application_Model_Option();
$option->find('loyalty', 'code');
$option->setOnlyOnce(1)->save();