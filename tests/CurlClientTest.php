<?php
namespace Payabbhi;
use Payabbhi\HttpClient\CurlClient;
use Payabbhi\Error\Api;
use Payabbhi\Error\InvalidRequest;
use Payabbhi\Error\Authentication;
use PHPUnit\Framework\TestCase;

class CurlClientTest extends TestCase
{
      public function testTimeout()
      {
          $curl = new CurlClient();
          $this->assertSame(CurlClient::DEFAULT_TIMEOUT, $curl->getTimeout());
          $this->assertSame(CurlClient::DEFAULT_CONNECT_TIMEOUT, $curl->getConnectTimeout());
          $curl = $curl->setConnectTimeout(1)->setTimeout(10);
          $this->assertSame(1, $curl->getConnectTimeout());
          $this->assertSame(10, $curl->getTimeout());
          $curl->setTimeout(-1);
          $curl->setConnectTimeout(-999);
          $this->assertSame(0, $curl->getTimeout());
          $this->assertSame(0, $curl->getConnectTimeout());
          $curl->setTimeout("a");
          $curl->setConnectTimeout("b");
          $this->assertSame(0, $curl->getTimeout());
          $this->assertSame(0, $curl->getConnectTimeout());

      }

      public function testInvalidHTTPMethod()
      {
        try {
          $method= "GETS";
          CurlClient::instance()->request("https://www.google.com",$method,null);
        } catch (Api $e){
          $msg = 'Unexpected Method: ' . $method;
          $this->assertNull($e->getHttpStatus());
          $this->assertEquals($msg, $e->getDescription());
          $this->assertEquals("message: $msg, field: method\n", $e->getMessage());
          $this->assertEquals("method", $e->getField());
        }

      }

      public function testUrlForGETRequestWithParams()
      {
        $ch = curl_init();
        $base = \Payabbhi\Client::baseUrl();
        \Payabbhi\Client::$apiBase = "https://payabbhi.com";
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $params = array('foo'=>'bar',
                'baz'=>'boom',
                'cow'=>'milk',
                'php'=>'hypertext processor');
        CurlClient::buildGETRequest($ch,"/api/v1/check",$params);
        curl_exec($ch);
        $url     = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        \Payabbhi\Client::$apiBase = $base;
        $this->assertEquals($url,"https://payabbhi.com/api/v1/check?foo=bar&baz=boom&cow=milk&php=hypertext+processor");
      }

      public function testUrlForGETRequestWithNoParams()
      {
        $ch = curl_init();
        $base = \Payabbhi\Client::baseUrl();
        \Payabbhi\Client::$apiBase = "https://payabbhi.com";
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        CurlClient::buildGETRequest($ch,"/api/v1/check",null);
        curl_exec($ch);
        $url     = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        \Payabbhi\Client::$apiBase = $base;
        $this->assertEquals($url,"https://payabbhi.com/api/v1/check");
      }

      public function testUrlForPOSTRequestWithParams()
      {
        $ch = curl_init();
        $base = \Payabbhi\Client::baseUrl();
        \Payabbhi\Client::$apiBase = "https://payabbhi.com";
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $params = array('foo'=>'bar',
                'baz'=>'boom',
                'cow'=>'milk',
                'php'=>'hypertext processor');
        CurlClient::buildPOSTRequest($ch,"/api/v1/check",$params);
        curl_exec($ch);
        $url     = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        \Payabbhi\Client::$apiBase = $base;
        $this->assertEquals("https://payabbhi.com/api/v1/check",$url);
      }

      public function testUrlForPOSTRequestWithParamsAsNull()
      {
        $ch = curl_init();
        $base = \Payabbhi\Client::baseUrl();
        \Payabbhi\Client::$apiBase = "https://payabbhi.com";
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        CurlClient::buildPOSTRequest($ch,"/api/v1/check",null);
        curl_exec($ch);
        $url     = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        \Payabbhi\Client::$apiBase = $base;
        $this->assertEquals("https://payabbhi.com/api/v1/check",$url);
      }

      public function testErrorForInvalidPayload()
      {
        try {
          $ch = curl_init();
          $params = "\xB1\x31";
          CurlClient::buildPOSTRequest($ch,"/api/v1/check",$params);
        } catch (InvalidRequest $e){
          $msg = "Error in request payload formation";
          $this->assertNull($e->getHttpStatus());
          $this->assertEquals($msg, $e->getDescription());
          $this->assertEquals("message: $msg\n", $e->getMessage());
          $this->assertNull($e->getField());
        }
      }

      public function testGetPathForUrl()
      {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $url = "https://payabbhi.com/search?hl=en&client=firefox-a&hs=42F";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_exec($ch);
        $this->assertEquals(CurlClient::getPath($ch),"/search?hl=en&client=firefox-a&hs=42F");
      }

      /**
      * @dataProvider CredentialProvider
      */
      public function testErrorForEmptyCredentials($accessid, $secretkey, $method)
      {
        try {
          $ch = curl_init();
          \Payabbhi\Client::$apiBase = "https://payabbhi.com";
          $api = new \Payabbhi\Client($accessid,$secretkey);
          $api->payment->all();
          $this->fail("Did not raise error");
        } catch (Authentication $e){
          $msg = "Incorrect access_id or secret_key provided";
          $this->assertEquals(401,$e->getHttpStatus());
          $this->assertEquals($msg, $e->getDescription());
          $this->assertEquals("message: $msg, http_code: 401\n", $e->getMessage());
          $this->assertEmpty($e->getField());
        }
      }

      public function CredentialProvider()
      {
        return [
            ["","secret_key","GET"],
            ["","secret_key","POST"],
            ["access_id","","GET"],
            ["access_id","","POST"],
            ["","","GET"],
            ["","","POST"]
        ];
      }

      private function parseHeader($header)
      {
        $myarray=array();
        $data=explode("\n",$header);
        $myarray['status']=$data[0];
        array_shift($data);
        array_pop($data);
        array_pop($data);
        foreach($data as $part)
        {
          $middle=explode(":",$part);
          $myarray[trim($middle[0])] = trim($middle[1]);
        }
      return $myarray;
      }

      public function testSignRequestInCurlHeaderForGET()
      {
        $ch = curl_init();
        $methodGET = "GET";
        $url = "http://payabbhi.com/search?hl=en&client=firefox-a&hs=42F";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLINFO_HEADER_OUT,true);
        $api = new \Payabbhi\Client("access_id","secret_key");
        CurlClient::signRequest($ch,$methodGET);
        curl_exec($ch);
        $headerarray = self::parseHeader(curl_getinfo($ch,CURLINFO_HEADER_OUT));
        $this->assertRegExp('/Basic/', $headerarray['Authorization']);
      }

      public function testSignRequestInCurlHeaderForPOST()
      {
        $ch = curl_init();
        $methodPOST = "POST";
        $url = "http://payabbhi.com/search?hl=en&client=firefox-a&hs=42F";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLINFO_HEADER_OUT,true);
        $api = new \Payabbhi\Client("access_id","secret_key");
        $params = array('foo'=>'bar',
                'baz'=>'boom',
                'cow'=>'milk',
                'php'=>'hypertext processor');
        $body =json_encode($params);
        CurlClient::signRequest($ch,$methodPOST,$body);
        curl_exec($ch);
        $headerarray = self::parseHeader(curl_getinfo($ch,CURLINFO_HEADER_OUT));
        $this->assertRegExp('/Basic/', $headerarray['Authorization']);
      }

      public function testNullResultForParseResult()
      {
        $ch = curl_init();
        curl_exec($ch);
        $this->assertNull(CurlClient::parseResult($ch,null));
      }

      public function testJsonResultForParseResult()
      {
        $ch = curl_init();
        curl_exec($ch);
        $this->assertEquals(CurlClient::parseResult($ch,'{"foo_bar": 12345}')->foo_bar,12345);
      }

      public function testErrorForBadJsonForParseResult()
      {
        try {
          $ch = curl_init();
          curl_exec($ch);
        } catch (Api $e) {
          $this->assertNull($e->getHttpStatus());
          $msg = "Something did not work as expected on our side";
          $this->assertEquals($msg, $e->getDescription());
          $this->assertEquals("description: $msg\n", $e->getMessage());
          $this->assertEmpty($e->getField());
        }
      }

      public function testParsingRawHTTPResponse()
      {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch,CURLINFO_HEADER_OUT,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $url = "https://payabbhi.com/api/v1/payments";
        curl_setopt($ch, CURLOPT_URL, $url);
        $params = array('foo'=>'bar',
                'baz'=>'boom',
                'cow'=>'milk',
                'php'=>'hypertext processor');
        $result =curl_exec($ch);
        $resp = CurlClient::parseRawResponse($ch,$result);
        $this->assertTrue(is_array($resp));
        $this->assertEquals($resp[0],401);
        $this->assertContains("Content-Type: application/json; charset=UTF-8",$resp[1]);
        $this->assertCount(3,$resp);
      }


}
