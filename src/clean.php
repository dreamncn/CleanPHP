<?php



require_once  "./core/utils/StringUtil.php";
require_once  "./core/config/Spyc.php";
require_once "./core/config/Config.php";
require_once  "./core/utils/FileUtil.php";

require_once  "./core/utils/ZipUtil.php";

use app\core\config\Config;
use app\core\utils\FileUtil;
use app\core\utils\StringUtil;
use app\core\utils\ZipUtil;

/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/
const APP_DIR = __DIR__;
const DS = DIRECTORY_SEPARATOR;
//定义控制器所在路径
const APP_CONTROLLER = APP_DIR . DS . 'controller' . DS;
//定义存储空间位置
const APP_STORAGE = APP_DIR . DS . 'storage' . DS;
//渲染完成的视图文件
const APP_TMP = APP_STORAGE . 'view' . DS;
//缓存文件
const APP_CACHE = APP_STORAGE . 'cache' . DS;
//路由缓存文件
const APP_ROUTE = APP_STORAGE . 'route' . DS;
//日志文件
const APP_LOG = APP_STORAGE . 'logs' . DS;
//垃圾文件
const APP_TRASH = APP_STORAGE . 'trash' . DS;
//框架拓展目录
const APP_EXTEND = APP_DIR . DS . 'extend' . DS;
//框架配置目录
const APP_CONF = APP_DIR . DS . 'config' . DS;
//模块路径
const APP_MODEL = APP_DIR . DS . 'model' . DS;
//框架第三方库
const APP_LIB = APP_DIR . DS . 'lib' . DS;
//框架原始视图存储位置
const APP_VIEW = APP_DIR . DS . 'static' . DS . 'view' . DS;
//框架原始视图（内置Response皮肤）存储位置
const APP_INNER = APP_DIR . DS . 'static' . DS . 'innerView' . DS;
//框架公开位置
const APP_PUBLIC = APP_DIR . DS . 'public' . DS;

class FileCheck
{
    private static $info = "";
    private static $errCount = 0;
    private static $warnCount = 0;
    private static $no_check=[];

    /**
     * 开始进行安全性检查
     * @return void
     */
    public static function run()
    {
        self::$errCount = 0;
        self::$warnCount = 0;
        self::$info = "[信息]安全性检查开始";
        self::checkPHPInUI();
        self::checkController();
        self::checkShell();
        self::$info .= "\n[信息]安全性检查结束，总计" . self::$warnCount . "个警告，" . self::$errCount . "个错误。";
        echo self::$info;
    }

    public static function setNoCheck($arr){

        self::$no_check=array_merge(self::$no_check,$arr);
    }
    public static function checkController(){
        self::$info .= "\n[信息]正在执行不规范写法检查";
        $files = self::getAllfile(APP_CONTROLLER);
        $files2 = self::getAllfile(APP_MODEL);
        $files=array_merge($files,$files2);
        self::doFile($files,function ($f){
            // $content=strtolower(file_get_contents($f));
            preg_match_all("/(echo|var_dump|die|exit|print|printf)(\(|\s)/",strtolower(file_get_contents($f)),$matches);
            if(sizeof($matches)!=0){
                if(sizeof($matches[0])!=0){
                    self::$errCount++;
                    self::$info .= "\n[错误]文件 $f 存在不规范的函数使用(".str_replace("\n","",str_replace("(","",$matches[0][0])).")！处理建议：输出内容请直接return，调试输出请使用内置函数，退出程序运行请使用exitApp()函数。";
                    //  dump(self::$info);
                }

            }
        });
    }
    public static function checkPHPInUI()
    {
        self::$info .= "\n[信息]正在执行公开目录可执行文件检查";
        $files = self::getAllfile(APP_PUBLIC);
        //检查公开目录的是否存在php
        self::doFile($files, function ($file) {
            if (!StringUtil::get($file)->endsWith("index.php") && StringUtil::get($file)->endsWith(".php")) {
                self::$warnCount++;
                self::$info .= "\n[警告] " . $file . " 存在可执行PHP文件。 修改建议：public目录下除了index.php不要放任何php文件。";
            }
        });
        $files = self::getAllfile(APP_STORAGE);
        // 检查缓存路径是否存在在php
        self::doFile($files, function ($file) {
            if (StringUtil::get($file)->endsWith(".php")) {
                self::$warnCount++;
                self::$info .= "\n[警告] " . $file . " 存在可执行PHP文件。 修改建议：storage路径为临时缓存与存储路径，请不要在此处放任何可执行php文件,因为在APP打包时会进行清理。";
            }
        });
        $files = self::getAllfile(APP_CONF);
        // 检查配置路径是否存在不标准文件
        self::doFile($files, function ($file) {
            if (!StringUtil::get($file)->endsWith(".DS_Store") && !StringUtil::get($file)->endsWith(".yml")) {
                self::$warnCount++;
                self::$info .= "\n[警告] " . $file . " 存在非配置文件。 修改建议：配置路径请勿放其他无关文件，建议删除该文件。";
            }
        });

    }

    private static function doFile($fileList, $fnName)
    {
        if (is_array($fileList) && sizeof($fileList) != 0) {
            foreach ($fileList as $key => $file) {
                self::doFile($file, $fnName);
            }
        }
        if (!is_array($fileList) && is_file($fileList)) {
            call_user_func_array($fnName, [$fileList]);
        }

    }

    public static function checkShell()
    {
        self::$info .= "\n[信息]正在检查可能存在的恶意代码";
        $functions = [
            '/(\s|\(|=)(system|passthru|shell_exec|exec|popen|proc_open)\(/'=>"可能导致系统命令执行，属于高危函数，请谨慎使用。",
            '/(\s|\(|=)(eval|assert|call_user_func|gzinflate|gzuncompress|gzdecode|str_rot13)\(/'=>"可能导致任意代码执行，请谨慎使用。",
            '/(\s|\(|=)(require|require_once|include|include_once)\(/'=>"可能导致任意文件包含，代码中请直接规范使用命名空间来避免包含文件。",
            '/\$_(GET|POST|REQUEST|COOKIE|SERVER|FILES)/'=>"可能导致不可控的用户输入，请使用内置的arg函数获取用户数据。",
            '/(\$\w+)\(/'=>"可能导致不可控的函数执行，请尽量明确执行函数。",
        ];
        self::$no_check[]="/clean.php";
        self::$no_check[]="/core";
        self::$no_check[]="/vendor";
        self::$no_check[]="/public/index.php";
        $file=self::getAllfile(APP_DIR);

        unset(self::$no_check[sizeof(self::$no_check)-1]);
        unset(self::$no_check[sizeof(self::$no_check)-1]);
        self::doFile($file,function ($f) use ($functions) {
            if(!StringUtil::get($f)->endsWith(".php"))return;
            foreach ($functions as $key=>$value){
                preg_match_all($key,strtolower(file_get_contents($f)),$matches);
                //  dump($matches);
                if(sizeof($matches)!=0){
                    if(sizeof($matches[0])!=0){
                        self::$warnCount++;
                        self::$info .= "\n[警告]文件 $f 存在可疑的(".str_replace("\n","",str_replace("(","",$matches[0][0])).")调用！处理建议：$value";
                        //  dump(self::$info);
                    }

                }
            }
        });



    }



    private static function getAllfile($dir)
    {
        $files = array();
        if ($head = opendir($dir)) {
            while (($entry = readdir($head)) !== false) {
                $file=str_replace("//","/",str_replace(APP_DIR,"",$dir). '/' .$entry);
                $find=false;
                foreach (self::$no_check as $v){
                    if(StringUtil::get($file)->startsWith($v)){
                        $find=true;
                        break;
                    }
                }
                if($find)continue;
                if ($entry != ".." && $entry != ".") {
                    if (is_dir($dir . '/' . $entry)) {
                        $files[$entry] = self::getAllfile($dir . '/' . $entry);
                    } else {
                        $files[] = $dir . '/' . $entry;
                    }
                }
            }
        }
        closedir($head);
        return $files;
    }
}
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
        FileUtil::del($new . "/clean.php");
        FileUtil::empty($new . "/storage/cache/");//清空文件夹
        file_put_contents($new . "/storage/cache/.gitkeep","");
        FileUtil::empty($new . "/storage/logs/");//清空文件夹
        file_put_contents($new . "/storage/logs/.gitkeep","");
        FileUtil::empty($new . "/storage/route/");//清空文件夹
        file_put_contents($new . "/storage/route/.gitkeep","");
        FileUtil::empty($new . "/storage/trash/");//清空文件夹
        file_put_contents($new . "/storage/trash/.gitkeep","");
        FileUtil::empty($new . "/storage/view/");//清空文件夹
        file_put_contents($new . "/storage/view/.gitkeep","");

        Config::getInstance("frame")->setLocation($new . "/config/")->set("debug", false);//关闭调试模式
        $hosts = Config::getInstance("frame")->setLocation($new . "/config/")->get("host");
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

        $appName = Config::getInstance("frame")->setLocation($new . "/config/")->get("app");
        $verCode = Config::getInstance("frame")->setLocation($new . "/config/")->get("verCode");
        $verName = Config::getInstance("frame")->setLocation($new . "/config/")->get("verName");
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
        }
        fclose($fh);


        if(class_exists("ZipArchive")){
            $fileName=dirname(APP_DIR) . "/release/".$appName."_".$verName."(".$verCode.").zip";

            $zip=new ZipUtil();
            $zip->zip("../release/temp",$fileName);
            echo "\n[项目打包程序]php程序已打包至$fileName";
            FileUtil::del($new);
        }else{
            $fileName=dirname(APP_DIR) . "/release/temp";
            echo "\n[项目打包程序]缺少zip拓展，请手动生成压缩包，程序位于：$fileName";
        }



    }

}
function help()
{
    echo <<<EOF
Usage: php clean.php [options] 
Options:
  check                     代码检查.
  release                   发布安装包.
  
  run [index/main/index]    命令行模式运行CleanPHP.
EOF;
    return null;
}

function run($argv)
{
    if (!isset($argv[2]) && ($argv != "clean_check" && $argv != "clean_release")) return help();
    $_SERVER['CLEAN_CONSOLE'] = true;
    $_SERVER["HTTP_HOST"] = "localhost";
    if (is_array($argv)) {
        $uri = $argv[2];
        if(StringUtil::get($uri)->startsWith("/"))
            $uri = substr($uri,1);

        $_SERVER["REQUEST_URI"] = "/" . $uri;
        $str = substr($argv[2], strpos($argv[2], "?") + 1);

        $res = [];

        $arr = explode('&', $str);//转成数组
        foreach ($arr as $k => $v) {
            $arr = explode('=', $v);
            if(sizeof($arr)==2){
                $res[$arr[0]] = $arr[1];
            }

        }

        $_GET = $res;
        $_REQUEST = $_GET;
        include './public/index.php';
        return null;

    } else{
        if($argv == "clean_check"){
            FileCheck::run();
        }else if($argv == "clean_release"){
            Release::run();
        }
    }
}

function release()
{
    run("clean_release");
}

function check()
{
    run("clean_check");
}


if (!isset($argv[1]))
    return help();

switch ($argv[1]) {
    case "check":
        check();
        break;
    case "release":
        release();
        break;
    case "run":
        run($argv);
        break;
    default:
        help();
}



