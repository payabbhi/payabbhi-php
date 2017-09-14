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
        $this->assertEquals("Invalid signature passed",$e->getDescription());
        $this->assertEquals("message: Invalid signature passed\n",$e->getMessage());
        $this->assertEmpty($e->getField());
     }
   }

   public function testverifySignatureError()
   {
      try {
        $api = new \Payabbhi\Client("BKa70BUicoKuQnZ6_ZSOJIWbi6nCO8mL83-4DJTcxdU=", "exq4JxHbTtMS9IM5hnoPNkdMXKW-ws36y4RgGtzGeGg=");
        $api->utility->verifySignature('pay_123&order_test', 'wrong_signature');
         $this->fail("Did not raise error");
       } catch (\Payabbhi\Error\SignatureVerification $e) {
          $this->assertEquals("Invalid signature passed",$e->getDescription());
          $this->assertEquals("message: Invalid signature passed\n",$e->getMessage());
          $this->assertEmpty($e->getField());
       }
    }

    public function testverifySignature()
    {
      $api = new \Payabbhi\Client("BKa70BUicoKuQnZ6_ZSOJIWbi6nCO8mL83-4DJTcxdU=", "exq4JxHbTtMS9IM5hnoPNkdMXKW-ws36y4RgGtzGeGg=");
      $this->assertSame($api->utility->verifySignature('pay_123&order_test', '31b8de6a27e9911fe8828d80fda4c2c7cefdf332c06eeaa734a0708af6cafd87'),true);

    }

}

?>
