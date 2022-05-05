<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\core\release;


use app\core\config\Config;
use app\core\utils\FileUtil;
use app\core\utils\StringUtil;
use app\core\utils\ZipUtil;

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
        FileUtil::copyDir(APP_DIR, $new);
        FileUtil::delFile($new . "/clean.php");
        FileUtil::cleanDir($new . "/storage/cache/");//清空文件夹
        file_put_contents($new . "/storage/cache/.gitkeep","");
        FileUtil::cleanDir($new . "/storage/logs/");//清空文件夹
        file_put_contents($new . "/storage/logs/.gitkeep","");
        FileUtil::cleanDir($new . "/storage/route/");//清空文件夹
        file_put_contents($new . "/storage/route/.gitkeep","");
        FileUtil::cleanDir($new . "/storage/trash/");//清空文件夹
        file_put_contents($new . "/storage/trash/.gitkeep","");
        FileUtil::cleanDir($new . "/storage/view/");//清空文件夹
        file_put_contents($new . "/storage/view/.gitkeep","");

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

        Config::getInstance("frame")->setLocation($new . "/config/")->set("md5",  FileCheck::getMd5($new,$new));

        $fileName=dirname(APP_DIR) . "/release/".$appName."_".$verName."(".$verCode.").zip";

        $zip=new ZipUtil();
        $zip->Zip("../release/temp",$fileName);
        echo "\n[项目打包程序]php程序已打包至$fileName";
        FileUtil::del($new);
    }

}



