<?php
/**
 * User: 刘富国
 * Date: 2019/8/6
 * Time: 16:14
 */
namespace app\index\model;

use think\Model;

class user extends Model
{
    public static function  getNickName($uid){
        return self::field('nickname')->where('id',$uid)->find();
    }

    public static function getHead($uid){
        return self::field('headimgurl')->where('id',$uid)->find();
    }
}