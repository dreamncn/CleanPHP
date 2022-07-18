<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace core\utils;


use ZipArchive;

/**
 * Class ZipUtil
 * Created By ankio.
 * Date : 2022/1/12
 * Time : 8:12 下午
 * Description :zip工具类
 */
class ZipUtil
{


    function zip($dir, $dst): bool
    {
        $zip=new ZipArchive();
        if($zip->open($dst, ZipArchive::CREATE|ZipArchive::OVERWRITE)=== TRUE){
           $this->addFileToZip($dir, $zip,$dir); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法

            return $zip->close(); //关闭处理的zip文件
        }
        return false;
    }

    function unZip($src,$dst): bool
    {
        $zip=new ZipArchive();
        if ($zip->open($src) === TRUE) {//中文文件名要使用ANSI编码的文件格式
            $zip->extractTo($dst);//提取全部文件
            //$zip->extractTo('/my/destination/dir/', array('pear_item.gif', 'testfromfile.php'));//提取部分文件
            return  $zip->close();
        }
        return false;
    }

    /**
     *
     * @param $path
     * @param $zip ZipArchive
     * @param $replace
     * @return void
     */
    private function addFileToZip($path, ZipArchive $zip,$replace){
        $handler=opendir($path); //打开当前文件夹由$path指定。
        while(($filename=readdir($handler))!==false){
            if(!StringUtil::get($filename)->startsWith(".")){//文件夹文件名字为'.'和‘..'，不要对他们进行操作
                if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
                    $this->addFileToZip($path."/".$filename, $zip,$replace);
                }else{ //将文件加入zip对象
                    $zip->addFile($path."/".$filename);
                    $zip->renameName($path . "/" . $filename,str_replace($replace,"",$path).'/'.$filename);
                }
            }
        }
        @closedir($handler);
    }
}

