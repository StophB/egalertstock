<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

$sql = array();

// Table creation
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'eg_alertstock` (
    `id_eg_alertstock` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_product` INT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `status` int(11) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id_eg_alertstock`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

return true;
