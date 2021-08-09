<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\extend\net_ankio_tasker\core;
use app\core\debug\Log;
use app\core\mvc\Model;
use app\core\web\Request;
use app\core\web\Response;

/**
 * +----------------------------------------------------------
 * Class Async
 * +----------------------------------------------------------
 * @package app\extend\net_ankio_tasker
 * +----------------------------------------------------------
 * Date: 2020/12/20 23:04
 * Author: ankio
 * +----------------------------------------------------------
 * Desciption:异步处理，多用于后台与多线程
 * +----------------------------------------------------------
 */
class Async extends Model
{
    static private $err = '';
    private static $instance=null;
    public static function err()
    {
        return self::$err;
    }



    public function __construct()
    {
        parent::__construct("extend_async");
        $this->setDbLocation(EXTEND_TASKER."data".DS, "db");
        $this->setDatabase("sqlite");
        $this->execute(
            "CREATE TABLE  IF NOT EXISTS extend_async(
                    id integer PRIMARY KEY autoincrement,
                    identify varchar(200),
                    timeout varchar(200),
                    token varchar(200)
                    )"
        );
    }

    /**
     * @return Async
     */
    public static function getInstance(){
        return self::$instance===null?(self::$instance=new Async()):self::$instance;
    }
    /**
     * +----------------------------------------------------------
     *  发起异步请求，就是后台服务请求
     * +----------------------------------------------------------
     * @param string $url 完整的URL
     * @param string $method 调用的方法
     * @param array $data 传递的数据
     * @param array $cookie cookie数组
     * @param string $identify 唯一标识符
     * +----------------------------------------------------------
     * @return bool
     * +----------------------------------------------------------
     */
    public static function request($url, $method = 'GET', $data = [], $cookie = [], $identify = 'clean')
    {

        Log::debug("Async","异步发起中：".$url);
        Log::debug("Async","identify：".$identify);

        $url_array = parse_url($url); //获取URL信息，以便平凑HTTP HEADER

        if($data==[]&&isset($url_array["query"]))
            parse_str($url_array["query"], $data);

        Log::debug("Async",print_r($data,true));
        $port = $url_array['scheme'] == 'http' ? 80 : 443;
       try{
           $fp = fsockopen(($url_array['scheme'] == 'http' ? "" : 'ssl://') . $url_array['host'], $port, $errno, $errstr, 30);
       }catch (\Exception $e){
           self::$err = '无法向该URL发起请求' . $errstr;
           Log::debug("Async","异步发起失败，原因：".self::$err);
           return false;
       }
        if (!$fp) {
            self::$err = '无法向该URL发起请求' . $errstr;
            Log::debug("Async","异步发起失败，原因：".self::$err);
            return false;
        }

       if ($method == 'GET' && $data!==[])
            $getPath = $url_array['path'] . "?" . http_build_query($data);
        else
            $getPath = $url_array['path'];

        $header = $method . " " . $getPath;
        $header .= " HTTP/1.1" . PHP_EOL;
        $header .= "Host: " . $url_array['host'] . "" . PHP_EOL; //HTTP 1.1 Host域不能省略
        $token = getRandom(128);

        $identify=md5($token . $identify);
        
        self::getInstance()->insert(SQL_INSERT_NORMAL)->table("extend_async")->keyValue(['identify'=>$identify,'token' => $token, 'timeout' => time() + 60])->commit();

        $header .= "Token: " . md5($token) . PHP_EOL;
        $header .= "Identify: $identify" . PHP_EOL;
        $header .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Async/1.0.0.1 " . PHP_EOL;
        $header .= "Connection:Close" . PHP_EOL;
        if (!empty($cookie)) {
            $_cookie = strval(null);
            foreach ($cookie as $k => $v) {
                $_cookie .= $k . "=" . $v . "; ";
            }
            $cookie_str = "Cookie: " . $_cookie . " " . PHP_EOL;//传递Cookie
            $header .= $cookie_str;
        }

        if (!empty($data)) {
            $_post = "" . PHP_EOL . http_build_query($data);
            $post_str = "Content-Type: application/x-www-form-urlencoded" . PHP_EOL;//POST数据
            $post_str .= "Content-Length: " . strlen($_post) . " " . PHP_EOL;//POST数据的长度
            $post_str .= $_post . PHP_EOL . PHP_EOL . " "; //传递POST数据

        }else{
            $post_str =   PHP_EOL . PHP_EOL . " "; //传递POST数据
        }
        $header .= $post_str;
        Log::debug("Async",$header);
        Log::debug("Async","异步发起结束");
        fwrite($fp, $header);
        fclose($fp);
        return true;
    }
    /**
     * +----------------------------------------------------------
     *  响应后台异步请求
     * +----------------------------------------------------------
     * @param int $time 最大运行时间
     * +----------------------------------------------------------
     * @return void
     * +----------------------------------------------------------
     */
    public static function response($time = 0)
    {
        Log::debug('Async_Res', '异步响应中...' );
        if (!self::checkToken()) {
            Log::debug('Async_Res', '异步响应失败，原因：' . self::$err);
            Response::msg(true,403,"禁止访问","您无权访问该资源。",0,Response::getAddress(),"立即跳转");
        }
        ignore_user_abort(true); // 后台运行，不受前端断开连接影响
        Log::debug('Async_Res', '异步响应成功... '.Response::getNowAddress() );
        set_time_limit($time);
        ob_end_clean();
        header("Connection: close");
        header("HTTP/1.1 200 OK");
        ob_start();
        $size = ob_get_length();
        header("Content-Length: $size");
        ob_end_flush();//输出当前缓冲
        flush();
        if (function_exists("fastcgi_finish_request")) {
            fastcgi_finish_request(); /* 响应完成, 关闭连接 */
        }
        sleep(1);
        Log::debug('ASync_Res', '异步响应结束...' );
    }

    /**
     * +----------------------------------------------------------
     * 进行Token检查
     * +----------------------------------------------------------
     * @return bool
     * +----------------------------------------------------------
     */
    private static function checkToken()
    {
        $header = Request::getHeader();
        if (isset($header['Token']) && isset($header['Identify'])) {

            $data = self::getInstance()->select()->table("extend_async")->where(['identify'=>$header['Identify']])->limit(1)->commit();

            if (empty($data)) {
                self::$err = 'token缺失';
                return false;
            }
            self::getInstance()->delete()->table("extend_async")->where(['identify'=>$header['Identify']])->commit();

            $token = $data[0];

            if ($token && isset($token['timeout']) && isset($token['token'])) {
                if (intval($token['timeout']) < time()) {
                    self::$err = '响应超时';
                    return false;
                }
                if ($header['Token'] !== md5($token['token'])) {
                    self::$err = 'token校验失败';
                    return false;
                }
                return true;
            }
        }
        self::$err = '任务不存在';
        return false;
    }
}
