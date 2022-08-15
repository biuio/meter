<?php

namespace Biuio\Meter\dlt645;

use Biuio\Meter\dlt645;

class di3
{
    public $address;
    public $dlt645;
    public $request;
    public $response;
    public $di;
    public $errorCode;
    public $errorMessage;

    public function __construct($dlt645)
    {
        $this->dlt645 = $dlt645;
        $this->request = new request();
        $this->response = new response();
    }
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
    public function exec($name, $ctrlCode, $di)
    {
        $address = $this->address ? $this->address : dlt645::DEFAULT_ADDR;
        $this->request->setName($name)->setAddress($address)->setCtrlCode($ctrlCode)->setDI3($di[0])->setDI2($di[1])->setDI1($di[2])->setDI0($di[3])->make();
        $raw = $this->dlt645->exec($this->request, $this->response);
        $this->response->setRaw($raw)->make();
    }
    public function setError($errorCode, $errorMessage)
    {
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }
    public function getError()
    {
        return ['errorCode' => $this->errorCode, 'errorMessage' => $this->errorMessage];
    }
    public function getDisReference($reference)
    {
        $pattern = '/引用\[(.*)\]格式/';
        preg_match($pattern, $reference, $match);
        if (!$match) {
            return false;
        }
        return $match[1];
    }
}
