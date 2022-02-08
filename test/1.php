<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/
/**
 * File ${NAME}
 * Created By ankio.
 * Date : 2022/1/13
 * Time : 11:42 上午
 * Description :
 */
ini_set('date.timezone','Asia/Shanghai');
 function getNext(int $minute, int $hour, int $day, int $month, int $week,int $loop){
    $time=$minute*60+$hour*60*60+$day*60*60*24+$month*60*60*24*30+$week*60*60*24*7;
    //if($day!=0||$month!=0||)
    if($loop==0){//如果是循环的话，每小时，每天，每周，每月
        $loopType = "Month";//循环类型
        $date = mktime(0,0,0,date('m'),1,date('Y'));//取当前月的第一天
        $add= $month*60*60*24*30;
        if($month==0){
            $loopType="Week";
            $date = mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y"));//取当前周的第一天
            $add= $week*60*60*24*7;
            if($week==0){
                $loopType="Day";
                $date = mktime(0,0,0,date('m'),date('d'),date('Y'));//获取当天的
                $add= $day*60*60*24;
                if($day==0){
                    $loopType="Hour";
                    $date = mktime($hour,0,0,date('m'),date('d'),date('Y'));//获取当天的
                    $add= $hour*60*60;
                }
            }
        }
        //判断出循环类型
        $retTime = $date+$time;

        while($retTime<time()){
            $retTime = $retTime+$add;
        }
    }else{
        $retTime = time()+$time;
    }
    return $retTime;
}


var_dump(date("Y-m-d H:i:s",getNext(5,1,0,8,0,0)));
