<?php
namespace Payabbhi;
use Payabbhi\Transfer;
use Payabbhi\Payabbhi;
use PHPUnit\Framework\TestCase;

class TransferTest extends PayabbhiTestCase
{
        public function testAll()
        {
          $api = self::authorizeFromEnv();
          $a = $api->transfer->all();
          $this->assertTrue(is_int($a->total_count));
          $this->assertTrue(is_string($a->object));
          $this->assertTrue(is_array($a->data));
          $this->assertEquals("list",($a->object));
        }
}
