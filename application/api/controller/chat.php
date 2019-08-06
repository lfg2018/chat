<?php
/**
 * User: 刘富国
 * Date: 2019/8/6
 * Time: 16:39
 */
namespace app\api\controller;

use app\index\model\communication;
use app\index\model\user;
use think\Controller;

class chat extends Controller
{
    function saveMessage(){
        $msg = $this->request->param('');
        $datas['fromid'] = $msg['fromid'];
        $datas['fromname'] = user::getNickName($msg['fromid']);
        $datas['toid'] = $msg['toid'];
        $datas['toname'] = user::getNickName($msg['toid']);
        $datas['content'] = $msg['data'];
        $datas['time'] = $msg['time'];
        $datas['isread'] = $msg['isread'];
        $datas['type'] = 1;
        (new communication)->insert($datas);
    }

    function getName(){
        $uid = $this->request->param('uid');
        return user::getNickName($uid);
    }

    function getHead(){
        $fromid = $this->request->param('fromid');
        $toid = $this->request->param('toid');
        return ['from_head'=> user::getHead($fromid),'toid'=>user::getHead($toid)];
    }

}