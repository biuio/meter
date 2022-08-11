<?php

namespace Biuio\Meter\dlt645;

use Biuio\Meter\lib\tool;

class response
{
    public $raw; //响应的原始应答帧（二进制）
    public $str; //响应的应答帧字符串
    public $ctrlCode;
    public $len;
    public $NData;
    public $di;
    public $di3;
    public $di2;
    public $di1;
    public $di0;
    public $success = false;
    public $err = null;
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
                $this->di = tool::getDataAdd33H(substr($this->str, 20, 8), false);
                $this->di3 = substr($this->di, 6);
                $this->di2 = substr($this->di, 4, 2);
                $this->di1 = substr($this->di, 2, 2);
                $this->di0 = substr($this->di, 0, 2);
                $this->NData = tool::reverseByte(tool::getDataAdd33H(substr($this->str, 28, $this->len - 8), false));
                $this->success = true;
                $this->err = null;
                break;
            case "D1": //异常应答
                $this->di = "";
                $this->di3 = "";
                $this->di2 = "";
                $this->di1 = "";
                $this->di0 = "";
                $this->NData = tool::getDataAdd33H(substr($this->str, 20, $this->len), false);
                // $this->NData = tool::reverseByte(tool::getDataAdd33H(substr($this->str, 20, $this->len), false));
                $this->success = false;
                $this->err = tool::getHex2bin($this->NData);
                break;
        }
    }
    public function setRaw($raw)
    {
        $this->raw = $raw;
        return $this;
    }
}
