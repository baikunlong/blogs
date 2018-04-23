<?php
/**
 * Created by PhpStorm.
 * User: baikunlong
 * Date: 2018/4/20
 * Time: 21:03
 */

namespace app\index\model;


use think\Model;

class Users extends Model
{
    protected $pk='uid';//主键，默认id
    protected $table='users';

}