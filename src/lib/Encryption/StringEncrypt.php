<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\lib\Encryption;

//基于异或计算的文本加密类
class StringEncrypt
{
    function strToHex($str){
        $hex="";
        for($i=0;$i<strlen($str);$i++)
            $hex.=dechex(ord($str[$i]));
        $hex=strtoupper($hex);
        return $hex;
    }


    function hexToStr($hex){
        $str="";
        for($i=0;$i<strlen($hex)-1;$i+=2)
            $str.=chr(hexdec($hex[$i].$hex[$i+1]));
        return $str;
    }

    function encode($string){

        $datas="%^&*()_+{}|:<>?`1234567890-=qwertyuiop[]\asdfghjkl;'zxcvbnm,./~!@#$";
        $datas=str_shuffle($datas);//每次都打乱顺序
        $arr2=str_split($datas,1);
        $arr1=str_split($string,1);
        $str="";
        foreach($arr1 as $item){
            $ok=false;
            foreach ($arr2 as $item2){
                foreach ($arr2 as $item3){
                    $i=$item2^$item3;
                    if($i===$item){
                        $str.=$item2.$item3;
                        $ok=true;
                        break;
                    }
                }
                if($ok)break;
            }
        }

        return strToHex(str_rot13($str));
    }

    function decode($string){
        $string=str_rot13(hexToStr($string));
        $arr1=str_split($string,2);
        $str="";
        foreach ($arr1 as $item){
            $arr2=str_split($item,1);
            $str.=$arr2[0]^$arr2[1];
        }
        return $str;
    }
}