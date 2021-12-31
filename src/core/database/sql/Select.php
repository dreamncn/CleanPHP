<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\core\database\sql;

/**
 * Class Select
 * @package app\core\database\sql
 * Date: 2020/11/22 10:52 下午
 * Author: ankio
 * Description:查询语句封装
 */
class Select extends sqlBase
{
    /**
     * @var null
     */
    protected $page = null;//开启分页的分页数据


    /**
     * 初始化
     * @param  string  $field
     * @return $this
     */
    public function select($field = "*")
    {
        $this->opt = [];
        $this->opt['tableName'] = $this->tableName;
        $this->opt['type'] = 'select';
        $this->opt['field'] = $field;
        $this->bindParam = [];
        return $this;
    }

    /**
     * 设置表
     * @param $table_name
     * @return Select
     */
    public function table($table_name)
    {
        return parent::table($table_name);
    }


    /**
     * 使用排序
     * @param $string
     * @return $this
     */
    public function orderBy($string)
    {
        $this->opt['order'] = $string;
        return $this;
    }


    /**
     * limit函数
     * @param  string  $limit
     * @return $this
     */
    public function limit($limit = '1')
    {
        unset($this->opt['page']);
        $this->opt['limit'] = $limit;
        return $this;
    }

    /**
     * 分页
     * @param  int  $start 开始
     * @param  int  $count 数量
     * @param  int  $range 最多分页
     * @return $this
     */
    public function page($start = 1, $count = 10, $range = 10)
    {
        unset($this->opt['limit']);
        $this->opt['page'] = true;
        $this->opt['start'] = $start;
        $this->opt['count'] = $count;
        $this->opt['range'] = $range;
        return $this;
    }


    /**
     * 查询条件
     * @param $conditions
     * @return Select
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
        if (isset($this->opt['page'])) {
            $sql = 'SELECT COUNT(*) as M_COUNTER ';

            // dump($this->getOpt('FROM', 'tableName'));
            // dump($this->tableName,true);

            $sql .= $this->getOpt('FROM', 'tableName');
            $sql .= $this->getOpt('WHERE', 'where');

            $sql .= $this->getOpt('ORDER BY', 'order');

            $total = $this->sql->execute($sql, $this->bindParam, true);
            $this->page = $this->pager($this->opt['start'], $this->opt['count'], $this->opt['range'], $total[0]['M_COUNTER']);
            if (!empty($this->page))
                $this->opt['limit'] = $this->page['offset'] . ',' . $this->page['limit'];
        }
        $this->translateSql();
        return $this->sql->execute($this->traSql, $this->bindParam, true);
    }


    /**
     * 分页函数
     * @param       $page
     * @param  int  $pageSize
     * @param  int  $scope
     * @param  int  $total
     * @return array|null
     */
    protected function pager($page, $pageSize = 10, $scope = 10, $total = 0)
    {
        $this->page = [
            'total_count' => $total,//总数量
            'page_size' => $pageSize,//一页大小
            'total_page' => 1,//总页数
            'first_page' => 1,//第一页
            'prev_page' => ((1 == $page) ? 1 : ($page - 1)),//上一页
            'next_page' => (($page == 1) ? 1 : ($page + 1)),//下一页
            'last_page' => 1,//最后一页
            'current_page' => $page,//当前页
            'all_pages' => [],//所有页
            'offset' => ($page - 1) * $pageSize,
            'limit' => $pageSize,
        ];
        if ($total > $pageSize) {
            $total_page = ceil($total / $pageSize);
            $page = min(intval(max($page, 1)), $total_page);
            $this->page["total_page"]=$total_page;
            $this->page["next_page"]=(($page == $total_page) ? $total_page : ($page + 1));//下一页
            $this->page["last_page"]=$total_page;
            $scope = (int)$scope;
            if ($total_page <= $scope) {
                $this->page['all_pages'] = range(1, $total_page);
            } elseif ($page <= $scope / 2) {
                $this->page['all_pages'] = range(1, $scope);
            } elseif ($page <= $total_page - $scope / 2) {
                $right = $page + (int)($scope / 2);
                $this->page['all_pages'] = range($right - $scope + 1, $right);
            } else {
                $this->page['all_pages'] = range($total_page - $scope + 1, $total_page);
            }
        }
        return $this->page;
    }

    /**
     * 编译
     */
    private function translateSql()
    {
        $sql = '';
        $sql .= $this->getOpt('SELECT', 'field');
        $sql .= $this->getOpt('FROM', 'tableName');
        $sql .= $this->getOpt('WHERE', 'where');
        $sql .= $this->getOpt('ORDER BY', 'order');
        $sql .= $this->getOpt('LIMIT', 'limit');
        $this->traSql = $sql . ";";

    }

    /**
     * 获取分页数据
     * @return array
     */
    public function getPage()
    {
        return $this->page;
    }


    /**
     * 统计查出来的数据的总数
     * @param array|string $conditions
     * @return int|mixed
     */
    public function count($conditions)
    {
        $this->where($conditions);
        $sql= "SELECT COUNT(*) AS M_COUNTER FROM " . $this->tableName." where " . $this->opt['where'];

        $count = $this->sql->execute($sql, $this->bindParam, true);
        return isset($count[0]['M_COUNTER']) && $count[0]['M_COUNTER'] ? $count[0]['M_COUNTER'] : 0;
    }

    /**
     * 对某个字段进行求和
     * @param array|string $conditions
     * @param string $param
     * @return int|mixed
     */
    public function sum($conditions, $param)
    {
        $this->where($conditions);
        $sql= "SELECT SUM($param) AS M_COUNTER FROM " . $this->tableName." where "  . $this->opt['where'];
        $count = $this->sql->execute($sql, $this->bindParam, true);
        return isset($count[0]['M_COUNTER']) && $count[0]['M_COUNTER'] ? $count[0]['M_COUNTER'] : 0;
    }



}
