<?php
namespace app\index\controller;

use think\Session;


class Index extends Base
{
    public function index()
    {
        return $this->fetch();

//        return $captcha->entry();
    }
}
