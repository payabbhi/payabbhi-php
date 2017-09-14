<?php
namespace Payabbhi;
use Payabbhi\HttpClient\CurlClient;
use Payabbhi\Util\Util;
/**
 * Class ApiResource
 *
 * @package Payabbhi
 */

Class ApiResource extends Resource implements ArrayableInterface
{

    protected $attributes = array();
    /**
     * @return string The name of the class.
     */
    public static function className()
    {
        $class   = get_called_class();
        $postfix = strrchr($class, '\\');
        $class   = substr($postfix, 1);
        $name    = strtolower($class);
        return $name;
    }

    /**
     * @return string The endpoint URL for the given class.
     */
    public static function classUrl()
    {
        $base = static::className();
        return "/api/v1/${base}s";
    }


    /**
     * @return string The full API URL for this API resource.
     */
    public static function instanceUrl($id)
    {
      $base = static::classUrl();
      $id = Util::utf8($id);
      $extn = urlencode($id);
      return "$base/$extn";
    }

    public function getObjectIdentifier() {
      if (!isset($this->id)) {
        throw new Error\InvalidRequest("Object Identifier not set");
      }
      return $this->id;
    }

    protected function _retrieve($id)
    {
        return $this->_request(static::instanceUrl($id), "GET", null);
    }

    protected function _all($params = null)
    {
        $this->_validateParams($params);
        return $this->_request(static::classUrl(), "GET", $params);
    }

    /**
     * @param array $params
     * @throws Error\InvalidRequest
     */
    public static function _validateParams($params = null)
    {
        if ($params && !is_array($params)) {
            $message = "You must pass an array as the first argument to Payabbhi API method calls.";
            throw new Error\InvalidRequest($message);
        }
    }


    protected function _request($url, $method, $params)
    {
        $response = CurlClient::instance()->request($url, $method, $params);
        if ((isset($response->object)) and
            ($response->object == $this->getObject()))
        {
            $this->update($response);

            return $this;
        }
        else
        {
            return static::buildObject($response);
        }
    }

    public static function convertObjectToArray($values)
    {
        $results = array();
        foreach ($values as $k => $v) {
            if ($v instanceof Collection || $v instanceof Order || $v instanceof Payment || $v instanceof Refund) {
                $results[$k] = $v->__toArray(true);
            } elseif (is_array($v)) {
                $results[$k] = self::convertObjectToArray($v);
            } else {
                $results[$k] = $v;
            }
        }
        return $results;
    }


    protected static function buildObject($data)
    {
        $objects = static::getObjectsArray();
        if (isset($data->object))
        {

            if (in_array($data->object, $objects))
            {
                $class = static::getObjectClass($data->object);
                if ($class == "Payabbhi\List") {
                  $object = new Collection;
                } else {
                  $object = new $class;
                }

            }
            else
            {
                $object = new static;
            }
        }
        else
        {
            $object = new static;
        }

        $object->update($data);

        return $object;
    }

    protected static function getObjectsArray()
    {
        return array(
            'list',
            'payment',
            'refund',
            'order');
    }

    protected static function getObjectClass($name)
    {
        return __NAMESPACE__.'\\'.ucfirst($name);
    }

    protected function getObject()
    {
        $class = get_class($this);
        $pos = strrpos($class, '\\');
        $object = strtolower(substr($class, $pos + 1));
        if ($object == 'list') {
          $object = 'collection';
        }

        return $object;
    }
    public function update($data)
    {
        $attributes = array();

        foreach ($data as $key => $value)
        {
            if (is_array($value))
            {
                if  (static::isAssocArray($value) === false)
                {
                    $collection = array();

                    foreach ($value as $v)
                    {
                        if (is_array($v))
                        {
                            $object = static::buildObject($v);
                            array_push($collection, $object);
                        }
                        else
                        {
                            array_push($collection, static::buildObject($v));
                        }
                    }

                    $value = $collection;
                }
                else
                {
                    $value = static::buildObject($value);
                }
            }
            else if(is_object($value))
            {
              $value = static::buildObject($value);
            }

            $attributes[$key] = $value;
        }

        $this->attributes = $attributes;
    }

    public static function isAssocArray($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function toArray()
    {
        return $this->convertToArray($this->attributes);
    }

    protected function convertToArray($attributes)
    {
        $array = $attributes;

        foreach ($attributes as $key => $value)
        {
            if (is_object($value))
            {
                $array[$key] = $value->toArray();
            }
            else if (is_array($value) and self::isAssocArray($value) == false)
            {
                $array[$key] = $this->convertToArray($value);
            }
        }

        return $array;
    }

    public function __toJSON()
    {
        if (defined('JSON_PRETTY_PRINT')) {
            return json_encode($this->__toArray(true), JSON_PRETTY_PRINT| JSON_UNESCAPED_SLASHES);
        } else {
            return json_encode($this->__toArray(true));
        }
    }


    public function __toString()
    {
        return $this->__toJSON();
    }

    public function __toArray($recursive = false)
    {
        if ($recursive) {
            return self::convertObjectToArray($this->attributes);
        } else {
            return $this->attributes;
        }
    }
}
