<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/
/**
 * File ${NAME}
 * Created By ankio.
 * Date : 2022/1/13
 * Time : 11:42 上午
 * Description :
 */

$str = <<<EOD
like '%:aba'
like '%:aba%'
like ':aba%'
EOD;
;
$isMatched = preg_match_all('/like\s+\'(%)?(:\w+)(%)?\'/', $str, $matches);
var_dump($isMatched, $matches);