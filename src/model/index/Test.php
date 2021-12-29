<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/


namespace app\model\index;
use app\core\mvc\Model;

class Test extends Model{

    public function __construct()
    {

        parent::__construct("log");
        $this->setDatabase("sqlite_demo");
        $this->execute("drop table if exists log;");
        $this->execute(
            "create table log(
    id INTEGER primary key autoincrement ,
    name varchar(200), 
    urls varchar(200),
    ip varchar(200)) ");
        $this->insert(SQL_INSERT_NORMAL)->keyValue(['urls'=>"wang"])->commit();
        $this->insert(SQL_INSERT_NORMAL)->keyValue(['name'=>"hi"])->commit();
        $this->insert(SQL_INSERT_NORMAL)->keyValue(['ip'=>"111"])->commit();
        $this->insert(SQL_INSERT_NORMAL)->keyValue(['name'=>"limi"])->commit();
        $this->insert(SQL_INSERT_NORMAL)->keyValue(['name'=>"whar"])->commit();
        $this->insert(SQL_INSERT_NORMAL)->keyValue(['name'=>"iisisis"])->commit();
        $this->insert(SQL_INSERT_NORMAL)->keyValue(['name'=>"nonon"])->commit();
        $this->insert(SQL_INSERT_NORMAL)->keyValue(['name'=>"000999"])->commit();
        $this->insert(SQL_INSERT_NORMAL)->keyValue(['name'=>"789090"])->commit();
    }
    public function get($id){
        return $this->select()->where(['id'=>$id])->commit();
    }
    
    public function testInit(){

        $data = $this->select()->commit();
        dump("当前数据库数据");
        dump($data);
        $this->insert(SQL_INSERT_NORMAL)->keyValue([
            'urls' => '1233', 'ip' => "333333",
        ])->commit();
        $this->insert(SQL_INSERT_NORMAL)->keyValue([
            'urls' => 'okkkk', 'ip' => "12.041232",
        ])->commit();

        $data = $this->select()->commit();
        dump("执行2次插入操作，当前数据库数据");
        dump($data);
        $this->delete()->where(['id' => 1])->commit();
        $data = $this->select()->commit();
        dump("删除id为1的数据，当前数据库数据");
        dump($data);
        $this->update()->where(['id' => 2])->set(["urls" => "213131213"])->commit();
        $data = $this->select()->commit();
        dump("更新id为2的数据，当前数据库数据");
        dump($data);
        dump("所有执行过的sql语句以及运行时间");
        dump($this->dumpSql());

        dump("事务开始");
        $this->beginTransaction();
        $this->insert(SQL_INSERT_NORMAL)->keyValue(['urls' => "你是hh"])
            ->commit();
        dump("事务 执行插入 并未提交");
        dump($this->select()->commit());
        dump("所有执行过的sql语句以及运行时间");
        dump($this->dumpSql());

        $this->commit();
        dump("事务提交");
        dump("事务开始");
        $this->beginTransaction();
        $this->insert(SQL_INSERT_NORMAL)->keyValue(['urls' => "12222"])->commit();
        $this->update()->set(["ip" => 45456])->where(['urls' => "大萨达"])
            ->commit();
        dump("事务 执行插入、更新 并未提交");
        dump($this->select()->commit());
        $this->rollBack();
        dump("事务 回滚");
        dump($this->select()->commit());
    }
}
