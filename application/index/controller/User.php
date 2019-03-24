<?php
namespace app\index\controller;
use think\captcha\Captcha;
use think\Session;
use think\Validate;
use think\Controller;

/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/3/15
 * Time: 19:03
 */
class User extends controller{
    private  $obj;
    private  $validate;
    public function _initialize() {
        $this->obj = model("User");
        $this->validate = Validate('User');
    }
    public function index(){
        $form = '415813765@qq.com';
        $name = '隔壁老王';
        $set = [
            '415813765@qq.com'
        ];
        $title = 'text';
        $message = 'kongkong';
        \Mail::Qmail($form ,$name ,$set ,$title ,$message);
    }
    public function register(){
        $data = input("post.");
        var_dump($data);
        if($this->validate->scene('register')->check($data)) {
            if($data['validate'] != Session::get("validate")){
                return $this->error("验证码错误");
            }
            $result = $this->obj->find(['username' => $data['username']]);
            if (!isset($result)) {
                unset($data['validate']);
                $data['password'] = md5($data['password']);
                $result = $this->obj->save($data);
                if ($result) {
                    return $this->success("注册成功", url("index/index"));
                } else {
                    return $this->error("注册失败");
                }
            } else {
                return $this->error("用户名已存在");
            }
        }else{
            return $this->error($this->validate->getError());
        }
    }
    public function login(){
        if($this->request->isPost()){
            $data = input("post.");
            var_dump($data);
            //$captcha = new Captcha();
            //var_dump($captcha->check($data['captcha']));
            $map['username'] = ['eq', $data['username']];
            $map['password'] = ['eq', md5($data['password'])];
            if($this->validate->scene('login')->check($data)) {
                $message = $this->obj->where($map)->find();
                var_dump($message);
                if ($message) {
                    if ($message['status'] == '启用') {
                        $update = [
                            'last_login_ip' => $_SERVER['SERVER_ADDR'],
                            'last_login_time' => time(),
                        ];
                        $result_update = $this->obj->where($map)->update($update);
                        $result = $this->obj->where($map)->setInc('login_times', 1);
                        if ($result && $result_update) {
                            Session::set('UsernameID',$message['id']);
                            Session::set('Username',$message['username']);
                            return $this->redirect(url("index/index"));
                        } else {
                            return $this->error("登录异常");
                        }
                    } else {
                        return $this->error("账户异常");
                    }
                }else{
                    return $this->error("用户名不存在或者密码不正确");
                }
            }else{
                return $this->error($this->validate->getError());
            }
        }else{
            return $this->redirect(url('index/index'));
        }

    }
    public function sendEmail(){
        $validate = "";
        for($i=0 ; $i<6; $i++){
            $number = rand(0,9);
            $validate = $validate."".$number;
        }
        Session::delete("validate");
        Session::set("validate","$validate","User");
        $set = '415813765@qq.com';
        $title = 'text';
        $message = "<h3><a>".$validate."</a></h3>";
        $name = "laowang";
        \Mail::Qmail($set ,$name ,$title ,$message);
    }
    public function logout(){
        Session::delete("UsernameID");
        $this->redirect(url('index/index'));
    }
}
/**
 * session值验证身份尚未设置
 */