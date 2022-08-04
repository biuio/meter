<?php

namespace meter645\dlt645;

use meter645\dlt645;
use meter645\lib\tool;

/**
 * 表A.5 参变量数据表示编码表
 */
class di3_04 extends di3
{
    public function __construct($dlt645)
    {
        parent::__construct($dlt645);
    }
    /////////////////////日期时间、最大需量、滑差时间等/////////////////////
    /**
     * 日期及星期（其中0代表星期天）
     * YYMMDDWW，年月日星期
     */
    public function getDayWeek()
    {
        $this->exec('11', '04', '00', '01', '01');
        $NData = str_split($this->response->NData, 2);
        return ['year' => $NData[0], 'month' => $NData[1], 'day' => $NData[2], 'W' => $NData[3]];
    }
    /**
     * 时间
     * hhmmss，时分秒
     */
    public function getTime()
    {
        $this->exec('11', '04', '00', '01', '02');
        $NData = str_split($this->response->NData, 2);
        return ['hour' => $NData[0], 'min' => $NData[1], 'sec' => $NData[2]];
    }
    /**
     * 最大需量周期
     * NN，分
     */
    public function getMaxDemandCycle()
    {
        $this->exec('11', '04', '00', '01', '03');
        $NData = str_split($this->response->NData, 2);
    }
    /**
     * 滑差时间
     * NN，分
     */
    public function getSlipTime()
    {
        $this->exec('11', '04', '00', '01', '04');
        $NData = str_split($this->response->NData, 2);
    }
    /**
     * 校表脉冲宽度
     * XXXX，毫秒
     */
    public function getCalibrationMeterPulseWidth()
    {
        $this->exec('11', '04', '00', '01', '05');
        $NData = str_split($this->response->NData, 2);
    }
    /**
     * 两套时区表切换时间
     * YYMMDDhhmm，年月日时分
     */
    public function getTwoTimezoneSwitchTimes()
    {
        $this->exec('11', '04', '00', '01', '06');
        $NData = str_split($this->response->NData, 2);
    }
    /**
     * 两套日式段表切换时间
     * YYMMDDhhmm，年月日时分
     */
    public function getDayTimetableSwitchTimes()
    {
        $this->exec('11', '04', '00', '01', '07');
        $NData = str_split($this->response->NData, 2);
    }
    /////////////////////年时区数、日时段表数等/////////////////////
    /////////////////////自动循环显示屏数、每屏显示时间等/////////////////////
    /////////////////////通讯地址、表号、资产管理编码等/////////////////////
    /**
     * 通讯地址
     * NN NN NN NN NN NN
     */
    public function getCorrespondenceAddress()
    {
        $this->exec('11', '04', '00', '04', '01');
        return tool::reverseByte($this->response->NData, 3, true);
    }
    /**
     * 表号
     * NN NN NN NN NN NN
     */
    public function getMeterNo()
    {
        $this->exec('11', '04', '00', '04', '02');
        return tool::reverseByte($this->response->NData, 3, true);
    }
    /**
     * 资产管理编码
     * NN NN NN ... NN NN NN，32字节
     */
    public function getMeterMgNo()
    {
        $this->exec('11', '04', '00', '04', '03');
        return tool::reverseByte($this->response->NData, 3, true);
    }
    /**
     * 额定电压
     * XX XX XX XX XX XX
     */
    public function getRatedVolt()
    {
        $this->exec('11', '04', '00', '04', '04');
        return tool::reverseByte($this->response->NData, 3, true);
    }
    /////////////////////.../////////////////////
    /////////////////////.../////////////////////
}
