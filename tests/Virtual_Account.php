<?php
namespace Payabbhi;
use Payabbhi\Virtual_Account;
use Payabbhi\Payabbhi;
use PHPUnit\Framework\TestCase;

class Virtual_AccountTest extends PayabbhiTestCase
{
        public function testAll()
        {
          $api = self::authorizeFromEnv();
          $a = $api->virtual_account->all();
          $this->assertTrue(is_int($a->total_count));
          $this->assertTrue(is_string($a->object));
          $this->assertTrue(is_array($a->data));
          $this->assertEquals("list",($a->object));
        }
}
