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
class Icepay_AutoCapture_Model_Webservice_AutoCapture extends Icepay_IceCore_Model_Webservice_Core {

    protected $serviceURL = 'https://connect.icepay.com/webservice/APCapture.svc?wsdl';
    
    public function captureFull($paymentID, $amount = 0, $currency = '') {
        $obj = new stdClass();
        
        $obj->MerchantID = $this->merchantID;
        $obj->Timestamp = $this->getTimestamp();
        $obj->amount = $amount;
        $obj->currency = $currency;
        $obj->PaymentID = $paymentID;
        
        $obj->Checksum = $this->generateChecksum($obj, $this->secretCode);
        
        try {
            $this->client->CaptureFull($obj);
            Mage::Helper('icecore')->log(sprintf(__('[AutoCapture] Captured payment: #%s'), $paymentID));
        } catch (Exception $e) {
            Mage::Helper('icecore')->log(sprintf(__('[AutoCapture] Failed to capture payment: #%s'), $e->getMessage()));
        }
    }
}