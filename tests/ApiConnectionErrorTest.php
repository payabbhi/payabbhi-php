<?php
namespace Payabbhi;
use Payabbhi\HttpClient\CurlClient;
use Payabbhi\Error\Api;
use Payabbhi\Error\InvalidRequest;
use Payabbhi\Error\ApiConnection;
use Payabbhi\Error\Authentication;
use Payabbhi\Payabbhi;
use PHPUnit\Framework\TestCase;

class ApiConnectionErrorTest extends TestCase
{
  public function testCurlTimeout()
  {
    try {
        $ch = curl_init();
        $url = "https://payabbhi.com/api/v1/payments";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_TIMEOUT_MS,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT_MS,1);
        $result = curl_exec($ch);
        CurlClient::handleCurlError($ch);
      } catch (\Payabbhi\Error\ApiConnection $e) {
        $this->assertEmpty($e->getHttpStatus());
        $this->assertContains('errno 28',$e->getDescription());
        $this->assertContains('errno 28',$e->getMessage());
        $this->assertEmpty($e->getField());
     }
  }

  public function testInsecureCertificate()
  {
    try {
      $ch = curl_init();
      $url = "https://www.payabbhi.com/";
      curl_setopt($ch, CURLOPT_URL, $url);
      $result = curl_exec($ch);
    } catch (\Payabbhi\Error\ApiConnection $e) {
      $this->assertEmpty($e->getHttpStatus());
      $message = "SSL certificate problem: Invalid certificate chain";
      $msg = "Could not verify Payabbhi's SSL certificate.  Please make sure " . "that your network is not intercepting certificates.  " . "(Try going to $url in your browser.)  " . "If this problem persists,";
      $msg .= " let us know at support@payabbhi.com.";
      $msg .= "\n\n(Network error [errno 60]: $message)";
      $this->assertEquals($message,$e->getDescription());
      $this->assertEquals($msg,$e->getMessage());
      $this->assertEmpty($e->getField());
    }
  }
}
