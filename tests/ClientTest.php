<?php
namespace Payabbhi;
use Payabbhi\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
        public function testBaseUrl()
        {
          $result = \Payabbhi\Client::baseUrl();
          \Payabbhi\Client::$apiBase = "https://payabbhitest.com";
          $result = \Payabbhi\Client::baseUrl();
          $this->assertEquals("https://payabbhitest.com", $result);
          \Payabbhi\Client::$apiBase = $result;
        }

        public function testAccessID()
        {
          $api = new \Payabbhi\Client("test_access_id","test_secret_key");
          $this->assertEquals("test_access_id",\Payabbhi\Client::getAccessID());
        }

        public function testSecretKey()
        {
          $api = new \Payabbhi\Client("test_access_id","test_secret_key");
          $this->assertEquals("test_secret_key",\Payabbhi\Client::getSecretKey());
        }

        /**
        * @dataProvider AppInfoProvider
        */
        public function testAppInfo($name,$version,$url)
        {
          $api = new \Payabbhi\Client("test_access_id","test_secret_key");
          $api->setAppInfo($name , $version, $url);
          $info = \Payabbhi\Client::getAppInfo();

          $this->assertEquals($name, $info['name']);
          $this->assertEquals($version, $info['version']);
          $this->assertEquals($url, $info['url']);

        }

        public function AppInfoProvider()
        {
          return [
              ["TestPayabbhiApplication", null, null],
              ["TestPayabbhiApplication","beta",null],
              ["TestPayabbhiApplication" , "beta", "https://testpayabbhiapplication.com"]
          ];
        }
}
