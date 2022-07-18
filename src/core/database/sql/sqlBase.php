<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/



namespace core\database\sql;
use core\error\AppError;
use PDO;

/**
 * Class sqlBase
 * @package core\database\sql
 * Date: 2020/11/20 11:32 下午
 * Author: ankio
 * Description:sql数据对象构成的基类
 */
class sqlBase
{

    protected array $opt = [];//封装常见的数据库查询选项
    protected string $tableName;
    protected ?string $traSql = null;//编译完成的sql语句
    protected array $bindParam = [];//绑定的参数列表
    protected ?sqlExec $sql = null;


	/**
* sqlBase constructor.
* @param $tableName
* @param $sqlDetail
*/
	public function __construct($tableName, $sqlDetail)
    {
        if (!class_exists("PDO") || !in_array("mysql", PDO::getAvailableDrivers(), true)) {
            new AppError("请安装PDO拓展并启用",APP_CONF."db.yml","type");
        }
        //初始化基础数据
        $this->opt['type'] = 'select';
        $this->opt['tableName'] = $tableName;
        $this->tableName = $tableName;
        $this->sql = $sqlDetail;
    }

	/**
	* 获取存储的数据选项
	* @param $head
    * @param $opt
	* @return string
	*/
	protected function getOpt($head, $opt): string
    {
        if (isset($this->opt[$opt])) return ' ' . $head . ' ' . $this->opt[$opt] . ' ';
        return ' ';
    }


	/**
	* 设置表名
	* @param string $tableName
	* @return $this
	*/
	protected function table(string $tableName)
    {
        $this->tableName = $tableName;
        $this->opt['tableName'] = $tableName;
        return $this;
    }



	/**
	* 设置查询条件
	* @param array $conditions 条件内容，必须是数组,格式如下["name"=>"张三","i > :hello",":hello"=>"hi"," id in (:in)",":in"=>"1,3,4,5"]
	* @return $this
	*/
	protected function where(array $conditions)
    {
        if (is_array($conditions) && !empty($conditions)) {
            $sql = null;
            $join = [];
            reset($conditions);

            foreach ($conditions as $key => &$condition) {
                if (is_int($key)) {
                    $isMatched = preg_match_all('/in(\s+)?\((\s+)?(:\w+)\)/', $condition, $matches);

                    if($isMatched){
                        for($i = 0;$i<$isMatched;$i++){
                            $key2 = $matches[3][$i];
                            if(isset($conditions[$key2])){
                                $value = $conditions[$key2];
                                unset($conditions[$key2]);
                                $values = explode(",",$value);
                                $new = "";
                                $len = sizeof($values);
                                for($j=0;$j<$len;$j++){
                                    $new.=$key2."_$j";
                                    $conditions[$key2."_$j"]=($values[$j]);
                                    if($j!==$len-1){
                                        $new.= ",";
                                    }
                                }
                                $condition  = str_replace($key2,$new,$condition);
                                //condition改写
                            }

                        }
                    }
                    //识别Like语句
                    $isMatched = preg_match_all('/like\s+(\')?(%)?(:\w+)(%)?(\')?/', $condition, $matches);

                    if($isMatched){
                        for($i = 0;$i<$isMatched;$i++){
                            $left_1 = $matches[1][$i];
                            $key2 = $matches[3][$i];
                            $left =  $matches[2][$i];
                            $right = $matches[4][$i];
                            $right_1 = $matches[5][$i];
                            if(isset($conditions[$key2])){

                                $value = $conditions[$key2];

                                unset($conditions[$key2]);
                                $value = "$left$value$right";
                                $conditions[$key2] = $value;
                                $condition  = str_replace( "$left_1$left$key2$right$right_1",$key2,$condition);

                                //condition改写
                            }

                        }
                    }


                    $join[] = $condition;
                    unset($conditions[$key]);
                    continue;
                }
                $keyRaw = $key;
                $key = str_replace('.', '_', $key);
                if (substr($key, 0, 1) != ":") {
                    unset($conditions[$keyRaw]);
                    $conditions[":_WHERE_" . $key] = $condition;
                    $join[] = "`" . str_replace('.', '`.`', $keyRaw) . "` = :_WHERE_" . $key;
                }

            }
            if (!$sql) $sql = join(" AND ", $join);

            $this->opt['where'] = $sql;
            $this->bindParam += $conditions;
        }
        return $this;
    }

}
