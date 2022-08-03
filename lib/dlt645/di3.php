<?php

namespace lib\dlt645;

use lib\dlt645;

class di3
{
    public $address;
    public $dlt645;
    public $request;
    public $response;

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
    public function exec($ctrlCode, $di3, $di2, $di1, $di0, $debug = false)
    {
        $address = $this->address ? $this->address : dlt645::DEFAULT_ADDR;
        $this->request->setAddress($address)->setCtrlCode($ctrlCode)->setDI3($di3)->setDI2($di2)->setDI1($di1)->setDI0($di0)->make();
        $this->dlt645->exec($this->request, $this->response);
        if ($debug) {
            var_dump($this->request);
            var_dump($this->response);
        }
    }
}
