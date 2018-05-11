<?php


class Espressolabs_Productconfigurator_Block_Checkout_Cart_Item_Renderer extends Mage_Checkout_Block_Cart_Item_Renderer
{

    public function getOptionList( $shortLabel = false ) {
        $optionList = parent::getOptionList();

        

        

        $isCustomized = false;

        

        

        $helper = Mage::helper('espressoconfigurator');

        foreach ($optionList as $key => $option) {
            $optionLabel = strtolower($option['label']);
            if ((strpos($optionLabel, "customize")) !== false) {
                if ( $optionLabel == "customize-text" ) {
                    $optionList[$key]['label'] = $helper->CheckoutTextYourLabel;
                    $isCustomized = true;
                } elseif( $optionLabel == "customize-font" ) {
                    $optionList[$key]['label'] = $helper->CheckoutTextYourFont;
                } elseif( $optionLabel == "customize-color" ) {
                    $optionList[$key]['label'] = $helper->CheckoutTextYourColor;
                }
            }
        }

        return $optionList;
    }

    public function getFormatedOptionValue($optionValue)
    {
        $formatedOptionValue = parent::getFormatedOptionValue($optionValue);
        return $formatedOptionValue;
    }



}





