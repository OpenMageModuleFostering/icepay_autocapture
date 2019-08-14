<?php

/**
 *  ICEPAY AutoCapture - ModuleInfo
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
class Icepay_AutoCapture_Block_Adminhtml_Grid_ModuleInfo extends Mage_Adminhtml_Block_Widget implements Varien_Data_Form_Element_Renderer_Interface {

    public function __construct() {
        $this->setTemplate("icepayautocapture/module_information.phtml");

        Mage::app()->getLayout()
            ->getBlock('head')
            ->addCss('icepay/admin.css');
    }

    public function render(Varien_Data_Form_Element_Abstract $element) {
        $this->setElement($element);
        return $this->toHtml();
    }
}
