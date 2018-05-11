<?php

class Espressolabs_Productconfigurator_IndexController extends Mage_Core_Controller_Front_Action {

    public function testModuleAction() {
                $this->getResponse()->setBody("test model... time is: " . time());

    }

    public function debugMe($msg) {
        echo ($msg . "<br>");
    }

    public function addOptionsToProductAction() {
                $this->debugMe(__METHOD__);

        $parameter = Mage::app()->getRequest()->getParams();
        $productId = $parameter['id'];
        $addOptionResult = Mage::helper('espressoconfigurator/productoptions')->addOptionsToProductById( $productId );

        $this->debugMe("done!");
    }

    public function addOptionsToAllMuehlenAction() {
        
        $productCollection = Mage::getModel('catalog/product')->getCollection();
        $productCollection->addFieldToFilter('unit_violas', array( array( 'eq' => 6 ) ));

        foreach ($productCollection as $myProduct ) {
            $productId = $myProduct->getId();
                        $addOptionResult = Mage::helper('espressoconfigurator/productoptions')->addOptionsToProductById( $productId );
            $this->debugMe("$productId --> Option added");
        }

        $this->debugMe("found " . $productCollection->count() . " products");

    }

}