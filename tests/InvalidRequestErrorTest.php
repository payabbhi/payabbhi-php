<?php

namespace Payabbhi;
use Payabbhi\Payment;
use Payabbhi\Error\ErrorCode;
use Payabbhi\HttpClient\CurlClient;
use PHPUnit\Framework\TestCase;

class InvalidRequestErrorTest extends PayabbhiTestCase
{
    public function testBadData()
    {
     try {
          $api = self::authorizeFromEnv();
          $api->payment->retrieve("abcd")->refund();
       } catch (\Payabbhi\Error\InvalidRequest $e) {
         $this->assertEquals(400, $e->getHttpStatus());
         $this->assertEquals("Invalid value provided in field",$e->getDescription());
         $this->assertEquals("message: Invalid value provided in field, http_code: 400, field: payment_id\n",$e->getMessage());
         $this->assertEquals("payment_id", $e->getField());
      }
    }
}
