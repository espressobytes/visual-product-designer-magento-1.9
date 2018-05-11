<?php


class Espressolabs_Productconfigurator_Block_Adminhtml_Systemconfigsource_Colorfields extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

    
    public function __construct()
    {
        $this->addColumn('color_label', array(
            'label' => 'Color Label (as in individual option)',
            'style' => 'width:120px',
        ));
        $this->addColumn('color_sku', array(
            'label' => 'Color Sku (as in individual option)',
            'style' => 'width:120px',
        ));
        $this->addColumn('color_code', array(
            'label' => "Hex Color Code (starting with '#')",
            'style' => 'width:120px',
        ));
        $this->_addAfter = false;
                $this->_addButtonLabel = 'Add field';
        parent::__construct();
    }
}