<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

/**
 * File delete
 *
 * @package app\core\sql
 * Date: 2020/10/14 11:20 下午
 * Author: ankio
 * Description:delete SQL
 */

namespace app\core\database\sql;


/**
 * Class Delete
 * @package app\core\database\sql
 * Date: 2020/11/22 10:51 下午
 * Author: ankio
 * Description:删除封装
 */
class Delete extends sqlBase
{
	/**
		 * 初始化
		 * @return $this
		 */
	public function delete()
    {
        $this->opt = [];
        $this->opt['tableName'] = $this->tableName;
        $this->opt['type'] = 'delete';
        $this->bindParam = [];
        return $this;
    }

	/**
		 * 设置表
		 * @param $table_name
		 * @return Delete
		 */
	public function table($table_name)
    {
        return parent::table($table_name);
    }

	/**
		 * 设置条件
		 * @param $conditions
		 * @return Delete
		 */
	public function where($conditions)
    {
        return parent::where($conditions);
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
        $sql .= $this->getOpt('DELETE FROM', 'tableName');
        $sql .= $this->getOpt('WHERE', 'where');
        $this->traSql = $sql . ";";

    }
}
