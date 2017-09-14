<?php
namespace Payabbhi;
// use PHPUnit\Framework\TestCase;

/**
 * Base class for Payabbhi test cases, provides some utility methods for creating
 * objects.
 */
class PayabbhiTestCase extends \PHPUnit_Framework_TestCase
{
  protected static function authorizeFromEnv()
      {
        // \Payabbhi\Payabbhi::$apiBase = "https://payabbhi.com";
        $accessID = getenv('ACCESS_ID');
        $secretKey = getenv('SECRET_KEY');
        $api = new \Payabbhi\Client($accessID,$secretKey);
        return $api;
      }

}
