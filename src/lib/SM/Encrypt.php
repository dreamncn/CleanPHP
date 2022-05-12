<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/
/**
 * Package: app\lib\SM
 * Class Encrypt
 * Created By ankio.
 * Date : 2022/5/12
 * Time : 12:52
 * Description :
 */

namespace app\lib\SM;

use Rtgm\sm\RtSm2;
use Rtgm\sm\RtSm3;
use Rtgm\sm\RtSm4;

class Encrypt
{
    const CBC = "sm4";
    const ECB = "sm4-ecb";
    const OFB = "sm4-ofb";
    const CFB = "sm4-cfb";
    const CTR = "sm4-ctr";

    const BASE64 = "base64";
    const HEX = "hex";
    static function sm3($data){
        $sm3 = new RtSm3();
        return $sm3->digest($data,1);
    }

    static function sm4($key): RtSm4
    {
        return  new RtSm4($key);
    }

    static function sm2($format=self::BASE64): RtSm2
    {
        return   new RtSm2($format);
    }

}