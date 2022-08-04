<?php

namespace meter645\dlt645;

use meter645\lib\tool;

class response
{
    public $raw; //响应的原始应答帧（二进制）
    public $str; //响应的应答帧字符串
    public $ctrlCode;
    public $len;
    public $NData;
    public $di3;
    public $di2;
    public $di1;
    public $di0;
    public function __construct()
    {
    }
    public function make()
    {
        $this->str = substr(tool::hex2Hexstr($this->raw), 8);
        $this->ctrlCode = substr($this->str, 16, 2);
        $this->len = hexdec(substr($this->str, 18, 2)) * 2;
        switch ($this->ctrlCode) {
            case '91': //正常应答
            case "D1": //异常应答
                break;
        }
        $di = tool::getDataAdd33H(substr($this->str, 20, 8), false);
        $this->di3 = substr($di, 6);
        $this->di2 = substr($di, 4, 2);
        $this->di1 = substr($di, 2, 2);
        $this->di0 = substr($di, 0, 2);
        $this->NData = tool::reverseByte(tool::getDataAdd33H(substr($this->str, 28, $this->len - 8), false));
    }
    public function setRaw($raw)
    {
        $this->raw = $raw;
        return $this;
    }
}
