<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\core\debug;

/**
 * 字符串工具类
 */
class StringUtil{
    /**
     * @var StringUtil
     */
    private static $stringUtil;
    private $str="";
    public static function get($s)
    {
        if(self::$stringUtil==null){
            self::$stringUtil=new StringUtil();
        }
        self::$stringUtil->setStr($s);
        return self::$stringUtil;
    }

    public function setStr($str){
        $this->str=$str;
    }

    public function equals($s){
        return $this->str===$s;
    }

    public function contains($s){
        return strpos($this->str,$s)!== false;
    }

    public function startsWith( $subString) {
        return substr($this->str, 0, strlen($subString)) === $subString;
        // 或者 strpos($s2, $s1) === 0
    }

    public function endsWith(  $subString) {
        return substr($this->str, strpos($this->str, $subString)) === $subString;
    }

    public function findEnd($endString){
        return str_replace($endString,"",substr($this->str,strrpos($this->str,'/')));
    }

    public function findStart($startString){
        $str = substr($this->str,0,strpos($this->str,$startString));
        if($str=="")
            $str=$this->str;
        return $str;
    }
}