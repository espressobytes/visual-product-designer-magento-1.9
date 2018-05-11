<?php


class Espressolabs_Productconfigurator_Helper_Productoptions extends Mage_Core_Helper_Abstract
{
    public function __construct()
    {
        

        



    }

    public function getConfigValues() {
        $this->configMaxChars = Mage::getStoreConfig('esp_configurator_settings/option_generator/max_characters');
        $this->configPrice = Mage::getStoreConfig('esp_configurator_settings/option_generator/price');

                $fontsSerialized = Mage::getStoreConfig('esp_configurator_settings/fonts/configurator_fonts');
        $configFonts = $this->unserializeConfigValue($fontsSerialized, "font_sku");

                $generatorFontsString = Mage::getStoreConfig('esp_configurator_settings/option_generator/fonts');
        $generatorFontsString = str_replace(" ","",$generatorFontsString);
        $generatorFontsArr = explode(",",$generatorFontsString);

                $myConfigFonts = array();
        foreach ($generatorFontsArr as $myFont) {
            if (isset($configFonts[$myFont])) {
                $myConfigFonts[] = $configFonts[$myFont];
            }
        }
        $this->configFonts = $myConfigFonts;

                $colorsSerialized = Mage::getStoreConfig('esp_configurator_settings/colors/configurator_colors');
        $configColors = $this->unserializeConfigValue($colorsSerialized, "color_sku");

                $generatorColorsString = Mage::getStoreConfig('esp_configurator_settings/option_generator/colors');
        $generatorColorsString = str_replace(" ","",$generatorColorsString);
        $generatorColorsArr = explode(",",$generatorColorsString);

                $myConfigColors = array();
        foreach ($generatorColorsArr as $myColor) {
            if (isset($configColors[$myColor])) {
                $myConfigColors[] = $configColors[$myColor];
            }
        }
        $this->configColors = $myConfigColors;

    }

    private function unserializeConfigValue( $serializedConfigVal, $newKey ) {
        $configVal = unserialize($serializedConfigVal);
        $newValArr = array();
        foreach ($configVal as $valueArr) {
            foreach ($valueArr as $myKey => $myValue) {
                if ($myKey == $newKey) {
                    $newValArr[$myValue] = $valueArr;
                    break;
                }
            }
        }
        return $newValArr;
    }

    public function addOptionsToProductById( $productId ) {
        $product = Mage::getModel('catalog/product')->load($productId);
        $returnArray = $this->addOptionsToProduct($product);
        return $returnArray;
    }

    public function addOptionsToProduct( $product ) {

        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $productId = $product->getId();
        
        $existingOptions = array();
        if ( $product->getHasOptions() ) {

            foreach ($product->getOptions() as $myProductOption) {
                $title = $myProductOption->getTitle();
                $existingOptions[] = $title;
            }
        }

        
        $optionsToAdd = array( 'customize-text' );
        if (count($this->configFonts) > 0) {
            $optionsToAdd[] = 'customize-font';
        }
        if (count($this->configColors) > 0) {
            $optionsToAdd[] = 'customize-color';
        }

        $optionsAdded = array();
        $optionsAddedString = "";

        foreach ($optionsToAdd as $key => $optionToAdd) {
            if (!(in_array($optionToAdd,$existingOptions))) {
                $product->setHasOptions(true)->save();

                if ( $optionToAdd == 'customize-text' ) {

                    
                    $newOptionData = array(
                        'title' => $optionToAdd,
                        'type' => 'field',                         'is_require' => false,
                        'sort_order' => $key,
                        'values' => array(),
                        'max_characters' => $this->configMaxChars,
                        'price' => $this->configPrice,
                        'price_type'    => 'fixed'
                                                                    );
                } elseif ( $optionToAdd == 'customize-font' ) {

                    $valueArr = array();
                    foreach ( $this->configFonts as $fontId => $myFontDetails ) {
                        $valueArr[] = array(
                            'is_delete'     => 0,
                            'title'         => $myFontDetails['font_label'],
                            'price_type'    => 'fixed',
                            'price'         => 0,
                            'sku'           => $myFontDetails['font_sku'],
                            'option_type_id'=> -1,
                        );
                    }

                }  elseif ( $optionToAdd == 'customize-color' ) {

                    $valueArr = array();
                    foreach ( $this->configColors as $colorId => $myColorDetails ) {
                        $valueArr[] = array(
                            'is_delete'     => 0,
                            'title'         => $myColorDetails['color_label'],
                            'price_type'    => 'fixed',
                            'price'         => 0,
                            'sku'           => $myColorDetails['color_sku'],
                            'option_type_id'=> -1,
                        );
                    }

                }

                if ( in_array($optionToAdd, array('customize-font','customize-color')) ) {
                    $newOptionData = array(
                        'title' => $optionToAdd,
                        'type' => 'drop_down',                         'is_require' => false,
                        'sort_order' => $key,
                        'price_type' => 'fixed',
                        'values'     => $valueArr,
                    );
                }

                $adminStoreId = Mage_Core_Model_App::ADMIN_STORE_ID;
                $option = Mage::getModel('catalog/product_option')
                    ->setProductId( $productId )
                    ->setStoreId( $adminStoreId )
                    ->addData( $newOptionData );

                $option->save();
                $product->addOption($option);
                $product->save();

                $optionsAdded[] = $optionToAdd;
                $optionsAddedString .= "$optionToAdd;";

            }
        }

        if (sizeof($optionsAdded) > 0) {
            $returnArray = array(
                'success' => true,
                'message' => "$productId; options for configurable successfully added;$optionsAddedString",
                'productId' => $productId
            );
        } else {
            $returnArray = array(
                'success' => false,
                'message' => "no options added. They apparently already exist.",
                'productId' => $productId
            );
        }

        return $returnArray;

    }

    

}