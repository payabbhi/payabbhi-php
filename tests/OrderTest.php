<?php
namespace Payabbhi;
use Payabbhi\Order;
use Payabbhi\Payabbhi;
use PHPUnit\Framework\TestCase;

class OrderTest extends PayabbhiTestCase
{
        public function testAll()
        {
          $api = self::authorizeFromEnv();
          $a = $api->order->all();
          $this->assertTrue(is_int($a->total_count));
          $this->assertTrue(is_string($a->object));
          $this->assertTrue(is_array($a->data));
          $this->assertEquals("list",($a->object));
        }
}
