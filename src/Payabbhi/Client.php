<?php
namespace Payabbhi;

Class Client
{
    private static $accessID = null;

    private static $secretKey = null;

    // @var array The application's information (name, version, URL)
    public static $appInfo = null;

    // @var string The base URL for the Payabbhi API.
    public static $apiBase = "https://payabbhi.com";

    const VERSION = '1.0.1';


    /**
     * @return string The base URL of Payabbhi API.
     */
    public static function baseUrl()
    {
        return self::$apiBase;
    }

    /**
     * @param string $accessID
     * @param string $secretKey
     */
    public function __construct($accessID, $secretKey)
    {
        self::$accessID = $accessID;
        self::$secretKey = $secretKey;
    }


    /**
     * @param string $appName The merchant application's name
     * @param string $appVersion The merchant application's version
     * @param string $appUrl The merchant application's URL
     */
    public function setAppInfo($appName, $appVersion = null, $appUrl = null)
    {
        if (self::$appInfo === null) {
            self::$appInfo = array();
        }
        self::$appInfo['name'] = $appName;
        self::$appInfo['version'] = $appVersion;
        self::$appInfo['url'] = $appUrl;
    }

    /**
     * @return array | null The application's information
     */
    public static function getAppInfo()
    {
        return self::$appInfo;
    }


    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $className = __NAMESPACE__.'\\'.ucwords($name);
        $object = new $className();

        return $object;
    }

    /**
     * @return string The API Secret Key used for requests.
     */
    public static function getSecretKey()
    {
        return self::$secretKey;
    }

    /**
     * @return string The API Access ID used for requests.
     */
    public static function getAccessID()
    {
        return self::$accessID;
    }

}
