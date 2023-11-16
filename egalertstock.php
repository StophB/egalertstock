<?php


require_once(_PS_MODULE_DIR_ . "egalertstock/classes/EgAlertStockClass.php");


if (!defined('_PS_VERSION_')) {
    exit;
}

class EgAlertStock extends Module
{
    protected $templateFile;
    protected $html = '';
    protected $product;

    public function __construct()
    {
        $this->name = 'egalertstock';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Stoph';
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Eg Alert Stock');
        $this->description = $this->l('Egio Alert Stock Module Notify costumers when product is in stock');

        $this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir désinstaller ce module ?');

        if (!Configuration::get('CUSTOM_BTN')) {
            $this->warning = $this->l('Aucun nom fourni');
        }

        $this->templateFile = 'module:egalertstock/views/templates/hook/egalertstock.tpl';
    }


    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (
            !parent::install() ||
            !$this->registerHook('displayReassurance') ||
            !$this->registerHook('displayProductActions') ||
            !$this->registerHook('actionUpdateQuantity') ||
            !$this->registerHook('header') ||
            !Configuration::updateValue('CUSTOM_BTN', 'Notify me')
        ) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

        if (
            !parent::uninstall() ||
            !Configuration::deleteByName('CUSTOM_BTN') ||
            !$this->unregisterHook('displayReassurance') ||
            !$this->unregisterHook('displayProductActions') ||
            !$this->unregisterHook('actionUpdateQuantity') ||
            !$this->unregisterHook('header')
        ) {
            return false;
        }

        return true;
    }

    public function getContent()
    {

        if (Tools::isSubmit('btnSubmit')) {
            $customBtn = strval(Tools::getValue('CUSTOM_BTN'));

            if (
                !$customBtn ||
                empty($customBtn)
            ) {
                $this->html .= $this->displayError($this->l('Invalid Configuration value'));
            } else {
                Configuration::updateValue('CUSTOM_BTN', $customBtn);
                $this->html .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        if (Tools::getValue('id_eg_alertstock')) {
            $resultAction = false;

            $id = Tools::getValue('id_eg_alertstock');
            $egAlertStock = new EgAlertStockClass($id);


            if (Tools::isSubmit('deleteegalertstock')) {
                if ($egAlertStock->delete())
                    $resultAction = true;
            }

            if ($resultAction)
                $this->html .= "<div class='alert alert-success' >Action executed correctly </div>";
            else
                $this->html .= "<div class='alert alert-error' >Error happened</div>";
        }

        $this->html .= $this->renderForm();
        $this->html .= $this->renderList();
        return $this->html;
    }

    public function renderList()
    {
        $data = $this->getAllRecord();

        $list = array(
            'id_eg_alertstock' => array(
                'title' => "ID ",
                'width' => 80,
                'search' => false,
                'orderby' => false
            ),
            'id_product' => array(
                'title' => "ID Product",
                'width' => 80,
                'search' => false,
                'orderby' => false
            ),
            'name' => array(
                'title' => "Customer Name",
                'width' => 140,
                'search' => false,
                'orderby' => false
            ),
            'email' => array(
                'title' => "Customer Email",
                'width' => 140,
                'search' => false,
                'orderby' => false
            ),
            'status' => array(
                'title' => 'Active',
                'align' => 'center',
                'status' => 'status',
                'class' => 'fixed-width-sm',
                'type' => 'bool',
                'orderby' => false
            ),

        );
        // create the helper list
        $helper = new HelperList();
        $helper->identifier = "id_eg_alertstock";
        $helper->shopLinkType = null;
        $helper->actions = array('delete');
        $helper->title = $this->displayName;
        $helper->table = $this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        return $helper->generateList($data, $list);
    }

    public function renderForm()
    {
        // Récupère la langue par défaut
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Initialise les champs du formulaire dans un tableau
        $form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Custom Button'),
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Custom button'),
                        'name' => 'CUSTOM_BTN', // Use the same key here
                        'size' => 20,
                        'required' => true
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name'  => 'btnSubmit'
                )
            ),
        );

        $helper = new HelperForm();

        // Module, token et currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Langue
        $helper->default_form_language = $defaultLang;

        $helper->fields_value['CUSTOM_BTN'] = Configuration::get('CUSTOM_BTN');

        return $helper->generateForm(array($form));
    }


    protected function getAllRecord()
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('eg_alertstock');
        return DB::getInstance()->executeS($sql);
    }

    public function hookDisplayProductActions($params)
    {
        $this->product = $params['product'];
    }

    public function hookDisplayReassurance()
    {
        if ($this->product['quantity'] < 1) {
            $this->context->smarty->assign(
                array(
                    'product' => $this->product,
                    'link' => $this->context->link->getModuleLink($this->name, 'alertsubmit', [], true),
                    'customBtn' => Configuration::get('CUSTOM_BTN'),
                )
            );
            return $this->display(__FILE__, 'views/templates/hook/egalertstock.tpl');
        }
    }

    public function hookActionUpdateQuantity($params)
    {
        $newQuantity = (int)$params['quantity'];

        if ($newQuantity > 0) {

            // Check if there are users subscribed to that product
            $subscribers = $this->getSubscribers();

            foreach ($subscribers as $subscriber) {
                // Send email to each subscriber
                $this->sendEmailToSubscriber($subscriber);
            }
        }
    }


    protected function sendEmailToSubscriber($subscriber)
    {
        $productId = (int)$subscriber['id_product'];
        $email = $subscriber['email'];
        $alertId = (int)$subscriber['id_eg_alertstock'];
        $subject = 'Product Back in Stock';
        $message = 'The product is back in stock.';
        if (mail($email, $subject, $message)) {
            // Delete the alert for this subscriber
            $this->deleteAlert($alertId);
        }
    }


    protected function getSubscribers()
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('eg_alertstock');

        return DB::getInstance()->executeS($sql);
    }

    protected function deleteAlert($alertId)
    {
        $egAlertStock = new EgAlertStockClass($alertId);
        $egAlertStock->delete();
    }


    public function hookHeader()
    {
        Media::addJsDef(
            [
                'moduleUrl' => $this->context->link->getModuleLink($this->name, 'alertsubmit')
            ]
        );
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
    }
}
