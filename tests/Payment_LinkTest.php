<?php
namespace Payabbhi;
use Payabbhi\Payment_Link;
use Payabbhi\Payabbhi;
use PHPUnit\Framework\TestCase;

class Payment_LinkTest extends PayabbhiTestCase
{
        public function testAll()
        {
          $api = self::authorizeFromEnv();
          $a = $api->payment_link->all();
          $this->assertTrue(is_int($a->total_count));
          $this->assertTrue(is_string($a->object));
          $this->assertTrue(is_array($a->data));
          $this->assertEquals("list",($a->object));
        }
}
