<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/3/23
 * Time: 13:51
 */

namespace app\index\controller;


class Contact extends Base
{
    public function index(){
        return $this->fetch("index/contact");
    }
}