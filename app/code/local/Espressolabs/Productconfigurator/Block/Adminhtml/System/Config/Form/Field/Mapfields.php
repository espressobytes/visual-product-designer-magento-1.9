<?php


class Espressolabs_Productconfigurator_Block_Adminhtml_System_Config_Form_Field_Mapfields extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

    
    public function __construct()
    {
        $this->addColumn('var_code', array(
            'label' => 'Code',
            'style' => 'width:120px',
        ));
        $this->addColumn('var_label', array(
            'label' => 'Label',
            'style' => 'width:120px',
        ));
        $this->_addAfter = false;
                $this->_addButtonLabel = 'Add field';
        parent::__construct();
    }
}