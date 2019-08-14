<?php

/**
 *  ICEPAY AutoCapture - Observer Model
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
class Icepay_AutoCapture_Model_Observer {

    public function sales_order_save_after(Varien_Event_Observer $observer) {        
        // Check if event is being triggered from the sales_order_shipment controller
        if (Mage::app()->getFrontController()->getRequest()->getControllerName() != 'sales_order_shipment')
            return;
        
        $order = $observer->getEvent()->getOrder();
        
        // Check if ICEPAY order
        if (!Mage::Helper('icepay_autocapture')->isIcepayOrder($order->getIncrementId()))
            return;
        
        $iceCoreModel = Mage::getModel('icecore/mysql4_iceCore');
        $ic_order = $iceCoreModel->loadPaymentByID($order->getIncrementId());
        
        $storeID = $ic_order['store_id'];
        
        // Check if auto capture is active
        if (!Mage::Helper('icepay_autocapture')->isAutoCaptureActive($storeID))
            return;

        $iceAdvancedModel = Mage::getModel('iceadvanced/mysql4_iceadvanced');
        $iceAdvancedModel->setScope($storeID);
        $paymentMethodData = $iceAdvancedModel->getPaymentMethodDataArrayByReference($ic_order['model']);

        if (empty($paymentMethodData)) {
            $iceAdvancedModel->setScope(0);
            $paymentMethodData = $iceAdvancedModel->getPaymentMethodDataArrayByReference($ic_order['model']);
        }
        
        // Check if payment method is Afterpay
        if ($paymentMethodData['pm_code'] != 'afterpay')
            return;
        
        $merchantID = Mage::getStoreConfig('icecore/settings/merchant_id', $storeID);
        $secretCode = Mage::getStoreConfig('icecore/settings/merchant_secret', $storeID);
        
        $service = Mage::getModel('Icepay_AutoCapture_Model_Webservice');
        $service->init($merchantID, $secretCode);
        
        $service->captureFull($ic_order['transaction_id']);
    }
}