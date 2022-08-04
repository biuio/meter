<?php

namespace meter645\test;

use meter645\dlt645;
use meter645\lib\conn;
use meter645\dlt645\di3_00;
use meter645\dlt645\di3_03;
use meter645\dlt645\di3_04;

class test
{
    public $conn;
    public $dlt645;
    public function __construct()
    {
        $this->ttt();
        $this->conn =  new conn('/dev/ttyUSB0');
        $this->dlt645 = new dlt645($this->conn);
    }
    public function start()
    {
        // $dl3_04 = new di3_04($this->dlt645);
        // $dl3_04->setAddress("214790020322");
        $dl3_00 = new di3_00($this->dlt645);
        $dl3_00->setAddress("214790020322");
        $energy = $dl3_00->getMeterEnergy(di3_00::DAYS[0], di3_00::RATES[0]);
        $dl3_03 = new di3_03($this->dlt645);
        $dl3_00->setAddress("214790020322");
        $aVolt = $dl3_03->getAQuadrantVolt();
        $aElc = $dl3_03->getAQuadrantElc();
        $cPower = $dl3_03->getCurPower();
        file_put_contents("/data/AxBuild/build/axDlt645/meter.log", '【' . date('Y-m-d H:i:s') . '】电能：' . $energy . '，电压：' . $aVolt . '，电流：' . $aElc . '，功率：' . $cPower . PHP_EOL, FILE_APPEND);
    }
    public function ttt()
    {
        // $str = '';
        // for ($i = 0; $i < 64; $i++) {
        //     $x = str_pad(strtoupper(dechex($i)), 2, '0', STR_PAD_LEFT);
        //     $str .= '"' . $x . '",';
        // }
        // var_dump($str);
        // exit;
        // var_dump(hexdec("AA"));
        // exit;
    }
    public function __destruct()
    {
        $this->conn->close();
    }
}
