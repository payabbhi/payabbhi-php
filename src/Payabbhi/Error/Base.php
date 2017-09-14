<?php
namespace Payabbhi\Error;
use Exception;

Class Base extends Exception
{
    var $httpStatus;
    var $description;
    var $field;
    var $message;

    function __construct($description, $field = null, $httpStatus = null)
    {
        $this->httpStatus  = $httpStatus;
        $this->description = $description;
        $this->field       = $field;
        $this->message     = $this->errorMessage();
    }

    function __toString()
    {
        return $this->message;
    }

    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }

    private function errorMessage()
    {
        $msg = "message: " . $this->description;
        $msg = empty($this->httpStatus) ? $msg : ($msg . ", http_code: " . $this->httpStatus);
        $msg = empty($this->field) ? $msg : ($msg . ", field: " . $this->field);

        return $msg . "\n";
    }

}
