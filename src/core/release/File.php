<?php
/**
 * Name         :File.php
 * Author       :dreamn
 * Date         :2020/2/12 22:47
 * Description  :文件文件夹io操作类
 */
namespace app\core\release;


/**
 * Class File
 * @package includes
 */
class File {

    public static function delFile($fileName){
        if(is_file($fileName))unlink($fileName);
    }
    /**
     * 文件夹删除
     * @param $dirname
     * @return bool|string
     */
    public static function del($dirname)
    {
        if (!is_dir($dirname)) {
            return " $dirname is not a dir!";
        }
        $handle = opendir($dirname); //打开目录
        while (($file = readdir($handle)) !== false) {
            if ($file != '.' && $file != '..') {
                //排除"."和"."
                $dir = $dirname .'/' . $file;
                is_dir($dir) ? self::del($dir) : unlink($dir);
            }
        }
        closedir($handle);
        $result = rmdir($dirname) ? true : false;
        return $result;
    }
    static function cleanDir($path)
    {
        //如果是目录则继续
        if (is_dir($path)) {
            //扫描一个文件夹内的所有文件夹和文件并返回数组
            $p = scandir($path);
            foreach ($p as $val) {
                //排除目录中的.和..
                if ($val != "." && $val != "..") {
                    //如果是目录则递归子目录，继续操作
                    if (is_dir($path . $val)) {
                        //子目录中操作删除文件夹和文件
                        self::cleanDir($path . $val . '/');
                        //目录清空后删除空文件夹
                          @rmdir($path.$val.'/');
                    } else {
                        //如果是文件直接删除
                        unlink($path . $val);
                    }
                }
            }
        }
    }
    /**
     * 文件夹文件拷贝
     *
     * @param string $src 来源文件夹
     * @param string $dst 目的地文件夹
     * @return bool
     */
    public static function copyDir($src = '', $dst = '')
    {
        if (empty($src) || empty($dst))
        {
            return false;
        }

        $dir = opendir($src);
        self::mkDir($dst);
        while (false !== ($file = readdir($dir)))
        {
            if (($file != '.') && ($file != '..'))
            {
                if (is_dir($src . '/' . $file))
                {
                    self::copyDir($src . '/' . $file, $dst . '/' . $file);
                }
                else
                {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);

        return true;
    }

    /**
     * 创建文件夹
     *
     * @param string $path 文件夹路径
     * @param int $mode 访问权限
     * @param bool $recursive 是否递归创建
     * @return bool
     */
    public static function mkDir($path = '', $mode = 0777, $recursive = true)
    {
        clearstatcache();
        if (!is_dir($path))
        {
            mkdir($path, $mode, $recursive);
            return chmod($path, $mode);
        }

        return true;
    }


    /**
     * 判断是否符合命名规则
     * @param $name
     * @return bool
     */
    public static function isName($name){
        $isMatched = preg_match_all('/^[0-9a-zA-Z_]+$/', $name);
        if($isMatched)return true;
        else {

            return false;
        }
    }





}
