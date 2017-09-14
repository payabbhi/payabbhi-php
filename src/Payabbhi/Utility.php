<?php

namespace Payabbhi;
use Payabbhi\Error;

class Utility
{
    const SHA256 = 'sha256';

    public function verifyPaymentSignature($paymentResponse)
    {
        $expectedSignature = $paymentResponse['payment_signature'];
        $orderId = $paymentResponse['order_id'];
        $paymentId = $paymentResponse['payment_id'];

        $payload = $paymentId . '&' . $orderId;
        return self::verifySignature($payload, $expectedSignature);
    }

    public function verifySignature($payload, $expectedSignature)
    {
        $actualSignature = hash_hmac(self::SHA256, $payload, Client::getSecretKey());

        if (($this->hashEquals($actualSignature, $expectedSignature)) === false)
        {
            throw new Error\SignatureVerification('Invalid signature passed');
        }
        return true;
    }

    private function hashEquals($actualSignature, $expectedSignature)
    {
      if (!is_string($actualSignature) || !is_string($expectedSignature)) {
           return false;
       }

       if (strlen($actualSignature) !== strlen($expectedSignature)) {
           return false;
       }

       $status = 0;
       for ($i = 0; $i < strlen($actualSignature); $i++) {
           $status |= ord($actualSignature[$i]) ^ ord($expectedSignature[$i]);
       }
       return $status === 0;
    }
}
