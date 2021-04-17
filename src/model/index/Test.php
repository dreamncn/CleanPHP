<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/


namespace app\model\index;
use app\vendor\mvc\Model;

class Test extends Model{

    public function __construct()
    {
        parent::__construct("testTable");
        $this->execute("create table if not exists testTable(id int primary key auto_increment ,name varchar(32)) ");
        $this->insert(SQL_INSERT_IGNORE)->keyValue(['name'=>"wang"])->commit();
        $this->insert(SQL_INSERT_IGNORE)->keyValue(['name'=>"hi"])->commit();
        $this->insert(SQL_INSERT_IGNORE)->keyValue(['name'=>"111"])->commit();
        $this->insert(SQL_INSERT_IGNORE)->keyValue(['name'=>"limi"])->commit();
        $this->insert(SQL_INSERT_IGNORE)->keyValue(['name'=>"whar"])->commit();
        $this->insert(SQL_INSERT_IGNORE)->keyValue(['name'=>"iisisis"])->commit();
        $this->insert(SQL_INSERT_IGNORE)->keyValue(['name'=>"nonon"])->commit();
        $this->insert(SQL_INSERT_IGNORE)->keyValue(['name'=>"000999"])->commit();
        $this->insert(SQL_INSERT_IGNORE)->keyValue(['name'=>"789090"])->commit();
    }
    public function get($id){
        return $this->select()->where(['id'=>$id])->commit();
    }
}
