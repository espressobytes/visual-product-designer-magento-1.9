<?php




class Espressolabs_Productconfigurator_Block_Order_Email_Items_Default extends Mage_Sales_Block_Order_Email_Items_Default
{
    

    public function getItemOptions()
    {
        $result = parent::getItemOptions();

        foreach ($result as $myKey => $myOption) {
            if (isset($myOption['label'])) {
                if ($myOption['label'] == "customize-text") {
                    $result[$myKey]['label'] = "Ihr persönliches Etikett";
                }
                if ($myOption['label'] == "customize-font") {
                    $result[$myKey]['label'] = "Ausgewählte Schriftart";
                }
            }
        }

        return $result;
    }

}



