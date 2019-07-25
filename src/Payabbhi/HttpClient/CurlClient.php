<?php

namespace Payabbhi\HttpClient;
use Payabbhi\Client;
use Payabbhi\Error;

class CurlClient
{
    private static $instance;
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // USER DEFINED TIMEOUTS

    const DEFAULT_CONNECT_TIMEOUT = 30;
    const DEFAULT_TIMEOUT = 80;

    private $connectTimeout = self::DEFAULT_CONNECT_TIMEOUT;
    private $timeout = self::DEFAULT_TIMEOUT;

    public function setTimeout($seconds)
    {
        $this->timeout = (int) max($seconds, 0);
        return $this;
    }

    /**
     * @return int timeout
     */
    public function getTimeout()
    {
        return $this->timeout;
    }
    public function setConnectTimeout($seconds)
    {
        $this->connectTimeout = (int) max($seconds, 0);
        return $this;
    }

    /**
     * @return int connectTimeout
     */
    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }

    /**
     * @return mixed curl handler
     * @throws Error\Api
     */
    private function getCurlHandler()
    {
        $ch = curl_init();
        if (!$ch) {
            throw new Error\Api("Could not initialize curl handler");
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HEADER, true);
        return $ch;
    }

    /**
     * @param mixed $ch
     * @param string $path
     * @param null|array $params
     */
    public static function buildGETRequest($ch, $path, $params)
    {
        $url = Client::baseUrl() . $path;
        if ($params != null) {
            foreach ($params as $key => $value){
              if (gettype($value) == "boolean"){
                $params[$key] = ($value) ? 'true' : 'false';
              }
            }
            $query = http_build_query($params);
            $url   = $url . '?' . $query;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
    }

    /**
     * @param mixed $ch
     * @param string $path
     * @param null|array $params
     * @return mixed json encoded body
     * @throws Error\InvalidRequest
     */
    public static function buildPOSTRequest($ch, $path, $params)
    {
        $url  = Client::baseUrl() . $path;
        $body = json_encode($params);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                break;
            default:
                curl_close($ch);
                throw new Error\InvalidRequest("Error in request payload formation");
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_URL, $url);
    }

    /**
     * @param mixed $ch
     * @param string $path
     * @param null|array $params
     * @return mixed json encoded body
     * @throws Error\InvalidRequest
     */
    public static function buildPUTRequest($ch, $path, $params)
    {
        $url  = Client::baseUrl() . $path;
        $body = json_encode($params);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                break;
            default:
                curl_close($ch);
                throw new Error\InvalidRequest("Error in request payload formation");
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    }

    /**
     * @param mixed $ch
     * @param string $path
     */
    public static function buildDELETERequest($ch, $path)
    {
        $url  = Client::baseUrl() . $path;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    }

    /**
     * @param mixed $ch
     * @param string $path
     */
    public static function buildPATCHRequest($ch, $path)
    {
        $url  = Client::baseUrl() . $path;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    }

    /**
     * @param mixed $ch
     * @return string $url
     */
    public static function getPath($ch)
    {
        $url        = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $parsed_url = parse_url($url);
        $path       = $parsed_url['path'];
        $url        = isset($parsed_url['query']) ? $path . '?' . $parsed_url['query'] : $path;
        return $url;
    }


    /**
     * @param mixed $ch
     * @param string $method
     * @param null|array $body
     * @throws Error\Authentication
     */
    public static function signRequest($ch)
    {
        $access_id  = Client::getAccessID();
        $secret_key = Client::getSecretKey();
        curl_setopt($ch, CURLOPT_USERPWD, $access_id . ":" . $secret_key);
    }


    /**
     * @param mixed $ch
     * @param string $path
     * @param string $method
     * @param null|array $params
     * @throws Error\Api
     */
    public static function handleHTTPMethod($ch, $path, $method, $params)
    {
        switch ($method) {
            case "GET":
                self::buildGETRequest($ch, $path, $params);
                self::signRequest($ch);
                break;
            case "POST":
                self::buildPOSTRequest($ch, $path, $params);
                self::signRequest($ch);
                break;
            case "PUT":
                self::buildPUTRequest($ch, $path, $params);
                self::signRequest($ch);
                break;
            case "DELETE":
                self::buildDELETERequest($ch, $path);
                self::signRequest($ch);
                break;
            case "PATCH":
                self::buildPATCHRequest($ch, $path);
                self::signRequest($ch);
                break;
            default:
                curl_close($ch);
                $msg = 'Unexpected Method: ' . $method;
                throw new Error\Api($msg, "method");
        }
    }

    /**
     * @param mixed $ch
     * @throws Error\ApiConnection
     */
    public static function handleCurlError($ch)
    {
        $url     = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $errno   = curl_errno($ch);
        $message = curl_error($ch);
        curl_close($ch);
        switch ($errno) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = "Could not connect to Payabbhi ($url).  Please check your " . "internet connection and try again, or";
                break;
            case CURLE_SSL_CACERT:
            case CURLE_SSL_PEER_CERTIFICATE:
                $msg = "Could not verify Payabbhi's SSL certificate.  Please make sure " . "that your network is not intercepting certificates.  " . "(Try going to $url in your browser.)  " . "If this problem persists,";
                break;
            default:
                $msg = "Unexpected error communicating with Payabbhi.  " . "If this problem persists,";
        }
        $msg .= " let us know at support@payabbhi.com.";
        $msg .= "\n\n(Network error [errno $errno]: $message)";
        throw new Error\ApiConnection($msg);

    }

    /**
     * @param mixed $ch
     * @param mixed $result
     * @return array
     */
    public static function parseRawResponse($ch, $result)
    {
        $http_code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header      = substr($result, 0, $header_size);
        $body        = substr($result, $header_size);
        return array(
            $http_code,
            $header,
            $body
        );
    }

    /**
     * @param mixed $ch
     * @param mixed $result
     * @throws Error\Api
     */
    public static function parseResult($ch, $result)
    {
        $decodedResult = json_decode($result);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $decodedResult;
            default:
                curl_close($ch);
                throw new Error\Api("Something did not work as expected on our side", null, 500);
        }
    }

    /**
     * @param mixed $ch.
     * @param array $decodedResult
     *
     * @throws Error\InvalidRequest if the error is caused by the invalid request parameters.
     * @throws Error\Authentication if the error is caused by invalid keys.
     * @throws Error\Api otherwise.
     */
    private static function checkHTTPStatusCode($ch, $decodedResult)
    {
        if (!curl_errno($ch)) {
            switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                case 200: # OK
                    break;
                case 400:
                case 404:
                    curl_close($ch);
                    $decodedError = $decodedResult->error;
                    throw new Error\InvalidRequest($decodedError->message, $decodedError->field, $http_code);
                case 401:
                    curl_close($ch);
                    $decodedError = $decodedResult->error;
                    throw new Error\Authentication($decodedError->message, $decodedError->field, $http_code);
                case 500:
                    curl_close($ch);
                    $decodedError = $decodedResult->error;
                    throw new Error\Api($decodedError->message, $decodedError->field, $http_code);
                case 502:
                    curl_close($ch);
                    $decodedError = $decodedResult->error;
                    if  (empty($decodedError->message)) {
                      throw new Error\Api("Something did not work as expected on our side", null, $http_code);
                    }
                    throw new Error\Gateway($decodedError->message, $decodedError->field, $http_code);
                default:
                    curl_close($ch);
                    $msg = 'Unexpected HTTP code: ' . $http_code;
                    throw new Error\Api($msg, null, $http_code);
            }
        }
    }

    private static function _formatAppInfo($appInfo)
    {
        if ($appInfo !== null) {
            $string = $appInfo['name'];
            if ($appInfo['version'] !== null) {
                $string .= '/' . $appInfo['version'];
            }
            if ($appInfo['url'] !== null) {
                $string .= ' (' . $appInfo['url'] . ')';
            }
            return $string;
        } else {
            return null;
        }
    }

    private static function setDefaultHeaders($ch)
      {
          $appInfo = \Payabbhi\Client::getAppInfo();

          $uaString = 'Payabbhi/v1 PhpBindings/' . Client::VERSION;

          $langVersion = phpversion();
          $uname = php_uname();

          $httplib = 'unknown';
          $ssllib = 'unknown';

          if (function_exists('curl_version')) {
              $curlVersion = curl_version();
              $httplib = 'curl ' . $curlVersion['version'];
              $ssllib = $curlVersion['ssl_version'];
          }

          $ua = array(
              'bindings_version' => Client::VERSION,
              'lang' => 'php',
              'lang_version' => $langVersion,
              'publisher' => 'payabbhi',
              'uname' => $uname,
              'httplib' => $httplib,
              'ssllib' => $ssllib,
          );
          if ($appInfo !== null) {
              $uaString .= ' ' . self::_formatAppInfo($appInfo);
              $ua['application'] = $appInfo;
          }

          $defaultHeaders = array(
              'Content-Type: application/json',
              'X-Payabbhi-Client-User-Agent: ' . json_encode($ua, JSON_UNESCAPED_SLASHES),
              'User-Agent: '. $uaString
          );
          curl_setopt($ch, CURLOPT_HTTPHEADER, $defaultHeaders);
      }

    /**
     * @param string $path.
     * @param string $method
     * @param null|array $params
     *
     * @return $json
     */
    public function request($path, $method, $params)
    {
        $ch = $this->getCurlHandler();
        self::setDefaultHeaders($ch);
        self::handleHTTPMethod($ch, $path, $method, $params);
        $result = curl_exec($ch);
        if (!$result) {
            self::handleCurlError($ch);
        }
        $raw_resp = self::parseRawResponse($ch, $result);
        $json     = self::parseResult($ch, $raw_resp[2]);
        self::checkHTTPStatusCode($ch, $json);
        curl_close($ch);
        return $json;
    }
}
