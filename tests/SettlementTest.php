<?php
namespace Payabbhi;
use Payabbhi\Settlement;
use Payabbhi\Payabbhi;
use PHPUnit\Framework\TestCase;

class SettlementTest extends PayabbhiTestCase
{
        public function testAll()
        {
          $api = self::authorizeFromEnv();
          $a = $api->settlement->all();
          $this->assertTrue(is_int($a->total_count));
          $this->assertTrue(is_string($a->object));
          $this->assertTrue(is_array($a->data));
          $this->assertEquals("list",($a->object));
        }
}
