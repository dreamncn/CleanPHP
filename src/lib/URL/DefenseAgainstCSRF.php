<?php


namespace app\lib\URL;

use app\core\web\Cookie;
use app\core\web\Request;
use app\core\web\Response;
use app\core\web\Session;
use http\Header;

/**
 * Class DefenseAgainstCSRF
 * @package Security\URLSecurity
 */
class DefenseAgainstCSRF
{
    /**
     * DefenseAgainstCSRF constructor.
     */
    public function __construct()
    {
    }


    public function verifyCSRFToken()
    {
        $csrf=Session::getInstance()->get("csrftoken");
        if($csrf===null)return false;
        $bool=$csrf===Cookie::getInstance()->get("csrftoken");
        Session::getInstance()->set("csrftoken",null);
        return $bool;
    }


    /**
     * @param $session
     * @param $salt
     * @return string
     */
    private function getCSRFToken($session, $salt)
    {
        $token=md5(md5($session.'|'.$salt).'|'.$salt);
        Cookie::getInstance()->set("csrftoken",$token);
        Session::getInstance()->set("csrftoken",$token,20*60);
        return $token;
    }

    public function setCSRFToken($session){
        return $this->getCSRFToken($session,time());
    }
}
