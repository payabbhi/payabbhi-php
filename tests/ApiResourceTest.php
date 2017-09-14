<?php
namespace Payabbhi;
use Payabbhi\Payabbhi;
use Payabbhi\ApiResource;
use Payabbhi\Error\ErrorCode;
use PHPUnit\Framework\TestCase;

class ApiResourceTest extends TestCase
{
        public function testClassName()
        {
          $result = ApiResource::className();
          $this->assertEquals("apiresource", $result);
        }

        public function testClassUrl()
        {
          $result = ApiResource::classUrl();
          $this->assertEquals("/api/v1/apiresources", $result);
        }

        public function testBadParams()
        {
          try {
            ApiResource::_validateParams("123");
          } catch (\Payabbhi\Error\InvalidRequest $e) {
            $message = "You must pass an array as the first argument to Payabbhi API method calls.";
            $this->assertNull($e->getHttpStatus());
            $this->assertEquals($message,$e->getDescription());
            $this->assertEquals("message: You must pass an array as the first argument to Payabbhi API method calls.\n",$e->getMessage());
            $this->assertEmpty($e->getField());
          }
       }

       public function testArrayParams()
       {
         $result = ApiResource::_validateParams(Array("123"));
         $this->assertNull($result);
       }


}
