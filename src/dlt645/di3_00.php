<?php

namespace meter645\dlt645;

use meter645\dlt645;
use meter645\lib\tool;

/**
 * 表A.1 电能表数据标识编码表
 */
class di3_00 extends di3
{
    const RATES = ["00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "0A", "0B", "0C", "0D", "0E", "0F", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "1A", "1B", "1C", "1D", "1E", "1F", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "2A", "2B", "2C", "2D", "2E", "2F", "30", "31", "32", "33", "34", "35", "36", "37", "38", "39", "3A", "3B", "3C", "3D", "3E", "3F", "FF"];
    const DAYS = ["00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "0A", "0B", "0C"];
    public function __construct($dlt645)
    {
        parent::__construct($dlt645);
    }
    /////////////////////电能表数据标识编码表/////////////////////
    /**
     * 获取各项电能数据
     * 最近12天的，$day=self::DAYS
     * 费率，$feilv=self::RATES，当$feilv=255组合有功电能数据库
     * 
     */
    public function getMeterEnergy($day = "00", $rate = "00")
    {
        if (!in_array($day, self::DAYS)) {
            return false;
        }
        if (!in_array($rate, self::RATES)) {
            return false;
        }
        $this->exec('11', '00', '00', $rate, $day); //00 00 00 00
        $energy = intval(substr($this->response->NData, 0, 6)) + intval(substr($this->response->NData, 6, 2)) / 100;
        return $energy;
    }
    /////////////////////.../////////////////////
    /////////////////////.../////////////////////
}
