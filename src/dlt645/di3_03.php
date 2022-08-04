<?php

namespace meter645\dlt645;

use meter645\dlt645;
use meter645\lib\tool;

/**
 * 表A.3 变量数据标识编码表
 */
class di3_03 extends di3
{
    public function __construct($dlt645)
    {
        parent::__construct($dlt645);
    }
    /////////////////////日期时间、最大需量、滑差时间等/////////////////////
    /**
     * A相电压
     * XXX.X
     */
    public function getAQuadrantVolt()
    {
        $this->exec('11', '02', '01', '01', '00');
        $aVolt = intval(substr($this->response->NData, 0, 3)) + intval(substr($this->response->NData, 3, 1)) / 10;
        return $aVolt;
    }
    /////////////////////.../////////////////////
    /**
     * A相电流
     * XXX.XXX
     */
    public function getAQuadrantElc()
    {
        $this->exec('11', '02', '02', '01', '00');
        $aElc = intval(substr($this->response->NData, 0, 3)) + intval(substr($this->response->NData, 3, 3)) / 1000;
        return $aElc;
    }
    /////////////////////.../////////////////////
    /**
     * 瞬时总有功功率
     * XX.XXXX
     */
    public function getCurPower()
    {
        $this->exec('11', '02', '03', '01', '00');
        $cPower = intval(substr($this->response->NData, 0, 2)) + intval(substr($this->response->NData, 2, 4)) / 10000;
        return $cPower;
    }
    /////////////////////.../////////////////////
}
