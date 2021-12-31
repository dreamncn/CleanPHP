<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

/**
 * File sql
 *
 * @package app\core\sql
 * Date: 2020/10/14 1:50 下午
 * Author: ankio
 * Desciption:sql sql的封装
 */

namespace app\core\database\sql;

use app\core\config\Config;
use app\core\database\exception\SqlCheckExceptionn;
use app\core\debug\Error;
use app\core\debug\Log;
use Exception;
use PDO;
use PDOException;
use PDOStatement;

/**
 * Class sqlExec
 * @package app\core\database\sql
 * Date: 2020/11/20 11:35 下午
 * Author: ankio
 * Desciption:数据库执行基类
 */
class sqlExec
{


    public $sqlIndex = "master";
    protected $sqlType = "mysql";
	private $sqlList = [];
	private $db = null;
	private $name = null;
	private $dbData = null;

	private static $instances=[];

	/**
		 * 设置数据库信息存储文件
		 * @param $file
	 * @param $name
		 */
	public function setDbFile($file, $name)
    {
        $this->db = $file;
        $this->name = $name;
        $this->getDbFile();
    }

	/**
		 * 获取数据库信息
		 * @return mixed|null
		 */
	private function getDbFile()
    {
        if ($this->db !== null && $this->name !== null) {
            $this->dbData = Config::getInstance($this->name)->setLocation($this->db)->get();
        } else {
            $this->dbData = Config::getInstance("db")->get();
        }
        return $this->dbData;

    }

	/**
		 * 设置数据库
		 * @param $sqlIndex
		 * @return $this
		 */
	public function setDatabase($sqlIndex)
    {
        $this->getDbFile();
        $this->sqlIndex = $sqlIndex;
        $this->sqlType = isset($this->getDbData()[$sqlIndex]['type']) ? $this->getDbData()[$sqlIndex]['type'] : "mysql";
        return $this;
    }

	/**
		 * 获取数据库数据
		 * @return mixed|null
		 */
	public function getDbData()
    {
        if ($this->dbData === null) {
            return $this->getDbFile();
        } else return $this->dbData;
    }

	/**
		 * 清空数据表
		 * @param $string
		 * @return false|mixed
		 */
	public function emptyTable($string)
    {
        switch ($this->sqlType) {
            case "sqlite2":
            case "sqlite3":
                return $this->execute("DELETE FROM '$string';");
            case "mysql":
                return $this->execute("TRUNCATE TABLE '$string';");
        }

        return $this->execute("TRUNCATE TABLE '$string';");
    }


    /**
     * 数据库执行
     * @param         $sql
     * @param array $params
     * @param false $readonly
     * @return array|int
     * @throws Exception
     */
	public function execute($sql, $params = [], $readonly = false)
    {

        $start = microtime(true);
        /**
         * @var $sth PDOStatement
         */
        $instance =  $this->dbInstance($this->getDbData()[$this->sqlIndex]);
        $sth = $instance->prepare($sql);

        if ($sth == false){
            $errorInfo = $instance->errorInfo();
            throw new SqlCheckExceptionn($sql,$errorInfo);
        }

        if (is_array($params) && !empty($params)) foreach ($params as $k => $v) {
            if (is_int($v)) {
                $data_type = PDO::PARAM_INT;
            } elseif (is_bool($v)) {
                $data_type = PDO::PARAM_BOOL;
            } elseif (is_null($v)) {
                $data_type = PDO::PARAM_NULL;
            } else {
                $data_type = PDO::PARAM_STR;
            }

            $sth->bindValue($k, $v, $data_type);
        }
        if ($sth->execute()) {
            $end = microtime(true) - $start;
            if (isDebug()) {
                Log::debug('sql', $sql);
                $sqlDefault = $sql;
                foreach ($params as $k => $v) {
                    $sqlDefault = str_replace($k, "\"$v\"", $sqlDefault);
                }
                Log::debug('sqlTranslate', $sqlDefault);
                Log::debug('sqlTranslate', strval($end * 1000) . "ms");
                $this->sqlList[] = [$sql, $sqlDefault, strval($end * 1000) . "ms"];
            }

            return $readonly ? $sth->fetchAll(PDO::FETCH_ASSOC) : $sth->rowCount();

        }
        $err = $sth->errorInfo();
        throw new SqlCheckExceptionn($sql,$err);

    }

    /**
     * 获取数据库对象
     * @param $db_config
     * @return PDO|null
     * @throws Exception
     */
	public function dbInstance($db_config)
    {

        $dsn = [
            "mysql" => "mysql:dbname={$db_config['db']};host={$db_config['host']};port={$db_config['port']}",
            "sqlite3" => "sqlite:".APP_DIR.$db_config['host'],
            "sqlite2" => "sqlite:".APP_DIR.$db_config['host'],
            "sqlserver" => "odbc:Driver={SQL Server};Server={$db_config['host']};Database={$db_config['db']}",
        ];
        $connectData = "";


        try {
            if (!isset($dsn[$this->sqlType]))
                throw new Exception("数据库错误: 我们不支持该类型数据库.({$this->sqlType})");
            $connectData = $dsn[$this->sqlType];
            Log::debug("clean",". 当前数据库信息：  {$connectData}");
            $key=md5($connectData);
           if(isset(self::$instances[$key]))return self::$instances[$key];

            self::$instances[$key]=new PDO(
                $connectData,
                $db_config['username'],
                $db_config['password'],
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'' . $db_config['charset'] . '\'',
                    PDO::ATTR_PERSISTENT => true,
                ]);
	        return self::$instances[$key];
        } catch (PDOException $e) {
            throw new Exception('数据库错误: ' . $e->getMessage() . ". 数据库信息：  {$connectData}");
        }
    }

	/**
		 * 输出sql语句
		 * @return array
		 */
	public function dumpSql()
    {
        return $this->sqlList;
    }
}
