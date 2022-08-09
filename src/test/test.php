<?php

namespace Biuio\Meter\test;

use Biuio\Meter\dlt645;
use Biuio\Meter\lib\conn;
use Biuio\Meter\dlt645\di3_00;
use Biuio\Meter\dlt645\di3_01;
use Biuio\Meter\dlt645\di3_03;
use Biuio\Meter\dlt645\di3_04;
use Biuio\Meter\lib\tool;

class test
{
    public $conn;
    public $dlt645;
    public function __construct()
    {
    }
    public function start()
    {
        // $this->ttt();
        $this->conn =  new conn('/dev/ttyUSB0');
        $this->dlt645 = new dlt645($this->conn);
        $dl3_00 = new di3_00($this->dlt645);
        $dl3_00->setAddress("214790020322");
        $energy = $dl3_00->getData('组合有功总电能', '当前');
        var_dump($dl3_00->request);
        var_dump($dl3_00->response);

        // $dl3_01 = new di3_01($this->dlt645);
        // $dl3_01->setAddress("214790020322");
        // $energy01 = $dl3_01->getMeterEnergy(di3_01::E_TYPE_DI2_A['反向有功'], di3_01::RATES_DI1['总最大需量及发生时间'], di3_01::DAYS_DI0['当前']);
        // var_dump($dl3_01->response);

        // $dl3_03 = new di3_03($this->dlt645);
        // $aVolt = $dl3_03->getAQuadrantVolt();
        // $aElc = $dl3_03->getAQuadrantElc();
        // $cPower = $dl3_03->getCurPower();

        // $dl3_04 = new di3_04($this->dlt645);
        // $dl3_04->setAddress("214790020322");

        $this->conn->close();
        // echo $energy01 . PHP_EOL;
    }
    public function ttt()
    {
        //     $bin = tool::getHex2bin("FF00");
        //     var_dump($bin);
        //     exit;
        $str = '';
        for ($i = 0; $i < 64; $i++) {
            $x = str_pad(strtoupper(dechex($i)), 2, '0', STR_PAD_LEFT);
            $str .= '"反向视在费率' . $i . '电能"=> ["00", "0A", "' . $x . '", "00", "XXXXXX.XX", "4", "kVAh", "r"],' . PHP_EOL;
        }
        print_r($str);
        exit;
        // var_dump(hexdec("AA"));
        // exit;
    }
    public function __destruct()
    {
    }
}
