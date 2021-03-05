<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\vendor\release;


use app\vendor\config\Config;
use app\vendor\debug\StringUtil;

class Release
{
    public static function run()
    {
        FileCheck::run();
        $fh = fopen('php://stdin', 'r');
        echo "\n[项目打包程序]请选择是否继续打包(y/n)：";
        $str = fread($fh, 1);
        fclose($fh);

        if ($str == "y") {
            //继续打包
            self::package();
        } else {
            echo "\n[项目打包程序]打包工作已取消";
        }
    }

    public static function package()
    {
        $new = dirname(APP_DIR) . "/release/temp";
        File::copyDir(APP_DIR, $new);
        unlink($new . "/clean.php");
        File::cleanDir($new . "/storage/cache/");//清空文件夹
        File::cleanDir($new . "/storage/logs/");//清空文件夹
        File::cleanDir($new . "/storage/route/");//清空文件夹
        File::cleanDir($new . "/storage/trash/");//清空文件夹
        File::cleanDir($new . "/storage/view/");//清空文件夹
        //删除命令行响应代码
        $rep = 'if(isset($_SERVER[\'CLEAN_CONSOLE\'])&&$_SERVER[\'CLEAN_CONSOLE\']){
            if($_SERVER["REQUEST_URI"]=="clean_check"){
                FileCheck::run();

            }else if($_SERVER["REQUEST_URI"]=="clean_release"){
                Release::run();
            }
            exitApp("命令行执行完毕");
        }';

        file_put_contents($new . "/vendor/web/Route.php", str_replace($rep, "", file_get_contents($new . "/vendor/web/Route.php")));

        Config::getInstance("frame")->setLocation($new . "/config/")->set("debug", false);//关闭调试模式
        $hosts = Config::getInstance("frame")->setLocation($new . "/config/")->getOne("host");
        echo "[项目打包程序]目前绑定域名如下：";
        for ($i = 0; $i < sizeof($hosts); $i++) {
            $host = $hosts[$i];
            echo "\n$host";
            $fh = fopen('php://stdin', 'r');
            echo "\n[项目打包程序]如需修改请输入新的域名,不修改请留空，删除请输入-1：";
            $str = fread($fh, 1000);
            fclose($fh);
            if (StringUtil::get($str)->startsWith("-1")) {
                echo "[项目打包程序]删除域名 {$hosts[$i]}";
                unset($hosts[$i]);
            } else if (StringUtil::get($str)->startsWith("\n")) {
                echo "[项目打包程序]{$hosts[$i]}无需修改。";
                continue;
            } else {
                $hosts[$i] = str_replace("\n", "", $str);
                echo "[项目打包程序]域名修改为  {$hosts[$i]} 。";

            }
        }
        Config::getInstance("frame")->setLocation($new . "/config/")->set("host", $hosts);

        $appName = Config::getInstance("frame")->setLocation($new . "/config/")->getOne("app");
        $verCode = Config::getInstance("frame")->setLocation($new . "/config/")->getOne("verCode");
        $verName = Config::getInstance("frame")->setLocation($new . "/config/")->getOne("verName");
        $fh = fopen('php://stdin', 'r');
        echo "\n[项目打包程序]项目名称（ $appName ），不修改请留空：";
        $str = fread($fh, 1000);
        if (StringUtil::get($str)->startsWith("\n")) {
            echo "[项目打包程序]无需修改。";
        } else {
            $appName = str_replace("\n","",$str);
            echo "[项目打包程序]修改项目名称为：$appName";
            Config::getInstance("frame")->setLocation($new . "/config/")->set("app", $appName);
        }
        fclose($fh);

        $fh = fopen('php://stdin', 'r');
        echo "[项目打包程序]更新版本号（ $verCode ），不修改请留空：";
        $str = fread($fh, 1000);
        if (StringUtil::get($str)->startsWith("\n")) {
            echo "[项目打包程序]无需修改。";
        } else {
            $verCode =  str_replace("\n","",$str);
            echo "[项目打包程序]修改版本号为：$verCode";
            Config::getInstance("frame")->setLocation($new . "/config/")->set("verCode", $verCode);
        }
        fclose($fh);

        $fh = fopen('php://stdin', 'r');
        echo "[项目打包程序]更新版本名（ $verName ），不修改请留空：";
        $str = fread($fh, 1000);
        if (StringUtil::get($str)->startsWith("\n")) {
            echo "\n[项目打包程序]无需修改。";
        } else {
            $verName =  str_replace("\n","",$str);
            echo "\n[项目打包程序]修改版本名为：$verName";
            Config::getInstance("frame")->setLocation($new . "/config/")->set("verName", $verName);
        }
        fclose($fh);

        Config::getInstance("frame")->setLocation($new . "/config/")->set("md5",  FileCheck::getMd5($new));


        $fileName=dirname(APP_DIR) . "/release/".$appName."_".$verName."(".$verCode.").zip";
        //File::zip($new,$new,$fileName );
        $zip=new Zip();
        $zip->Zip($new,$fileName);
        echo "\n[项目打包程序]php程序已打包至$fileName";
        File::del($new);
    }

    public static function clean()
    {
        $new = dirname(APP_DIR) . "/release/temp";
        File::copyDir(APP_DIR, $new);
        File::cleanDir($new . "/extend/");//清空文件夹
        //mkdir($new . "/extend/");
        File::cleanDir($new . "/lib/");//清空文件夹
        //mkdir($new . "/lib/");
        File::cleanDir($new . "/controller/");//清空文件夹
        File::cleanDir($new . "/static/view");//清空文件夹
        //mkdir($new . "/controller/");
        File::cleanDir($new . "/public/custom/");//清空文件夹
        File::cleanDir($new . "/public/layui/");//清空文件夹
        unlink("$new/storage/sql/1.db");//删除
        Config::getInstance("db")->setLocation($new . "/config/")->setAll(Config::getInstance("db")->setLocation("$new/config/")->getOne("master"));
        Config::getInstance("route")->setLocation($new . "/config/")->setAll(["<m>/<c>/<a>"=>"<m>/<c>/<a>"]);
        $fileName=dirname(APP_DIR) . "/release/clean_clean.zip";
        //File::zip($new,$new,$fileName );
        $zip=new Zip();
        $zip->Zip($new,$fileName);
        echo "\nclean php净化完成，已打包至该路径 $fileName";
        File::del($new);
    }


}



