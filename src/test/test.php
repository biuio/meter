<?php

namespace Biuio\Meter\test;

use Biuio\Meter\dlt645;
use Biuio\Meter\lib\conn;
use Biuio\Meter\dlt645\di3_00;
use Biuio\Meter\dlt645\di3_03;
use Biuio\Meter\dlt645\di3_04;

class test
{
    public $conn;
    public $dlt645;
    public function __construct()
    {
    }
    public function start()
    {
        $this->ttt();
        $this->conn =  new conn('/dev/ttyUSB0');
        $this->dlt645 = new dlt645($this->conn);
        // $dl3_04 = new di3_04($this->dlt645);
        // $dl3_04->setAddress("214790020322");
        $dl3_00 = new di3_00($this->dlt645);
        $dl3_00->setAddress("214790020322");
        $energy = $dl3_00->getMeterEnergy(di3_00::E_TYPE_DI2_A['组合有功'], di3_00::RATES_DI1['总电能'], di3_00::DAYS_DI0['当前']);
        $dl3_03 = new di3_03($this->dlt645);
        $dl3_00->setAddress("214790020322");
        $aVolt = $dl3_03->getAQuadrantVolt();
        $aElc = $dl3_03->getAQuadrantElc();
        $cPower = $dl3_03->getCurPower();
        $this->conn->close();
    }
    public function ttt()
    {
        $str = '';
        for ($i = 0; $i < 64; $i++) {
            $x = str_pad(strtoupper(dechex($i)), 2, '0', STR_PAD_LEFT);
            $str .= '"费率' . $i . '电能"=>"' . $x . '",' . PHP_EOL;
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
