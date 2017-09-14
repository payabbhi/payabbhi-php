<?php

namespace Payabbhi;
use Payabbhi\Payment;
use PHPUnit\Framework\TestCase;

class AuthenticationErrorTest extends TestCase
{
    public function testInvalidCredentials()
    {
      $api = new \Payabbhi\Client("wrong_access_id","wrong_secret_key");
     try {
        $api->payment->all();
        $this->fail("Did not raise error");
       } catch (\Payabbhi\Error\Authentication $e) {
         $this->assertEquals(401, $e->getHttpStatus());
         $this->assertEquals("Incorrect access_id or secret_key provided",$e->getDescription());
         $this->assertEquals("message: Incorrect access_id or secret_key provided, http_code: 401\n",$e->getMessage());
         $this->assertEmpty($e->getField());
      }
    }
}
