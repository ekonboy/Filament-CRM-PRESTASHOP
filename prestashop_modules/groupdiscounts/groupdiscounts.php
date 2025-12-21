<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class GroupDiscounts extends Module
{
    public function __construct()
    {
        $this->name = 'groupdiscounts';
        $this->tab = 'pricing';
        $this->version = '1.0.0';
        $this->author = 'Your Company';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = 'Group Discounts';
        $this->description = 'Apply dynamic discounts based on customer groups from Laravel system';
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => '8.2.3');
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        // Register hooks
        $this->registerHook('actionProductPriceOverride');
        $this->registerHook('displayProductPriceBlock');
        $this->registerHook('displayHeader');

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return true;
    }

    /**
     * Hook: Override product price calculation
     */
    public function hookActionProductPriceOverride($params)
    {
        if (!isset($params['product']) || !isset($params['context'])) {
            return;
        }

        $product = $params['product'];
        $context = $params['context'];
        
        // Get current customer
        if (!$context->customer || !$context->customer->id) {
            return; // No customer logged in
        }

        $customerId = $context->customer->id;
        
        // Get customer's default group
        $groupId = $this->getCustomerGroupId($customerId);
        
        if (!$groupId) {
            return; // No group found
        }

        // Get discount rule from Laravel database
        $discountRule = $this->getGroupDiscountRule($groupId);
        
        if (!$discountRule || $discountRule['status'] !== 'active') {
            return; // No active discount rule
        }

        $originalPrice = $params['price'];
        $discountPercentage = (float) $discountRule['discount_percentage'];
        
        // Apply discount
        $discountedPrice = $originalPrice * (1 - ($discountPercentage / 100));
        
        // Return the discounted price
        return $discountedPrice;
    }

    /**
     * Hook: Display discount info in product price block
     */
    public function hookDisplayProductPriceBlock($params)
    {
        if (!isset($params['product']) || !isset($this->context)) {
            return;
        }

        $product = $params['product'];
        $context = $this->context;
        
        if (!$context->customer || !$context->customer->id) {
            return;
        }

        $customerId = $context->customer->id;
        $groupId = $this->getCustomerGroupId($customerId);
        
        if (!$groupId) {
            return;
        }

        $discountRule = $this->getGroupDiscountRule($groupId);
        
        if (!$discountRule || $discountRule['status'] !== 'active') {
            return;
        }

        $this->context->smarty->assign(array(
            'discount_percentage' => $discountRule['discount_percentage'],
            'group_name' => $discountRule['group_name']
        ));

        return $this->display(__FILE__, 'views/templates/hook/product_price_block.tpl');
    }

    /**
     * Hook: Add CSS/JS to header
     */
    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path . 'views/css/groupdiscounts.css');
    }

    /**
     * Get customer's default group ID
     */
    private function getCustomerGroupId($customerId)
    {
        $sql = 'SELECT id_default_group FROM ' . _DB_PREFIX_ . 'customer WHERE id_customer = ' . (int)$customerId;
        return Db::getInstance()->getValue($sql);
    }

    /**
     * Get group discount rule from Laravel database
     */
    private function getGroupDiscountRule($groupId)
    {
        // Connect to Laravel database (configure these settings)
        $laravelDb = array(
            'server' => 'localhost',  // Your Laravel DB host
            'user' => 'root',        // Your Laravel DB user
            'password' => '',        // Your Laravel DB password
            'database' => 'dbd2alo7phrndx' // Your Laravel DB name
        );

        try {
            // Create connection to Laravel DB
            $link = mysqli_connect($laravelDb['server'], $laravelDb['user'], $laravelDb['password'], $laravelDb['database']);
            
            if (!$link) {
                return null;
            }

            // Query Laravel database
            $sql = "SELECT id_group, group_name, discount_percentage, status 
                    FROM group_discount_rules 
                    WHERE id_group = " . (int)$groupId . " AND status = 'active' 
                    LIMIT 1";
            
            $result = mysqli_query($link, $sql);
            
            if ($row = mysqli_fetch_assoc($result)) {
                mysqli_close($link);
                return $row;
            }
            
            mysqli_close($link);
            return null;
            
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get module configuration page content
     */
    public function getContent()
    {
        $output = '';

        // Save configuration
        if (Tools::isSubmit('submit' . $this->name)) {
            $laravel_db_host = Tools::getValue('LARAVEL_DB_HOST');
            $laravel_db_user = Tools::getValue('LARAVEL_DB_USER');
            $laravel_db_pass = Tools::getValue('LARAVEL_DB_PASS');
            $laravel_db_name = Tools::getValue('LARAVEL_DB_NAME');

            Configuration::updateValue('LARAVEL_DB_HOST', $laravel_db_host);
            Configuration::updateValue('LARAVEL_DB_USER', $laravel_db_user);
            Configuration::updateValue('LARAVEL_DB_PASS', $laravel_db_pass);
            Configuration::updateValue('LARAVEL_DB_NAME', $laravel_db_name);

            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        // Display configuration form
        $output .= $this->displayForm();

        return $output;
    }

    /**
     * Display configuration form
     */
    public function displayForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Laravel Database Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Database Host'),
                        'name' => 'LARAVEL_DB_HOST',
                        'desc' => $this->l('Laravel database hostname'),
                        'required' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Database User'),
                        'name' => 'LARAVEL_DB_USER',
                        'desc' => $this->l('Laravel database username'),
                        'required' => true
                    ),
                    array(
                        'type' => 'password',
                        'label' => $this->l('Database Password'),
                        'name' => 'LARAVEL_DB_PASS',
                        'desc' => $this->l('Laravel database password')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Database Name'),
                        'name' => 'LARAVEL_DB_NAME',
                        'desc' => $this->l('Laravel database name'),
                        'required' => true
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submit' . $this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        // Load current values
        $helper->fields_value['LARAVEL_DB_HOST'] = Configuration::get('LARAVEL_DB_HOST', 'localhost');
        $helper->fields_value['LARAVEL_DB_USER'] = Configuration::get('LARAVEL_DB_USER', 'root');
        $helper->fields_value['LARAVEL_DB_PASS'] = Configuration::get('LARAVEL_DB_PASS');
        $helper->fields_value['LARAVEL_DB_NAME'] = Configuration::get('LARAVEL_DB_NAME', 'dbd2alo7phrndx');

        return $helper->generateForm(array($fields_form));
    }
}
