<?php
namespace Payabbhi;
use Payabbhi\Product;
use Payabbhi\Payabbhi;
use PHPUnit\Framework\TestCase;

class PlanTest extends PayabbhiTestCase
{
        public function testAll()
        {
          $api = self::authorizeFromEnv();
          $a = $api->plan->all();
          $this->assertTrue(is_int($a->total_count));
          $this->assertTrue(is_string($a->object));
          $this->assertTrue(is_array($a->data));
          $this->assertEquals("list",($a->object));
        }
}
