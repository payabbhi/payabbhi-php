<?php
namespace Payabbhi;
use Payabbhi\Payment;
use Payabbhi\Payabbhi;
// use Payabbhi\PayabbhiTestCase;
use PHPUnit\Framework\TestCase;

class PaymentTest extends PayabbhiTestCase
{
        public function testAll()
        {
          $api = self::authorizeFromEnv();
          $a = $api->payment->all();
          $this->assertTrue(is_int($a->total_count));
          $this->assertTrue(is_string($a->object));
          $this->assertTrue(is_array($a->data));
          $this->assertEquals("list",($a->object));
        }

}
