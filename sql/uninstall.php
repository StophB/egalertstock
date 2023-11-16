<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

$sql = array();

// Table deletion
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'eg_alertstock`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

return true;
