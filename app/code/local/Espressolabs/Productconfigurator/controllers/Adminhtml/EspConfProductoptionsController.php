<?php


class Espressolabs_Productconfigurator_Adminhtml_EspConfProductoptionsController extends Mage_Adminhtml_Controller_Action
{

    public function massOptionsGeneratorAction( ) {

        $productOptionsHelper = Mage::helper('espressoconfigurator/productoptions');
        $validationResult = $productOptionsHelper->getConfigValues();

        $productIdArr  = $this->getRequest()->getParam('product');

        $successArr = array();
        $errorArr = array();

        foreach ($productIdArr as $productId) {
            $addOptionResult = $productOptionsHelper->addOptionsToProductById( $productId );
            if ($addOptionResult['success']) {
                $successArr[] = $productId;
            } else {
                $errorArr[] = $productId;
            }
        }

        if ( count($successArr) > 0 ) {
            $nSuccess = count($successArr);
            Mage::getSingleton('adminhtml/session')->addSuccess("Options for customization added for $nSuccess product(s).");
        }
        if ( count($errorArr) > 0 ) {
            $nError = count($errorArr);
            Mage::getSingleton('adminhtml/session')->addError("Adding customization options failed for $nError product(s).");
        }

        $this->_redirect('*/catalog_product');

    }
}


