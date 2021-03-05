<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/



function help()
{
    echo <<<EOF
Usage: php clean.php [options] 

Options:
  check                     Check your code and give you some suggestions for improvement.
  release                   Publish your code, which will output your code as a compressed package to the release folder, and remove some unnecessary release content.
  run [index/main/index]    Run cleanphp in command line mode.
EOF;
    return null;
}

function run($argv)
{

    if (!isset($argv[2])||($argv!="clean_check"&&$argv!="clean_release"&&$argv!="clean_clean")) return help();
    $_SERVER['CLEAN_CONSOLE'] = true;
    $_SERVER["HTTP_HOST"] = "localhost";
    if(is_array($argv))
        $_SERVER["REQUEST_URI"] = "/" . $argv[2];
    else
        $_SERVER["REQUEST_URI"] = $argv;

    include './public/index.php';
    return null;
}

function release()
{
    run("clean_release");
}

function check()
{
    run("clean_check");
}
function clean()
{
    run("clean_clean");
}

if (!isset($argv[1]))
    return help();

switch ($argv[1]) {
    case "check":
        check();
        break;
    case "release":
        release();
        break;
    case "run":
        run($argv);
        break;
    default:
        help();
}






