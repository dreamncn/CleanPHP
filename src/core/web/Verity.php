<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/
namespace app\core\web;
class Verity{
    /**
     * @var Verity
     */
    private static $instance = null;
    private  $str = "";
    private  $err = "";
    public static function get($str){
        if(self::$instance==null){
            self::$instance=new verity();
        }
        self::$instance->setStr($str);
        return self::$instance;
    }

    public function setStr($s){
        $this->str=$s;
    }

    /**
     * 参数格式验证,长度验证
     * @param   $type     integer  验证方式  -1:不需要验证  1:email   2:手机号  3:英文  4:数字  5:汉字  6:url地址  7:身份证  8:QQ
     * @param   $num     integer   规定字符串长度 如果单位是字节，请在使用时自行除以3
     * @return mixed
     */
    function check($type = -1,$num = 0)
    {
        if (!empty($num) && $num > 0) {
            $len = mb_strlen($this->str, 'UTF8');
            if ($len > $num) {
                $this->err =   '不能超过' . floor($num) . '个字符';
                return false;
            }
        }

        switch ($type) {
            case 1:
            case 'email':
                $rules = '/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/';
                break;
            case 2:
            case 'mobile':
                $rules = '/^1[34578]\d{9}$/';
                break;
            case 3:
            case 'en':
                $rules = '/^[a-zA-Z\s]+$/';
                break;
            case 4:
            case 'number':
                $rules = '/^[0-9]*$/';
                break;
            case 5:
            case 'cn':
                $rules = '/^[\u4e00-\u9fa5]{0,}$/';
                break;
            case 6:
            case 'url':
                $rules = '/^http://([\w-]+\.)+[\w-]+(/[\w-./?%&=]*)?$/';
                break;
            case 7:
            case 'id':
                $rules = '/^((\d{18})|([0-9x]{18})|([0-9X]{18}))$/';
                break;
            case 8:
            case 'qq':
                $rules = '/[1-9][0-9]{4,}/';
                break;
            default:
                $rules = '//';
                break;
        }
        if (preg_match($rules, $this->str)) {

            $this->err = '校验通过';
            return true;
        } else {
            $this->err =  '参数不合法';
            return false;
        }

    }

    function getErr(){
        return $this->err;
    }
}