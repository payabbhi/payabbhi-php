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
        return self::verifySignature($payload, $expectedSignature, Client::getSecretKey());
    }

    public function verifySignature($payload, $expectedSignature, $secret)
    {
        $actualSignature = hash_hmac(self::SHA256, $payload, $secret);
        if (($this->hashEquals($actualSignature, $expectedSignature)) === false)
        {
            throw new Error\SignatureVerification('Invalid signature');
        }
        return true;
    }

    public function verifyWebhookSignature($payload, $actualSignature, $secret, $replayInterval = 300)
    {
      $partial = explode(',', $actualSignature);
      $final = array();
      array_walk($partial, function($val,$key) use(&$final){
        list($key, $value) = array_pad(explode('=', trim($val),2),2,null);
        $final[$key] = $value;
      });

      if (array_key_exists("v1", $final) == false  || array_key_exists("t", $final) == false
        || time() - (int)$final["t"] > $replayInterval)
      {

        throw new Error\SignatureVerification('Invalid signature');
      }
      $canonicalString  = $payload . '&' . $final["t"];
      return self::verifySignature($canonicalString, $final["v1"], $secret);
    }

    public function hashEquals($actualSignature, $expectedSignature)
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
