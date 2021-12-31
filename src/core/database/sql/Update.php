<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\core\database\sql;

/**
 * Class Update
 * @package app\core\database\sql
 * Date: 2020/11/22 10:55 下午
 * Author: ankio
 * Description: 更新
 */
class Update extends sqlBase
{
	/**
		 * 初始化
		 * @return $this
		 */
	public function update()
    {
        $this->opt = [];
        $this->opt['tableName'] = $this->tableName;
        $this->opt['type'] = 'update';
        $this->bindParam = [];
        return $this;
    }

	/**
		 * 设置表
		 * @param $table_name
		 * @return Update
		 */
	public function table($table_name)
    {
        return parent::table($table_name);
    }

	/**
		 * 设置条件
		 * @param $conditions
		 * @return Update
		 */
	public function where($conditions)
    {
        return parent::where($conditions);
    }

	/**
		 * 设置更新字段信息
		 * @param $row array
		 * @return $this
		 */
	public function set($row)
    {
        $values = [];
        $set = '';
        foreach ($row as $k => $v) {
            if (is_int($k)) {
                $set .= $v . ',';
                continue;
            }
            $values[":_UPDATE_" . $k] = $v;
            $set .= "`{$k}` = " . ":_UPDATE_" . $k . ',';
        }
        $set = rtrim($set, ",");
        $this->bindParam += $values;
        $this->opt['set'] = $set;
        return $this;
    }

	/**
		 * 提交
		 * @return mixed
		 */
	public function commit()
    {
        $this->translateSql();
        return $this->sql->execute($this->traSql, $this->bindParam, false);
    }

	/**
		 * 编译
		 */
	private function translateSql()
    {
        $sql = '';
        $sql .= $this->getOpt('UPDATE', 'tableName');
        $sql .= $this->getOpt('SET', 'set');
        $sql .= $this->getOpt('WHERE', 'where');
        $this->traSql = $sql . ";";


    }
}
