<?php
/**
 * Created by PhpStorm.
 * User: baikunlong
 * Date: 2018/4/22
 * Time: 13:45
 */

namespace app\index\controller;


use think\Controller;
use think\facade\Session;

class MyInterceptor extends Controller
{
    public function __construct()
    {
        parent::__construct();
//        echo '控制器/方法';
        $loginca = "/blogs/public/index/users/vlogin,/blogs/public/index/users/login,
        /blogs/public/index/users/register,/blogs/public/index/users/vregister"; //未登录允许访问的控制器和方法
//        echo $_SERVER['REQUEST_URI'].strpos($loginca, $_SERVER['REQUEST_URI']);
        $users = Session::get('users');
        if(!isset($users) && strpos($loginca, $_SERVER['REQUEST_URI']) === false){
            echo '请先登陆：'.'<a href="../vlogin">点我登陆</a>';
            echo '<literal><script>window.top.location.href="../vlogin";</script></literal>';
            exit();
        }else{

        }
    }
}