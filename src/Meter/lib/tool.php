<?php

namespace Biuio\Meter\lib;

class tool
{
    /**
     * 将十六进制字符串，各位加上/减去/不加减（相当于只反转）33H，同时支持两字节调转
     * $type=(1/加33H,2/减33H,3/不加减)
     * 输入形如："01020304"
     * 输出形如："34353637"
     * 或者
     * 输入形如："34353637"
     * 输出形如："01020304"
     * 调转时如下：
     * 输入形如："34353637"
     * 输出形如："04030201"
     */
    public static function getDataAdd33H($data, $add = true)
    {
        $dataStr = "";
        $dataArr = str_split($data, 2);
        foreach ($dataArr as $d) {
            $dec = hexdec($d) + ($add ? 0x33 : -0x33);
            $hex = strtoupper(str_pad(dechex($dec), 2, '0', STR_PAD_LEFT));
            $dataStr .= $hex;
        }
        return $dataStr;
    }
    public static function reverseByte($data)
    {
        $dataStr = "";
        $dataArr = str_split($data, 2);
        foreach ($dataArr as $d) {
            $dataStr = $d . $dataStr;
        }
        return $dataStr;
    }
    /**
     * 获取二进制字节数，并补0
     * 输入形如："01020304"
     * 输出："04"
     */
    public static function getByteCnt($data)
    {
        return  str_pad(dechex(strlen($data) / 2), 2, '0', STR_PAD_LEFT);
    }
    /**
     * 十六进制字符串转二进制
     * 输入形如："68AAAAAAAA681100AD16"
     * 输出：二进制串
     */
    public static function hexstr2Hex($data)
    {
        return pack("H*", $data);
    }
    /**
     * 二进制转十六进制字符串
     * 输入二进制串
     * 输出形如："68AAAAAAAA681100AD16"
     */
    public static function hex2Hexstr($data)
    {
        $hexstr = "";
        $das = unpack('C*', $data);
        foreach ($das as $d) {
            $hex = strtoupper(str_pad(dechex($d), 2, '0', STR_PAD_LEFT));
            $hexstr .= $hex;
        }
        return $hexstr;
    }

    /**
     * 和校验结果（HEX）
     * 输入形如："68AAAAAAAA681100AD16"
     * 和校验结果（HEX）,https://www.23bei.com/tool/8.html
     */
    public static function getSumCheckHex($data)
    {
        $sum = 0;
        $das = unpack('C*', $data);
        foreach ($das as $d) {
            $sum += $d;
        }
        $ck = $sum % 256;
        return $ck;
    }
}
