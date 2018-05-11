<?php

class Espressolabs_Productconfigurator_Helper_Attributehelper extends Mage_Core_Helper_Abstract
{

    public function __construct()
    {
        $this->InstallationAttributesText = array(
            array('code' => 'espc_default_text', 'label' => 'Placeholder Text', 'note' => 'If nothing is set up here, the placeholder text in config will be used.'),
            array('code' => 'espc_default_font', 'label' => 'Default font', 'note' => 'Use the font-skus defined in System -> Configuration -> Visual Product Designer -> Fonts. If no default font is choosen, then the first available font is used.'),
            array('code' => 'espc_default_color', 'label' => 'Default color', 'note' => 'Use the color-skus defined in System -> Configuration -> Visual Product Designer -> Colors. If no default font is choosen, then the first available color is used.'),
        );
        $this->ArrtGroup = array(
            'name' => 'Visual Product Designer',
            'order' => 99
        );
    }

    public function createAllConfiguratorAttributes() {

        foreach ($this->InstallationAttributesText as $myAttr) {
            $this->createAttribute( $myAttr['code'], $myAttr['label'], $myAttr['note'], 'text' );
        }

        $this->assignAttributesToAllAttributeSets();
    }

    public function createAttribute( $attrCode, $attrLabel, $attrNote, $attrType, $product_type = -1 , $default_boolean = 0 )
    {
        

        if ($product_type == -1) {
            $product_type = '';         }

        $_attribute_data = array(
            'attribute_code' => $attrCode,
            'is_global' => '1',
            'frontend_input' => $attrType,
            'default_value_text' => '',
            'default_value_yesno' => $default_boolean,
            'default_value_date' => '',
            'default_value_textarea' => '',
            'is_unique' => '0',
            'is_required' => '0',
            'apply_to' => array($product_type),             'is_configurable' => '0',
            'is_searchable' => '0',
            'is_visible_in_advanced_search' => '0',
            'is_comparable' => '0',
            'is_used_for_price_rules' => '0',
            'is_wysiwyg_enabled' => '0',
            'is_html_allowed_on_front' => '0',
            'is_visible_on_front' => '0',
            'used_in_product_listing' => '0',
            'used_for_sort_by' => '0',
            'frontend_label' => array($attrLabel),
            'note' => $attrNote,
        );
        $defaultValueField = "";

        $model = Mage::getModel('catalog/resource_eav_attribute');
        
        if (is_null($model->getIsUserDefined()) || $model->getIsUserDefined() != 0) {
            $_attribute_data['backend_type'] = $model->getBackendTypeByInput($_attribute_data['frontend_input']);
        }
        
        if ($defaultValueField) {
                        $_attribute_data['default_value'] = $defaultValueField;
        }
        $model->addData($_attribute_data);
        $model->setEntityTypeId(Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId());
        $model->setIsUserDefined(1);

        try {
            $model->save();
            Mage::log( "Attribute created. Code: $attrCode, Label: $attrLabel, Type: $attrType" , Zend_Log::INFO, 'espressoconfigurator_log', true);
        } catch (Exception $e) {
            Mage::log( "Error occurred while trying to save the attribute. Exception: " . $e->getMessage() , Zend_Log::INFO, 'espressoconfigurator_log', true);
        }
    }

    public function assignAttributesToAllAttributeSets( ) {

                $attributesToGroup = array();
        foreach ($this->InstallationAttributesText as $myAttr) {
            $attributesToGroup[] = $myAttr['code'];
        }

        $myGroupName = $this->ArrtGroup['name'];

        foreach ($attributesToGroup as $SortOrder => $myAttribute) {
            $mySortOrder = 100 + $SortOrder;
            $attSet = Mage::getModel('eav/entity_type')->getCollection()->addFieldToFilter('entity_type_code','catalog_product')->getFirstItem();             $attSetCollection = Mage::getModel('eav/entity_type')->load($attSet->getId())->getAttributeSetCollection(); 
            $attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setCodeFilter($myAttribute)
                ->getFirstItem();
            $attCode = $attributeInfo->getAttributeCode();

            $attId = $attributeInfo->getId();
            foreach ($attSetCollection as $a)
            {
                $set = Mage::getModel('eav/entity_attribute_set')->load($a->getId());
                $setId = $set->getId();

                                $group = Mage::getModel('eav/entity_attribute_group')
                    ->getCollection()
                    ->addFieldToFilter('attribute_set_id',$setId);
                $group->addFieldToFilter('attribute_group_name',$myGroupName);
                $group->getFirstItem();
                $groupData = $group->getData();
                if (isset($groupData[0])) {
                    $groupId = $groupData[0]['attribute_group_id'];
                } else {
                    $groupId = false;
                }

                if ( intval ($groupId) > 0) {
                                    } else {
                                        $modelGroup = Mage::getModel('eav/entity_attribute_group');
                                        $modelGroup->setAttributeGroupName($myGroupName)                         ->setAttributeSetId($setId)
                        ->setSortOrder($this->ArrtGroup['order']);
                    $modelGroup->save();

                    $group = Mage::getModel('eav/entity_attribute_group')
                        ->getCollection()
                        ->addFieldToFilter('attribute_set_id',$setId);
                    $group->addFieldToFilter('attribute_group_name',$myGroupName);
                    $group->getFirstItem();
                    $groupData = $group->getData();
                    $groupId = $groupData[0]['attribute_group_id'];
                }

                                $newItem = Mage::getModel('eav/entity_attribute');
                $newItem->setEntityTypeId($attSet->getId())                     ->setAttributeSetId($setId)                     ->setAttributeGroupId($groupId)                     ->setAttributeId($attId)                     ->setSortOrder($mySortOrder)                     ->save()
                ;
                Mage::log( "Attribute ".$attCode." Added to Attribute Set ".$set->getAttributeSetName()." in Attribute Group ID: ".$groupId , Zend_Log::INFO, 'espressoconfigurator_log', true);
            }
        }
    }

}













