<?php

namespace meter645;

class dlt645
{
    const FRAME_START_SIGN = "68"; //帧起始符
    const DEFAULT_ADDR = "AAAAAAAAAAAA"; //电表默认地址
    const FRAME_END_SIGN = "16"; //帧起始符
    public $conn;

    public function __construct($conn)
    {
        $this->conn =  $conn;
    }
    public function exec($request, $response)
    {
        $raw = $this->conn->req($request->raw);
        $response->setRaw($raw)->make();
    }
}
