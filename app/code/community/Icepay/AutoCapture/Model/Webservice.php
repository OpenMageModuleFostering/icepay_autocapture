<?php

/**
 *  ICEPAY AutoCapture - Webservice Model
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
class Icepay_AutoCapture_Model_Webservice {
    
    private $_client;
    private $_merchantID;
    private $_secretCode;
    private $_url = 'https://connect.icepay.com/webservice/APCapture.svc?wsdl';
    
    public function init($merchantID, $secretCode) {
        $this->_merchantID = (int) $merchantID;
        $this->_secretCode = (string) $secretCode;
        
        $this->_client = new SoapClient($this->_url, array('cache_wsdl' => 'WSDL_CACHE_NONE', 'encoding' => 'utf-8'));
    }
    
    public function captureFull($paymentID, $amount = 0, $currency = '') {
        $obj = new stdClass();
        
        $obj->MerchantID = $this->_merchantID;
        $obj->Timestamp = gmdate("Y-m-d\TH:i:s\Z");
        $obj->amount = $amount;
        $obj->currency = $currency;
        $obj->PaymentID = $paymentID;
        
        $obj->Checksum = $this->generateChecksum($obj);
        
        try {
            $this->_client->CaptureFull($obj);
            Mage::Helper('icecore')->log(sprintf(__('[AutoCapture] Captured payment: #%s'), $paymentID));
        } catch (Exception $e) {
            Mage::Helper('icecore')->log(sprintf(__('[AutoCapture] Failed to capture payment: #%s'), $e->getMessage()));
        }
    }
    
    private function generateChecksum($obj) {
        $arr = array();
        array_push($arr, $this->_secretCode);
        
        foreach ($obj as $val) {
            array_push($arr, $val);
        }
        
        return sha1(implode('|', $arr));
    }
}