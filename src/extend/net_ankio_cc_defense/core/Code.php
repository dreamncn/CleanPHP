<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\extend\net_ankio_cc_defense\core;


use app\core\debug\Log;
use app\core\web\Session;

class Code
{
    public static function getImg(){
        $largeur = 120;
        $hauteur = 40;
        $longueur = 6;
        $liste = '134679ACEFGHIJLMNPRTUVWXY';
        $code = '';
        $image = @imagecreate($largeur, $hauteur) or die('Impossible d\'initializer GD');
        for ($i = 0; $i < 10; $i++) {
            imageline($image,
                mt_rand(0, $largeur),
                mt_rand(0, $hauteur),
                mt_rand(0, $largeur),
                mt_rand(0, $hauteur),
                imagecolorallocate($image, mt_rand(200, 255),
                    mt_rand(200, 255),
                    mt_rand(200, 255)
                )
            );
        }

        for ($i = 0, $x = 0; $i < $longueur; $i++) {
            $charactere = substr($liste, rand(0, strlen($liste) - 1), 1);
            $x += 10 + mt_rand(0, 10);
            imagechar($image, mt_rand(3, 4), $x, mt_rand(4, 20), $charactere,
                imagecolorallocate($image, mt_rand(0, 155), mt_rand(0, 155), mt_rand(0, 155)));
            $code .= ($charactere);
        }

        header('Content-Type: image/jpeg');
        imagejpeg($image);
        imagedestroy($image);
        Session::getInstance()->set('code',$code,time()+5*60) ;
        //5分钟有效期
    }

    public static function check(){
        $code=strval(arg("code"));
        $session_code=Session::getInstance()->get("code");
        Session::getInstance()->delete("code");
        return strtolower($code)===strtolower($session_code);
    }
}