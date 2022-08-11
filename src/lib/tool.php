<?php

namespace Biuio\Meter\lib;

class tool
{
    /**
     * 支持返回值：
     * XX.XXXX\nYYMMDDhhmm   带回车符
     * YYMMDDhhmmss\nYYMMDDhhmmss\nXXXXXX.XX\n*8组
     * XXX.X\nXXX.XXX*3组\nX.XXX\nXXXXXX.XX*4组...
     * YYMMDDhhmmss\nXXX.XXX\nYYMMDDhhmmss
     * 
     * XXXXXX，XXXXXX\nXXXXXX，XXXXXX\nXXXXXX，XXXXXX   带回车和中文逗号，这种暂不支持
     * XXX.XXX，XXX.XXX   带中文逗号
     * 
     * XXX.X
     * XXX.XXX
     * XX.XXXX
     * X.XXX
     * XX.XX
     * XXXXXXXX
     * 
     * YYMMDDhhmm
     * YYMMDDhhmmss
     * MMDDhhmm
     * hhmmss
     * 
     * hhmmNN  这种暂不支持
     * MMDDNN  这种暂不支持
     * DDhh  这种暂不支持
     * YYMMDDNN  这种暂不支持
     * 
     * NN
     * NN.NNNN  32/64位
     * 
     * C0C1C2C3  这种暂不支持
     * 
     * 01  这种暂不支持
     * 未完成
     */
    public static function caclResponses($patterns, $response)
    {
        $data = null;
        foreach ($patterns as $pattern) {
            $data[] = self::caclOneResponse($pattern, $response); //这里有问题，起始点传0好像不太对
        }
        return $data;
    }
    public static function caclOneResponse($patterns, $response, $pos = 0)
    {
        $pattern = $patterns[0];
        $len = $patterns[1];
        $unit = $patterns[2];
        switch ($pattern) {
            case "YYMMDDhhmmss":
                $dtime = strtotime("20" . substr($response, $pos, strlen($pattern)));
                return ['val' => date('Y-m-d H:i:s', $dtime), 'unit' => $unit];
            case "YYMMDDhhmm":
                $dtime = strtotime("20" . substr($response, $pos, strlen($pattern)));
                return ['val' => date('Y-m-d H:i', $dtime), 'unit' => $unit];
            case "MMDDhhmm":
                $dtime = strtotime(substr($response, $pos, strlen($pattern)));
                return ['val' => date('m-d H:i', $dtime), 'unit' => $unit];
            case "hhmmss":
                $dtime = substr($response, $pos, strlen($pattern));
                return ['val' => date('H:i:s', $dtime), 'unit' => $unit];
        }
        $checkResult = self::checkChr($pattern);
        if ($checkResult['isDot']) {
            $r = substr_replace($response, ".", $checkResult['dotPos'], 0); //这里有问题
            return ['val' => doubleval($r), 'unit' => $unit];
        }
        if ($checkResult['allIsN']) {
            $r = substr($response, $pos, strlen($pattern));
            return ['val' => $r, 'unit' => $unit];
        }
        if ($checkResult['allIsX']) {
            $r = substr($response, $pos, strlen($pattern));
            return ['val' => intval($r), 'unit' => $unit];
        }
    }
    public static function checkChr($str)
    {
        $checkResult = [
            "allIsX" => true,
            "allIsN" => true,
            "isDot" => false,
            "dotPos" => -1,
        ];
        for ($i = 0; $i < strlen($str); $i++) {
            $chr = $str[$i];
            if ($chr != 'X') {
                $checkResult["allIsX"] = false;
            }
            if ($chr != 'N') {
                $checkResult["allIsN"] = false;
            }
            if ($chr == '.') {
                $checkResult["isDot"] = true;
                $checkResult["dotPos"] = $i;
                break;
            }
        }
        return $checkResult;
    }
    public static function getAscii2Str($ascii)
    {
        $dataStr = "";
        $dataArr = str_split($ascii, 2);
        foreach ($dataArr as $d) {
            $hex = chr(hexdec($d));
            $dataStr .= $hex;
        }
        return $dataStr;
    }
    /**
     * 将十六进制字符串转为二进制字符串
     * 输入形如："0A"
     * 输出形如："00000010"
     */
    public static function getHex2bin($hex)
    {
        $dataStr = "";
        $dataArr = str_split($hex, 2);
        foreach ($dataArr as $d) {
            $dec = hexdec($d);
            $hex = strtoupper(str_pad(decbin($dec), 8, '0', STR_PAD_LEFT));
            $dataStr .= $hex;
        }
        return $dataStr;
    }
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
