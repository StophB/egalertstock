<?php


class EgAlertStockAlertSubmitModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        if (Tools::getValue('action') === 'submitAlertForm') {
            $productId = (int)Tools::getValue('productId');
            $email = pSQL(Tools::getValue('email'));
            $name = pSQL(Tools::getValue('name'));

            if ($this->isAlertSaved($productId, $email, $name)) {
                $response = array(
                    'success' => true,
                    'message' => 'Alert already saved!'
                );
            } else {
                if ($this->saveAlert($productId, $email, $name)) {
                    $response = array(
                        'success' => true,
                        'message' => 'Alert submitted successfully!'
                    );
                }
            }

            header('Content-Type: application/json');
            die(json_encode($response));
        }

        $this->setTemplate('module:egalertstock/views/templates/hook/egalertstock.tpl');
    }


    public function isAlertSaved($productId, $email, $name)
    {
        $query = new DbQuery();
        $query->select('COUNT(*)');
        $query->from('eg_alertstock', 'e');
        $query->where('e.id_product = ' . (int)$productId);
        $query->where('e.email = \'' . pSQL($email) . '\'');
        $query->where('e.name = \'' . pSQL($name) . '\'');

        $result = Db::getInstance()->getValue($query);

        return (int)$result > 0;
    }


    public function saveAlert($productId, $email, $name)
    {
        $query = 'INSERT INTO `' . _DB_PREFIX_ . 'eg_alertstock` (id_product, email, name) 
              VALUES (' . (int)$productId . ', \'' . pSQL($email) . '\', \'' . pSQL($name) . '\')';

        return Db::getInstance()->execute($query);
    }
}
