<?php

/**
 *  ICEPAY AutoCapture - Settings Model
 * 
 *  @version 1.0.0
 *  @author Wouter van Tilburg <wouter@icepay.eu>
 *  @copyright ICEPAY <www.icepay.com>
 *  
 *  Disclaimer:
 *  The merchant is entitled to change de ICEPAY plug-in code,
 *  any changes will be at merchant's own risk.
 *  Requesting ICEPAY support for a modified plug-in will be
 *  charged in accordance with the standard ICEPAY tariffs.
 * 
 */
class Icepay_AutoCapture_Model_Settings extends Mage_Core_Model_Config_Data {
    
    protected function _afterLoad() {
        switch ($this->getPath()) {
            case 'icepay_autocapture/settings/merchant_id':
                $value = Mage::getStoreConfig('icecore/settings/merchant_id');
                break;
        }

        $this->setValue($value);
    }
    
    public function toOptionArray() {
        return array(
            array('value' => 1, 'label' => Mage::Helper('icepay_autocapture')->__('Yes')),
            array('value' => 0, 'label' => Mage::Helper('icepay_autocapture')->__('No'))
        );
    }
}
