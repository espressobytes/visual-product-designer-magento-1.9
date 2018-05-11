<?php



class Espressolabs_Productconfigurator_Block_Order_Item_Renderer_Default extends Mage_Sales_Block_Order_Item_Renderer_Default
{

    public function getItemOptions()
    {
        $result = parent::getItemOptions();

        $helper = Mage::helper('espressoconfigurator');

        foreach ($result as $myKey => $myOption) {
            if (isset($myOption['label'])) {
                if ($myOption['label'] == "customize-text") {
                    $result[$myKey]['label'] = $helper->CheckoutTextYourLabel;
                }
                if ($myOption['label'] == "customize-font") {
                    $result[$myKey]['label'] = $helper->CheckoutTextYourFont;
                }
                if ($myOption['label'] == "customize-color") {
                    $result[$myKey]['label'] = $helper->CheckoutTextYourColor;
                }
            }
        }

        return $result;
    }

}




