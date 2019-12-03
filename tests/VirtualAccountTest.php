<?php
namespace Payabbhi;
use Payabbhi\VirtualAccount;
use Payabbhi\Payabbhi;
use PHPUnit\Framework\TestCase;

class VirtualAccountTest extends PayabbhiTestCase
{
        public function testAll()
        {
          $api = self::authorizeFromEnv();
          $a = $api->virtualaccount->all();
          $this->assertTrue(is_int($a->total_count));
          $this->assertTrue(is_string($a->object));
          $this->assertTrue(is_array($a->data));
          $this->assertEquals("list",($a->object));
        }
}
