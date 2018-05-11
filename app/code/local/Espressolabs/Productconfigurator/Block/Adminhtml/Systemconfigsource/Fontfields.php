<?php


class Espressolabs_Productconfigurator_Block_Adminhtml_Systemconfigsource_Fontfields extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

    
    public function __construct()
    {
        $this->addColumn('font_label', array(
            'label' => 'Font label (as in individual option)',
            'style' => 'width:120px',
        ));
        $this->addColumn('font_sku', array(
            'label' => 'Font Sku (as in individual option)',
            'style' => 'width:120px',
        ));
        $this->addColumn('font_family', array(
            'label' => "Css font-family (as defined in fonts.css or alternatives)",
            'style' => 'width:280px',
        ));
        $this->_addAfter = false;
                $this->_addButtonLabel = 'Add field';
        parent::__construct();
    }
}