<?php

class EgAlertStockClass extends ObjectModel
{
    public $id_eg_alertstock;
    public $id_product;
    public $name;
    public $email;
    public $status;

    public static $definition = array(
        'table' => 'eg_alertstock',
        'primary' => 'id_eg_alertstock',
        'multilang' => false,
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'required' => true),
            'email' => array('type' => self::TYPE_STRING, 'required' => true),
            'id_product' => array('type' => self::TYPE_INT, 'required' => true),
            'status' => array('type' => self::TYPE_BOOL),

        )
    );

    // public static function updateStatus($productId, $email, $name)
    // {
    //     $sql = 'UPDATE `' . _DB_PREFIX_ . 'eg_alertstock`
    //             SET `status` = 0, `name` = \'' . pSQL($name) . '\'
    //             WHERE `id_product` = ' . (int)$productId . '
    //             AND `email` = \'' . pSQL($email) . '\'';

    //     Db::getInstance()->execute($sql);
    // }
}
