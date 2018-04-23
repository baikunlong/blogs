<?php

namespace app\index\controller;

use think\Controller;
use think\exception\DbException;
use think\facade\Request;
use think\facade\Session;

class Users extends MyInterceptor
{
    /**
     * @return \think\response\View 注册页面
     */
    public function vRegister()
    {
        return view('/register');
    }

    public function register()
    {
        $uname = Request::request('uname');
        $upwd = Request::request('upwd');
        $head = $this->request->file('uhead');
        if (!trim($uname) != '' || !trim($upwd) != '') return '名字或密码空。';
        if (!isset($head)) return '未上传头像';
        if (!captcha_check(Request::request('captcha'))) return '验证码错误';
        $type = $head->getInfo('type');//判断是否为图片
        $ok = strpos($type, 'image');
        if ($ok !== 0) return '头像格式不对';

        $file = false;//文件是否上传标志
        if (isset($head))
            $file = $head->move('uploads');
        if ($file) {//上传成功
            echo '图片后缀' . $file->getExtension() . '<br>';
            echo '图片目录' . $file->getSaveName() . '<br>';
            echo '图片名字' . $file->getFilename() . '<br>';
        } else {
            // 上传失败获取错误信息
            return $this->error('注册失败了，上传文件失败！', 'vregister', '', '10');
        }
        //写入数据库
        try {
            $users = \app\index\model\Users::create(['uname' => $uname, 'upwd' => $upwd, 'uhead' => $file->getSaveName()]);
            if(session_status()!==PHP_SESSION_ACTIVE) session_start();//开启session
            Session::set('users', $users);
        } catch (\Exception $e) {
            return '用户注册失败,名字重复或者长度太长';
        }
        return $this->success('注册成功！，用户名：' . $uname, 'main', '', '10');
    }

    /**
     * @return \think\response\View 登陆页
     */
    public function vlogin(){
        return view('/login');
    }

    public function login(){
        $uname = Request::param('uname');
        $upwd = Request::param('upwd');
        $data =['uname'=>$uname,'upwd'=>$upwd];
        try {
            $users = \app\index\model\Users::get($data);
            if(isset($users)){
                if(session_status()!==PHP_SESSION_ACTIVE) session_start();//开启session
                Session::set('users', $users);
                return redirect('main');
            }
            return '用户名或密码错误';
        } catch (DbException $e) {
            return '登陆失败';
        }
    }


    public function getHead($uname=''){
        try {
            $users = \app\index\model\Users::get(['uname' => $uname]);
        } catch (DbException $e) {
            return "visitor";
        }
        if(!isset($users)) return "visitor.jpg";
        return $users->uhead;
    }



    /**
     * @return \think\response\View 主页面
     */
    public function main()
    {
        return view('/main');
    }

}