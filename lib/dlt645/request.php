<?php

namespace lib\dlt645;

use lib\dlt645;
use lib\tool;

class request
{
    public $raw; //发出的原始请求帧（二进制命令）
    public $address; //地址域
    public $ctrlCode; //控制码
    public $len;
    public $ck;
    public $str; //发出的原始请求帧字符串（十六进制形式的字符串，便于观看）
    public $di3;
    public $di2;
    public $di1;
    public $di0;
    public function __construct()
    {
    }

    public function make()
    {
        $data = "";
        if ($this->di3 && $this->di2 && $this->di1 && $this->di0) {
            $data = tool::getDataAdd33H($this->di0 . $this->di1 . $this->di2 . $this->di3); //注意，此处调转了DI3_2_1_0的顺序
        }
        $this->len = tool::getByteCnt($data);
        $preCmd = dlt645::FRAME_START_SIGN . $this->address . dlt645::FRAME_START_SIGN . $this->ctrlCode . $this->len . $data;
        $this->ck = strtoupper(dechex(tool::getSumCheckHex(tool::hexstr2Hex($preCmd))));
        $this->str = $preCmd . $this->ck . dlt645::FRAME_END_SIGN;
        $this->raw = tool::hexstr2Hex($this->str);
    }
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
    public function setCtrlCode($ctrlCode)
    {
        $this->ctrlCode = $ctrlCode;
        return $this;
    }
    public function setDI3($di3)
    {
        $this->di3 = $di3;
        return $this;
    }
    public function setDI2($di2)
    {
        $this->di2 = $di2;
        return $this;
    }
    public function setDI1($di1)
    {
        $this->di1 = $di1;
        return $this;
    }
    public function setDI0($di0)
    {
        $this->di0 = $di0;
        return $this;
    }
    public function resetReq()
    {
        $this->setDI3(null)->setDI2(null)->setDI1(null)->setDI0(null);
    }
}
