<?php

namespace Biuio\Meter\test;

use Biuio\Meter\dlt645;
use Biuio\Meter\lib\conn;
use Biuio\Meter\dlt645\di3_00;
use Biuio\Meter\dlt645\di3_01;
use Biuio\Meter\dlt645\di3_02;
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
        $this->ttt();
        $this->conn =  new conn('/dev/ttyUSB0');
        $this->dlt645 = new dlt645($this->conn);

        // echo "di3_00" . PHP_EOL;
        // $di3_00 = new di3_00($this->dlt645);
        // $di3_00->setAddress("214790020322");
        // $data = $di3_00->getData('正向有功总电能', '当前');
        // // print_r($di3_00->request);
        // // print_r($di3_00->response);
        // print_r($data);

        // echo "dl3_01" . PHP_EOL;
        // $dl3_01 = new di3_01($this->dlt645);
        // $dl3_01->setAddress("214790020322");
        // $data = $dl3_01->getData('正向有功总最大需量及发生时间', '当前');
        // // print_r($dl3_01->request);
        // // print_r($dl3_01->response);
        // print_r($data);

        // echo "dl3_02" . PHP_EOL;
        // $dl3_02 = new di3_02($this->dlt645);
        // $dl3_02->setAddress("214790020322");
        // $data = $dl3_02->getData('A相电压');
        // print_r($data);
        // $data = $dl3_02->getData('内部电池工作时间');
        // // print_r($dl3_02->request);
        // // print_r($dl3_02->response);
        // print_r($data);

        echo "di3_03" . PHP_EOL;
        $di3_03 = new di3_03($this->dlt645);
        $di3_03->setAddress("214790020322");
        print_r(di3_03::DIs);
        $data = $di3_03->getData('A相失压记录');
        // print_r($di3_03->request);
        // print_r($di3_03->response);
        print_r($data);

        // echo "di3_04" . PHP_EOL;
        // $di3_04 = new di3_04($this->dlt645);
        // $di3_04->setAddress("214790020322");
        // $data = $di3_04->getData('厂家编号（ASCII码）');
        // // print_r($di3_04->request);
        // // print_r($di3_04->response);
        // print_r($data);

        $this->conn->close();
        print_r($data);
    }
    public function ttt()
    {
        //     $bin = tool::getHex2bin("FF00");
        //     var_dump($bin);
        //     exit;
        $str = '';
        for ($i = 0; $i < 255; $i++) {
            $x = str_pad(strtoupper(dechex($i)), 2, '0', STR_PAD_LEFT);
            $str .= '["YYMMDDNN", 4, "", "节假日编程前第' . $i . '节假日数据"],' . PHP_EOL;
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
