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

    public function testBadURLPath()
    {
      try {
        $api = self::authorizeFromEnv();
        CurlClient::instance()->request("/api/v2/payments","GET",null);
      } catch (\Payabbhi\Error\InvalidRequest $e) {
        $this->assertEquals(404, $e->getHttpStatus());
        $this->assertEquals("Request URL GET /api/v2/payments does not exist. Please check documentation",$e->getDescription());
        $this->assertEquals("message: Request URL GET /api/v2/payments does not exist. Please check documentation, http_code: 404\n",$e->getMessage());
        $this->assertEmpty($e->getField());
      }
    }
}
