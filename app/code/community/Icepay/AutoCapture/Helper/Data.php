<?php

/**
 *  ICEPAY AutoCapture - Helper
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
class Icepay_AutoCapture_Helper_Data extends Mage_Core_Helper_Abstract {

    private $_version = '1.0.1';
    private $_minAdvancedVersion = '1.1.7';

    public function getCompatiblityVersion() {
        return $this->_minAdvancedVersion;
    }

    public function getVersion() {
        return $this->_version;
    }

    public function isCompatible() {
        return (Mage::Helper('iceadvanced')->version >= $this->_minAdvancedVersion);
    }
    
    public function isAutoCaptureActive($storeID) {        
        return (bool)Mage::getStoreConfig('icepay_autocapture/settings/active', $storeID);
    }
    
    public function isIcepayOrder($orderID) {
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderID);
        $paymentCode = $order->getPayment()->getMethodInstance()->getCode();

        return (strpos($paymentCode, 'icepayadv_') !== false) ? true : false;
    }
    
    public function doChecks() {
        $lines = array();

        $soapCheck = Mage::helper('icecore')->hasSOAP();
        array_push($lines, array(
            'line' => ($soapCheck) ? $this->__('SOAP webservices available') : $this->__('SOAP was not found on this server'),
            'result' => ($soapCheck) ? "ok" : "err"));

        $compatiblityCheck = $this->isCompatible();
        array_push($lines, array(
            'line' => ($compatiblityCheck) ? $this->__('Compatible with ICEPAY Advanced ') . Mage::helper('iceadvanced')->version : $this->__('Not compatible with ICEPAY Advanced ') . Mage::helper('iceadvanced')->version,
            'result' => ($compatiblityCheck) ? "ok" : "err"));

        return $lines;
    }
}