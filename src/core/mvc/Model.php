<?php
    /*******************************************************************************
     * Copyright (c) 2022. CleanPHP. All Rights Reserved.
     ******************************************************************************/
    
    namespace core\mvc;
    
    
    
    use core\database\Sql;

    /**
     * Class Model
     * @package core\mvc
     * Date: 2020/12/3 12:21 上午
     * Author: ankio
     * Description: 模型类
     */
    class Model extends Sql
    {
        
        protected static array $instance= [];

        public static function getInstance()
        {
            $classFullName = get_called_class();
            if(!isset(self::$instance[$classFullName])||self::$instance[$classFullName]==null) {
                self::$instance[$classFullName]  = new static($classFullName::$table);
            }
            
            self::$instance[$classFullName]->setDb();
            
            return self::$instance[$classFullName];
            
        }


        public function __construct(string $tableName = '')
        {
            parent::__construct($tableName);
            $this->setDb();
        }

        public function setDb()
        {
            //手动设置默认数据库位置
            $this->setDbLocation(APP_CONF,"db")->setDatabase("master");
            // print_r("table:$table_name\n");
        }
        
        
        /**
         * 设置选项
         * @param $idName string 唯一键名
         * @param $id string 唯一键值
         * @param $opt string key名
         * @param $val string key值
         * @return mixed
         */
        public function setOption(string $idName, string $id, string $opt, string $val)
        {
            return $this->update()->where([$idName => $id])->set([$opt => $val])->commit();
        }
        
        
    }
