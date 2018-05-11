<?php

Mage::log( "Starting setup for Version 1.0.0.0 ..." , Zend_Log::INFO, 'espressoconfigurator_log', true);

$installer = $this;
$installer->startSetup();





$AttributeHelper = Mage::helper('espressoconfigurator/attributehelper');
$AttributeHelper->createAllConfiguratorAttributes();
$AttributeHelper->assignAttributesToAllAttributeSets();

$installer->endSetup();

Mage::log( "Installation finished!" , Zend_Log::INFO, 'espressoconfigurator_log', true);







