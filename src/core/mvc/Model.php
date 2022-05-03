<?php
    /*******************************************************************************
     * Copyright (c) 2022. CleanPHP. All Rights Reserved.
     ******************************************************************************/
    
    namespace app\core\mvc;
    
    
    
    use app\core\database\Sql;
    use app\core\debug\Log;
    
    /**
     * Class Model
     * @package app\core\mvc
     * Date: 2020/12/3 12:21 上午
     * Author: ankio
     * Description: 模型类
     */
    class Model extends Sql
    {
        
        protected static array $instance= [];
        public static string $table = "";
        public static function getInstance()
        {
            $classFullName = get_called_class();
            if(!in_array($classFullName,self::$instance)) {
                self::$instance[$classFullName]  = new static();
            }
            
            self::$instance[$classFullName]->setTable($classFullName::$table);
            
            return self::$instance[$classFullName];
            
        }
        
        /**
         * Model constructor.
         *
         * @param string $table_name
         */
        public function setTable(string $table_name = '')
        {
            
            //手动设置默认数据库位置
            $this->setDbLocation(APP_CONF,"db")->setDatabase("master")->table($table_name);
            
            // print_r("table:$table_name\n");
            
        }
        
        
        /**
         * 设置选项
         * @param $idName
         * @param $id
         * @param $opt
         * @param $val
         * @return mixed
         */
        public function setOption($idName, $id, $opt, $val)
        {
            return $this->update()->where([$idName => $id])->set([$opt => $val])->commit();
        }
        
        
    }
