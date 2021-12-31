<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\core\database\sql;


class Page
{
    private $page = [];

    public function __construct($page)
    {
        $this->page=$page;
    }
    /**
     * 总数量
     * @return mixed
     */
    public function getTotalCount(){
        return $this->page["total_count"];
    }

    /**
     * 一页大小
     * @return mixed
     */
    public function getPageSize(){
        return $this->page["page_size"];
    }

    /**
     * 总页数
     * @return mixed
     */
    public function getTotalPage(){
        return $this->page["total_page"];
    }

    /**
     * 第一页
     * @return mixed
     */
    public function getFirstPage(){
        return $this->page["first_page"];
    }

    /**
     * 上一页
     * @return mixed
     */
    public function getPrevPage(){
        return $this->page["prev_page"];
    }

    /**
     * 上一页
     * @return mixed
     */
    public function getNextPage(){
        return $this->page["next_page"];
    }

    /**
     * 最后一页
     * @return mixed
     */
    public function getLastPage(){
        return $this->page["last_page"];
    }

    /**
     * 当前页
     * @return mixed
     */
    public function getCurrentPage(){
        return $this->page["current_page"];
    }

    /**
     * 所有页
     * @return mixed
     */
    public function getAllPages(){
        return $this->page["all_pages"];
    }

    /**
     * 偏移
     * @return mixed
     */
    public function getOffset(){
        return $this->page["offset"];
    }

    /**
     *  一页大小
     * @return mixed
     */
    public function getLimit(){
        return $this->page["limit"];
    }
}