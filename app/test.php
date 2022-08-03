<?php

namespace app;

use lib\dlt645;
use lib\conn;
use lib\dlt645\di3_00;
use lib\dlt645\di3_04;

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
        $dl3_00->getMeterEnergy(di3_00::DAYS[0], di3_00::RATES[0]);
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
}
