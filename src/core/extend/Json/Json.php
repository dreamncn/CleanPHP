<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/
/**
 * Package: core\extend\Json
 * Class Json
 * Created By ankio.
 * Date : 2022/5/5
 * Time : 12:22
 * Description :
 */

namespace core\extend\Json;

use stdClass;

class Json
{
    /**
     * @param $string string 需要解码的字符串
     * @param false $isArray 是否解码为数组
     * @return array|bool|float|int|mixed|stdClass|string|null
     */
    static function decode(string $string, bool $isArray=false){
        if($isArray)
            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        else{
            $json = new Services_JSON();
        }

       return  $json->decode($string);
    }

    /**
     * @param $array string 需要编码的字符串
     * @return Services_JSON_Error|float|int|mixed|string
     */
    static function encode(string $array){
        $json = new Services_JSON();
        return $json->encode($array);
    }

}