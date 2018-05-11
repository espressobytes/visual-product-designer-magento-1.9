<?php


class Espressolabs_Productconfigurator_Helper_Data extends Mage_Core_Helper_Abstract
{

    

    public function __construct()
    {

                $this->CheckoutTextYourLabel = Mage::getStoreConfig('esp_configurator_settings/checkout_texts/custom_label');
        $this->CheckoutTextYourFont = Mage::getStoreConfig('esp_configurator_settings/checkout_texts/custom_font');
        $this->CheckoutTextYourColor = Mage::getStoreConfig('esp_configurator_settings/checkout_texts/custom_color');

    }

}