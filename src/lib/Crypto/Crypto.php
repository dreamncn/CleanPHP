<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/
/**
 * Class Crypto
 * Created By ankio.
 * Date : 2022/4/2
 * Time : 11:00
 * Description :
 */

namespace lib\Crypto;

class Crypto{
    static ?Crypto $instance=null;
    public static function getInstance(): ?Crypto
    {
        if (self::$instance == null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    private function setKey($strLen, $key){
        $key = md5($key);
        $keyLen = strlen($key);
        $skewy = $key;
        if($keyLen>$strLen){
            $skewy = substr($key,0,$strLen);
        }else{
            $limit = $strLen - $keyLen;
            $j=0;
            for($i=0;$i<$limit;$i++){
                if($j>=$keyLen)
                    $j=0;
                $skewy.=$key[$j];
                $j++;
            }
        }
        return $skewy;
    }

    /**
     * @param $str
     * @param $key
     * @return string
     */

    public function encrypt($str,$key): string
    {
        try{
            $base = bin2hex($str);
            $key = $this->setKey(strlen($base), $key);
            // var_dump($key);
            $strArr = str_split($base);
            $keys = str_split($key);
            $retArray = $strArr;
            $randTable = [];
            $keyCount = count($keys)-1;
            foreach ($strArr as $item=>$value){
                $rand = $keys[rand(0,$keyCount)];
                $randTable[] = $rand;
                $retArray[$item]= $retArray[$item]^$rand;
            }
            $encodeStr = join('', $retArray);
            $randTable = join('',$randTable);
            $table = $randTable^$key;
            return str_rot13($encodeStr.$table);
        }catch (\Throwable $e){
            return "";
        }
    }

    /**
     * @param $str
     * @param $key
     * @return string
     */
    public function decrypt($str,$key): string
    {
       try{
           $strLen = intval(strlen($str)/2);
           $key = $this->setKey($strLen,$key);
           $str = str_rot13($str);
           $encodeData = str_split(substr($str,0,$strLen));
           $strArr = str_split(substr($str,$strLen)^$key);
           $retArray = $encodeData;
           foreach ($strArr as $item=>$value){
               $retArray[$item]= $retArray[$item]^ $value;
           }
           return hex2bin(join('', $retArray))??"";
       }catch (\Throwable $e){
           return "";
       }
    }


}