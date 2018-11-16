<?php
namespace Payabbhi;
use Payabbhi\Utility;
use PHPUnit\Framework\TestCase;

class UtilityTest extends TestCase
{
  public function testverifyPaymentSignature()
  {
    $api = new \Payabbhi\Client("BKa70BUicoKuQnZ6_ZSOJIWbi6nCO8mL83-4DJTcxdU=", "exq4JxHbTtMS9IM5hnoPNkdMXKW-ws36y4RgGtzGeGg=");
    $this->assertSame($api->utility->verifyPaymentSignature(array('payment_id'=>'pay_123','order_id'=>'order_test','payment_signature'=>'31b8de6a27e9911fe8828d80fda4c2c7cefdf332c06eeaa734a0708af6cafd87')),true);

  }

 public function testverifyPaymentSignatureError()
 {
    try {
      $api = new \Payabbhi\Client("BKa70BUicoKuQnZ6_ZSOJIWbi6nCO8mL83-4DJTcxdU=", "exq4JxHbTtMS9IM5hnoPNkdMXKW-ws36y4RgGtzGeGg=");
      $api->utility->verifyPaymentSignature(array('payment_id'=>'pay_123','order_id'=>'order_test','payment_signature'=>'wrong_signature'));
       $this->fail("Did not raise error");
     } catch (\Payabbhi\Error\SignatureVerification $e) {
        $this->assertEquals("Invalid signature",$e->getDescription());
        $this->assertEquals("message: Invalid signature\n",$e->getMessage());
        $this->assertEmpty($e->getField());
     }
   }

   public function testverifyWebhookSignature()
   {
     $payload = '{"event":"payment.captured"}';
     $t = time();
     $secret="skw_live_jHNxKsDqJusco5hA";
     $canonicalString = $payload . '&' . $t;
     $v1 = hash_hmac('sha256', $canonicalString, $secret);
     $actualSignature = "t=" . $t . ", v1=" . $v1;

     $api = new \Payabbhi\Client("BKa70BUicoKuQnZ6_ZSOJIWbi6nCO8mL83-4DJTcxdU=", "exq4JxHbTtMS9IM5hnoPNkdMXKW-ws36y4RgGtzGeGg=");
     $this->assertSame($api->utility->verifyWebhookSignature($payload,$actualSignature,$secret),true);
   }

   public function testverifyWebhookSignatureForReplayError()
   {
     try {
       $payload = '{"event":"payment.captured"}';
       $t = time() - 400;
       $secret="skw_live_jHNxKsDqJusco5hA";
       $canonicalString = $payload . '&' . $t;
       $v1 = hash_hmac('sha256', $canonicalString, $secret);
       $actualSignature = "t=" . $t . ", v1=" . $v1;

       $api = new \Payabbhi\Client("BKa70BUicoKuQnZ6_ZSOJIWbi6nCO8mL83-4DJTcxdU=", "exq4JxHbTtMS9IM5hnoPNkdMXKW-ws36y4RgGtzGeGg=");
       $api->utility->verifyWebhookSignature($payload,$actualSignature,$secret);
       $this->fail("Did not raise error");
     } catch (\Payabbhi\Error\SignatureVerification $e) {
        $this->assertEquals("Invalid signature",$e->getDescription());
        $this->assertEquals("message: Invalid signature\n",$e->getMessage());
        $this->assertEmpty($e->getField());
     }
   }

   public function testverifyWebhookSignatureError()
   {
    try {
      $payload = '{"event":"payment.captured"}';
      $actualSignature = "wrong signature";
      $secret="skw_live_jHNxKsDqJusco5hA";
      $api = new \Payabbhi\Client("BKa70BUicoKuQnZ6_ZSOJIWbi6nCO8mL83-4DJTcxdU=", "exq4JxHbTtMS9IM5hnoPNkdMXKW-ws36y4RgGtzGeGg=");
      $api->utility->verifyWebhookSignature($payload,$actualSignature,$secret);
      $this->fail("Did not raise error");
     } catch (\Payabbhi\Error\SignatureVerification $e) {
        $this->assertEquals("Invalid signature",$e->getDescription());
        $this->assertEquals("message: Invalid signature\n",$e->getMessage());
        $this->assertEmpty($e->getField());
     }
   }

   public function testverifySignatureError()
   {
      try {
        $api = new \Payabbhi\Client("BKa70BUicoKuQnZ6_ZSOJIWbi6nCO8mL83-4DJTcxdU=", "exq4JxHbTtMS9IM5hnoPNkdMXKW-ws36y4RgGtzGeGg=");
        $api->utility->verifySignature('pay_123&order_test', 'wrong_signature',"exq4JxHbTtMS9IM5hnoPNkdMXKW-ws36y4RgGtzGeGg=");
         $this->fail("Did not raise error");
       } catch (\Payabbhi\Error\SignatureVerification $e) {
          $this->assertEquals("Invalid signature",$e->getDescription());
          $this->assertEquals("message: Invalid signature\n",$e->getMessage());
          $this->assertEmpty($e->getField());
       }
    }

    public function testverifySignature()
    {
      $api = new \Payabbhi\Client("BKa70BUicoKuQnZ6_ZSOJIWbi6nCO8mL83-4DJTcxdU=", "exq4JxHbTtMS9IM5hnoPNkdMXKW-ws36y4RgGtzGeGg=");
      $this->assertSame($api->utility->verifySignature('pay_123&order_test', '31b8de6a27e9911fe8828d80fda4c2c7cefdf332c06eeaa734a0708af6cafd87',"exq4JxHbTtMS9IM5hnoPNkdMXKW-ws36y4RgGtzGeGg="),true);
    }

    public function testhashEqualsError()
    {
      $api = new \Payabbhi\Client("BKa70BUicoKuQnZ6_ZSOJIWbi6nCO8mL83-4DJTcxdU=", "exq4JxHbTtMS9IM5hnoPNkdMXKW-ws36y4RgGtzGeGg=");
      $this->assertFalse($api->utility->hashEquals(123,123));
    }

}

?>
