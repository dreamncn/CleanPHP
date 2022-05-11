<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/
/**
 * Package: app\extend\ankioTask\core
 * Class cycle
 * Created By ankio.
 * Date : 2022/5/10
 * Time : 19:29
 * Description :
 */

namespace app\extend\ankioTask\core;

class Cycle
{

    /**
     * 以天为周期
     * @param $hour int 小时
     * @param $minute int 分钟
     * @return array
     */
    static public function day(int $hour, int $minute): array
    {
        return [$minute,$hour,1,0,0];
    }

    /**
     * 以N天为周期
     * @param $day int 天数
     * @param $hour int 时间
     * @param $minute int 分钟
     * @return array
     */
    static public function nDay(int $day, int $hour, int $minute): array
    {
        return [$minute,$hour,$day,0,0];
    }

    /**
     * 以N小时为周期
     * @param $hour int 小时
     * @param $minute int 分钟
     * @return array
     */
    static public function nHour(int $hour, int $minute): array
    {
        return [$minute,$hour,1,0,0];
    }

    /**
     * 以小时为周期
     * @param $minute int 分钟
     * @return array
     */
    static public function hour(int $minute): array
    {
        return [$minute,1,0,0,0];
    }

    /**
     * 以N分钟为周期
     * @param $minute int 分钟
     * @return array
     */
    static public function nMinute(int $minute): array
    {
        return [$minute,0,0,0,0];
    }

    /**
     * 以周为周期
     * @param $week int 周数
     * @param $hour int 小时
     * @param $minute int 分钟
     * @return array
     */
    static public function week(int $week, int $hour, int $minute): array
    {
        return [$minute,$hour,0,0,$week];
    }

    /**
     * 以月为周期
     * @param $day int 天
     * @param $hour int 小时
     * @param $minute int 分钟
     * @return array
     */
    static public function month(int $day, int $hour, int $minute): array
    {
        return [$minute,$hour,$day,1,0];
    }

}