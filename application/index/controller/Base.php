<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/3/20
 * Time: 17:13
 */
namespace app\index\controller;

use think\captcha\Captcha;
use think\Controller;
use think\Session;


class Base extends Controller
{
    public function _initialize(){

    }
    public function isLogin(){
        $user = Session::get("UsernameID");
        if(isset($user)){
            return true;
        }else{
            return false;
        }
    }
    public function captcha(){
        $captcha = new Captcha();
        $captcha->length = 4;
        $captcha->fontSize = 30;
        return $captcha->entry();
    }
}