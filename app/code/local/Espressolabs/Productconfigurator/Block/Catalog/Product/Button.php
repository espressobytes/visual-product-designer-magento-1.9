<?php


class Espressolabs_Productconfigurator_Block_Catalog_Product_Button extends Mage_Catalog_Block_Product_View
{

    protected function _beforeToHtml() {

        
        $this->ConfiguratorTitle = Mage::getStoreConfig('esp_configurator_settings/configurator_texts/title');
        $this->ConfiguratorTextBeforeInput = Mage::getStoreConfig('esp_configurator_settings/configurator_texts/before_customization_input');
        $this->ConfiguratorBtnTextDone = Mage::getStoreConfig('esp_configurator_settings/configurator_texts/btn_done');
        $this->ConfiguratorBtnTextReset = Mage::getStoreConfig('esp_configurator_settings/configurator_texts/btn_reset');

        $this->espcImagePosition = Mage::getStoreConfig('esp_configurator_settings/preview_image/default_image_position');
        if (!(is_numeric($this->espcImagePosition))) {
            $this->espcImagePosition = 99;
        }

                $this->defaultImageForCustomization = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'product-configurator/' . Mage::getStoreConfig('esp_configurator_settings/general/default_image');
        $this->CustomizationBgImageWidth = "450px";
        $this->CustomizationBgImageHeight = "450px";
        $this->PlaceholderText = Mage::getStoreConfig('esp_configurator_settings/texts/placeholder');

                $this->textBeforeCustomization = array(
            0 => Mage::getStoreConfig('esp_configurator_settings/texts/before_customization_btn_0'),
            1 => Mage::getStoreConfig('esp_configurator_settings/texts/before_customization_btn_1'),
        );
        $this->textForExtraPrice = Mage::getStoreConfig('esp_configurator_settings/texts/before_customization_extra_costs');

                $this->btnText = array(
            0 => Mage::getStoreConfig('esp_configurator_settings/texts/btn_text_1'),
            1 => Mage::getStoreConfig('esp_configurator_settings/texts/btn_text_2'),
        );

                $fontsSerialized = Mage::getStoreConfig('esp_configurator_settings/fonts/configurator_fonts');
        $this->configFonts = unserialize($fontsSerialized);

                $colorsSerialized = Mage::getStoreConfig('esp_configurator_settings/colors/configurator_colors');
        $this->configColors = unserialize($colorsSerialized);

        

        $this->CurrentCustomizationArr = NULL;
        $this->inputMaxCharacters = false;

        return parent::_beforeToHtml();
    }

    public function _toHtml() {

        $html = trim(parent::_toHtml());
        return $html;
    }

    public function getCustomizationTitle() {
        return $this->ConfiguratorTitle;
    }

    public function isProductCustomizable() {

        

        $currentProduct = $this->getProduct();
        $productOptions = $currentProduct->getOptions();

        foreach ($productOptions as $myOption) {
            if ($myOption->getTitle() == "customize-text") {
                $this->inputMaxCharacters = $myOption->getMaxCharacters();
                if ( ($myOption->getPriceType() == "fixed") AND ($myOption->getPrice() > 0) ) {
                    $this->extraPrice = $myOption->getPrice();
                } else {
                    $this->extraPrice = false;
                }
                                return true;
            }
        }

        return false;

    }

    public function getExtraPrice() {
        if (isset($this->extraPrice)) {
            return $this->extraPrice;
        } else {
            if ($this->isProductCustomizable()) {
                return $this->extraPrice;
            }
        }

        return false;
    }

    public function getInputMaxCharacters() {
        if ($this->inputMaxCharacters) {
            return $this->inputMaxCharacters;
        } else {
            if ($this->isProductCustomizable()) {
                return $this->inputMaxCharacters;
            }
        }

        return false;
    }

    public function getCustomizationImageUrl() {
        if (!(isset($this->CustomizationBgImage))) {
            $product = $this->getProduct();
            
            $this->CustomizationBgImage = false;

            $galleryData = $product->getData('media_gallery');

            
            foreach ($galleryData['images'] as $image) {
                if ($image['position'] == $this->espcImagePosition) {
                    $this->CustomizationBgImage = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . "catalog/product" . $image['file'];
                    break;
                }
            }

            if (!($this->CustomizationBgImage)) {
                $this->CustomizationBgImage = $this->defaultImageForCustomization;
            }

        }

        return $this->CustomizationBgImage;
    }

    public function getCustomizationBgImageSize() {
        return $this->CustomizationBgImageWidth . " " . $this->CustomizationBgImageHeight . ";";
    }

    public function getPlaceholderText() {
                $currentProduct = $this->getProduct();
        $placeholderTextAttrVal = $currentProduct->getData('espc_default_text');

        if ( (isset($placeholderTextAttrVal)) AND ($placeholderTextAttrVal != false) ) {
            return $placeholderTextAttrVal;
        }

        return $this->PlaceholderText;
    }

    public function getTextBeforeBtn($key) {
        $text = $this->textBeforeCustomization[$key];
        if ($key == 0) {
            if ($this->getExtraPrice() > 0) {
                $textExtraPrice = $this->textForExtraPrice;
                $formattedExtraPrice = Mage::helper('core')->currency($this->getExtraPrice(), true, false);
                $text .= " " . str_replace("[price]", $formattedExtraPrice, $textExtraPrice);
            }
        }
        return $text;
    }

    public function getCurrentCustomization() {
        if (!(is_null($this->CurrentCustomizationArr))) {
            return $this->CurrentCustomizationArr;
        } else {
            $currentProduct = $this->getProduct();
            $productOptions = $currentProduct->getOptions();
            $preConfiguredOptionValues = $currentProduct->getPreconfiguredValues()->getOptions();
            if (is_null($preConfiguredOptionValues)) {
                $this->CurrentCustomizationArr = false;
                                return $this->CurrentCustomizationArr;
            }
            $hasIndividualOptions = false;
            $individualOptionArr = array(
                'font' => "",
                'text' => ""
            );
            foreach ($productOptions as $myOption) {
                $myOptionId = $myOption->getId();
                if ($myOption->getTitle() == "customize-font") {
                    $optionValues = $myOption->getValues();
                    $selectedValue = $optionValues[$preConfiguredOptionValues[$myOptionId]];
                    $individualOptionArr['font'] = $selectedValue->getTitle();
                } elseif ($myOption->getTitle() == "customize-text") {
                    $individualOptionArr['text'] = $preConfiguredOptionValues[$myOptionId];
                    $hasIndividualOptions = true;
                }
            }
            if ($hasIndividualOptions) {
                                $this->CurrentCustomizationArr = $individualOptionArr;
            } else {
                                $this->CurrentCustomizationArr = false;
            }
            return $this->CurrentCustomizationArr;

        }

    }

    public function getFontTitles() {

        $currentProduct = $this->getProduct();
        $productOptions = $currentProduct->getOptions();

        $hasIndividualOptions = false;
        $optionValueArr = array();

        foreach ($productOptions as $myOption) {
            if ($myOption->getTitle() == "customize-font") {
                $optionValues = $myOption->getValues();
                foreach ($optionValues as $key => $myOptionValue) {
                                        $optionValueArr[$key] = array(
                        'title' => $myOptionValue->getTitle(),
                        'sku'   => $myOptionValue->getSku(),
                        'id'    => $myOptionValue->getOptionId()
                    );
                                        }
                $hasIndividualOptions = true;
            }
        }

        if ($hasIndividualOptions) {
            return $optionValueArr;
        } else {
            return false;
        }
    }

    public function getDefaultFontSku() {
        if (isset($this->DefaultFontSku)) {
            return $this->DefaultFontSku;
        }

        $fonts = $this->getFontTitles();
        $currentProduct = $this->getProduct();
        $defaultFontAttrVal = $currentProduct->getData('espc_default_font');

        if ($fonts) {
                        foreach ($fonts as $myFont) {
                if ($defaultFontAttrVal == $myFont['sku']) {
                    $this->DefaultFontSku = $defaultFontAttrVal;
                    return $defaultFontAttrVal;
                }
            }

                        $firstFont = reset($fonts);
            $this->DefaultFontSku = $firstFont['sku'];
            return $firstFont['sku'];

        } else {
                        $i = 0;
            $firstConfigFont = false;
            foreach ($this->configFonts as $myConfigFont) {
                if ($i == 0) {
                    $firstConfigFont = $myConfigFont['font_sku'];
                }
                if ($defaultFontAttrVal == $myConfigFont['font_sku']) {
                    $this->DefaultFontSku = $defaultFontAttrVal;
                    return $defaultFontAttrVal;
                }
                $i++;
            }

                        $this->DefaultFontSku = $firstConfigFont;
            return $firstConfigFont;
        }

        return false;
    }

    public function getFontColors() {

        $currentProduct = $this->getProduct();
        $productOptions = $currentProduct->getOptions();

        $hasIndividualOptions = false;
        $optionValueArr = array();

        foreach ($productOptions as $myOption) {
            if ($myOption->getTitle() == "customize-color") {
                $optionValues = $myOption->getValues();
                foreach ($optionValues as $key => $myOptionValue) {
                    
                                        $hexCode = $this->getHexColorCodeFromConfig( $myOptionValue->getTitle() );

                    $optionValueArr[$key] = array(
                        'title' => $myOptionValue->getTitle(),
                        'sku'   => $myOptionValue->getSku(),
                        'id'    => $myOptionValue->getOptionId(),
                        'hex'   => $hexCode
                    );
                                    }
                $hasIndividualOptions = true;
            }
        }

        if ($hasIndividualOptions) {
            return $optionValueArr;
        } else {
            return false;
        }

    }

    public function getDefaultColorSku() {

        if (isset($this->DefaultColorSku)) {
            return $this->DefaultColorSku;
        }

        $colors = $this->getFontColors();
        $currentProduct = $this->getProduct();
        $defaultColorAttrVal = $currentProduct->getData('espc_default_color');

        if ($colors) {
                        foreach ($colors as $myColor) {
                if ($defaultColorAttrVal == $myColor['sku']) {
                    $this->DefaultColorSku = $defaultColorAttrVal;
                    return $defaultColorAttrVal;
                }
            }

                        $firstColor = reset($colors);
            $this->DefaultColorSku = $firstColor['sku'];
            return $firstColor['sku'];

        } else {
                        $i = 0;
            $firstConfigColor = false;
            foreach ($this->configColors as $myConfigColor) {
                if ($i == 0) {
                    $firstConfigColor = $myConfigColor['color_sku'];
                }
                if ($defaultColorAttrVal == $myConfigColor['color_sku']) {
                    $this->DefaultColorSku = $defaultColorAttrVal;
                    return $defaultColorAttrVal;
                }
                $i++;
            }

                        $this->DefaultColorSku = $firstConfigColor;
            return $firstConfigColor;
        }

        return false;
    }

    public function getHexColorCodeFromConfig( $colorLabel ) {
        foreach ($this->configColors as $myColor) {
            if ($myColor['color_label'] == $colorLabel) {
                return $myColor['color_code'];
            }
        }
                return false;
    }


}









