<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/3/15
 * Time: 21:30
 */
namespace app\index\controller;
use think\Controller;
use think\Session;

class Sign extends Controller{
    private  $obj;
    private  $validate;
    public function _initialize() {
        $this->obj = model("Sign");
//        $this->validate = Validate('User');
    }
    public function index(){
        if(!Session::has("UsernameID")){
            return $this->error("未登录",url('user/login'));
        }
        $id = Session::get("UsernameID");
        $time = strtotime(date("Y-m-d", time()));
        $where['user_id'] = ['eq', $id];
        $where['sign_date'] = ['eq', $time];
        $weektime = $this->obj->getWeekendTime($id ,1);
        $weekalltime = $this->obj->getWeekendTime($id ,0);
        $nowdatetime = $this->obj->getTodayTime($id);
        $result = $this->obj->where($where)->find();
        if(!$result){
            $status = 0;
        }else{
            if($result['status'] == 1){
                $status = 1;
            }else{
                $status = 0;
            }
        }
        return $this->fetch('sign/psetting',[
            'weektime'     =>   $weektime,
            'weekalltime'  =>   $weekalltime,
            'nowdatetime'  =>   $nowdatetime,
            'status'       =>   $status,
        ]);
    }
    public function onSign(){
        if(!Session::has("UsernameID")){
            return $this->error("未登录",url('user/login'));
        }
        $id = Session::get("UsernameID");
        $time = strtotime(date("Y-m-d", time()));
        $where['user_id'] = ['eq', $id];
        $where['sign_date'] = ['eq', $time];
        $result = $this->obj->where($where)->find();

        if(!$result){
            $date = [
                'user_id'    =>    $id,
                'sign_date'  =>    $time,
                'status'     =>    1,
            ];
            $this->obj->save($date);
        }else{
            if($result['status'] == 0){
                $this->obj->where($where)->update(['status' => 1]);
            }else{
                $sign_time = $result['sign_time'];
                $update_time = strtotime($result['update_time']);
                $sign_time += time()-$update_time;
                $this->obj->where($where)->update([
                    'status'     =>  0,
                    'sign_time'  =>  $sign_time,
                    'update_time'=>  time(),
                ]);
            }
        }
        $array = array();
        $id = Session::get("UsernameID");
        $array['week'] = $this->obj->getWeekendTime($id ,0);
        $array['today'] = $this->obj->getTodayTime($id);
        return json($array);
//        $this->obj->where('id', 11)->update(['sign_date' => strtotime(date("Y-m-d", time()))]);
    }
    public function showSign(){
        $array = array();
        $id = Session::get("UsernameID");
        $array['week'] = $this->obj->getWeekendTime($id ,0);
        $array['today'] = $this->obj->getTodayTime($id);
        return json($array);
}
}