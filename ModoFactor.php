<?php

class ModoFactor extends Module
{
    public $tabs = array(
        array(
            'name' => 'MODOPHAR', // One name for all langs
            'class_name' => 'AdminOrigin',
            'visible' => true,
            'parent_class_name' => 'SELL',
        ));

    public function __construct()
    {

        $this->name = 'ModoFactor';
        $this->author = 'JYF';
        $this->description = 'Un module pour gerer le front et le back office';
        $this->version = '1.0.0';
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('MODOFACTOR');

    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('displayHome')
            && $this->registerHook('ActionAdminProductsListingFieldsModifier')
            && $this->createTabLink()
            ;
    }

    public function uninstall()
    {
        return parent::uninstall()
            && $this->deleteTabLink()
            ;
    }

    public function hookDisplayHome()
    {
        return $this->display(__FILE__,"views/templates/hook/home.twig");
    }


    public function hookActionAdminProductsListingFieldsModifier($params)
    {
        /**
         * Rajout du fabricant
         */
//Champ sql
        $params['sql_select']['ean13'] = [
            'table' => 'st',
            'field' => 'ean13',
            'filtering' => \PrestaShop\PrestaShop\Adapter\Admin\AbstractAdminQueryBuilder::FILTERING_LIKE_BOTH
        ];
//Table sql
        $params['sql_table']['st'] = [
            'table' => 'stock',
            'join' => 'LEFT JOIN',
            'on' => 'p.id_product = st.id_product',
        ];

//Gestion du filtre, si un paramètre post est défini ( c'est le nom du champ dans le fichier displayAdminCatalogTwigProductFilter.tpl )
        $ean13_filter = Tools::getValue('filter_column_ean13',false);
        if ( $ean13_filter && $ean13_filter != '') {
            $params['sql_where'][] .= "p.ean13 =".$ean13_filter;
        }
    }

    public function createTabLink()
    {
        $tabId = (int) Tab::getIdFromClassName('AdminOrigin');
        if (!$tabId) {
            $tabId = null;
        }

        $tab = new Tab($tabId);
        $tab->active = 1;
        $tab->class_name = 'AdminOrigin';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'ModoFactor';
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('SELL');
        $tab->module = $this->name;

        return $tab->save();
    }

    private function deleteTabLink()
    {
        $tabId = (int) Tab::getIdFromClassName('AdminOrigin');
        if (!$tabId) {
            return true;
        }

        $tab = new Tab($tabId);
        return $tab->delete();
    }

}