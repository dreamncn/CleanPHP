<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\core\database\exception;

class SqlCheckExceptionn extends \Exception
{
    public function __construct($sql,$message = null)
    {
        $errorInfo = "\nSQL语句预编译失败，存在以下问题：\n";
        $errorInfo.= "\nSQL编译语句为：\n".$sql."\n";
        if(is_array($message)&&sizeof($message)==3){
            $errorInfo.= "\n错误信息：\n".$message[2]."\n";
        }
        parent::__construct($errorInfo, 500, null);
    }

}