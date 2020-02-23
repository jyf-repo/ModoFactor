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