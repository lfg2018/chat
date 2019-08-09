<?php
/**
 * User: 刘富国
 * Date: 2019/8/6
 * Time: 16:56
 */

namespace app\index\model;


use think\Model;

class communication extends Model
{
    //显示聊天记录,todo 要做分页的处理
    public  static  function getRecord($fromid,$toid){
        $fromWhere = [
          ['fromid','in',[$fromid,$toid]]
        ];
        $toWhere = [
            ['toid','in',[$fromid,$toid]]
        ];
        $count = self::whereOr($fromWhere,$toWhere)->count();
        if($count > 10){
            $msg = self::whereOr($fromWhere,$toWhere)->limit($count-10,10)->order('id')->select();
        }else{
            $msg = self::whereOr($fromWhere,$toWhere)->order('id')->select();
        }
        return $msg;
    }

    public static function saveDate($data){
        return self::insertGetId($data);
    }
}