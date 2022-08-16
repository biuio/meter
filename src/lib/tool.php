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
        $pos = 0;
        // $patterns = array_reverse($patterns);
        foreach ($patterns as $pattern) {
            if (!is_array($pattern) || $pattern == "---") {
                continue;
            }
            $data[] = self::caclOneResponse($pattern, $response, $pos);
            $pos += $pattern[1] * 2;
        }
        return ($data);
    }
    public static function caclOneResponse($patterns, $response, $pos = 0)
    {
        $pattern = $patterns[0];
        $len = $patterns[1];
        $unit = $patterns[2];
        $dtype = $patterns[3];
        $name = isset($patterns[4]) ? $patterns[4] : "";
        $data = null;
        if ($dtype == "string" || $dtype == "ascii" || $dtype == "uknow") {
            $data = substr($response, $pos, strlen($pattern));
            if ($dtype == "ascii") {
                $data = self::getAscii2Str($data);
            }
        } elseif ($dtype == "int") {
            $data = intval(substr($response, $pos, strlen($pattern)));
        } elseif ($dtype == "float" || $dtype == "double") {
            $dotPos = strpos($pattern, '.');
            $dotLen =  $dotPos !== false ? 1 : 0; //寻找其中是否有dot
            $rsp = substr($response, $pos, strlen($pattern) - $dotLen);
            $data = doubleval(substr_replace($rsp, ".", $dotPos, 0));
        } elseif ($dtype == "date" || $dtype == "time") {
            $dtime = substr($response, $pos, strlen($pattern));
            switch ($pattern) {
                case "YYMMDDhhmmss":
                    $data = date('Y-m-d H:i:s', strtotime("20" . $dtime));
                    break;
                case "YYMMDDhhmm":
                    $data = date('Y-m-d H:i', strtotime("20" . $dtime . "00"));
                    break;
                case "YYMMDDWW":
                    $day = date('Y-m-d', strtotime("20" . substr($dtime, 0, 6)));
                    $week = intval(substr($dtime, 6));
                    $data = ['day' => $day, 'week' => $week];
                    break;
                case "YYMMDD":
                    $data = date('Y-m-d', strtotime("20" . $dtime . "00"));
                    break;
                case "MMDDhhmm":
                    $data = date('m-d H:i', strtotime(date("Y") . $dtime . "00"));
                    break;
                case "MMDD":
                    $data = date('m-d', strtotime(date("Y") . $dtime));
                    break;
                case "DDhh":
                    $data = [
                        "day" => intval(substr($dtime, 0, 2)),
                        "hour" => intval(substr($dtime, 2)),
                    ];
                    break;
                case "hhmmss":
                    // $data = date('H:i:s', strtotime($dtime));
                    $data = substr_replace(substr_replace($dtime, ":", 2, 0), 5, 0);
                    break;
                case "hhmm":
                    $data = substr_replace($dtime, ":", 2, 0);
                    break;
            }
        } else {
            $data = "数据类型未知，请查阅资料后重试";
        }
        return ['val' => $data, 'len' => $len, 'unit' => $unit, 'dtype' => $dtype, 'name' => $name];
    }
    /**
     * 将一串十六进制字符串码转换位ascii字符串
     * 输入形如："3230475730323430303030303530303132303232303330390000000000000000"
     * 输出形如："20GW02400000500120220309"
     */
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
