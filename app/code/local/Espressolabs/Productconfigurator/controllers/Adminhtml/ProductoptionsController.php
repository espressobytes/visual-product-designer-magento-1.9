<?php




class Espressolabs_Productconfigurator_Adminhtml_ProductoptionsController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction() {
        echo ("Hello World");
        $debug = true;
    }

    public function massOptionsGeneratorAction( $transportObj = false ) {
        $debug = $transportObj;
    }
}
