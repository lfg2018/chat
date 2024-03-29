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
use think\facade\Env;

class Chat extends Controller
{
    /**
     * 保存历史记录
     */
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

    /**
     * 获取用户昵称
     * @return array|null|\PDOStatement|string|\think\Model
     */
    function getName(){
        $uid = $this->request->param('uid');
        return user::getNickName($uid);
    }

    /**
     * 获取头像
     * @return array
     */

    function getHead(){
        $fromid = $this->request->param('fromid');
        $toid = $this->request->param('toid');
        return ['from_head'=> user::getHead($fromid),'to_head'=>user::getHead($toid)];
    }

    /**
     * 获取历史记录
     * @return array|\PDOStatement|string|\think\Collection
     */
    function getRecode(){
        $fromid = $this->request->param('fromid');
        $toid = $this->request->param('toid');
        return communication::getRecord($fromid,$toid);
    }

    /**
     * 上传文件
     */
    function uploadImg(){
        $file = $_FILES['file'];
        $fromid = $this->request->param('fromid');
        $toid = $this->request->param('toid');
        $suffix = strtolower(strrchr($file['name'],'.'));
        $type = ['.jpg','.jpeg','gif','png'];
        if(!in_array($suffix,$type)) return ['status'=>'error','msg'=>'error img tye'];
        if($file['size']/1024 > 5120) return ['status'=>'error','msg'=>'img too large'];
        $filename = uniqid('chat_img_',false);
        $uploadpath = Env::get('root_path').'\public\\Upload\\img\\';
        $fileUp = $uploadpath.$filename.$suffix;
        $ret = move_uploaded_file($file['tmp_name'],$fileUp);
        if(!$ret) return ['status'=>'error','msg'=>'upload error'];
        $name = '\\Upload\\\\img\\\\'.$filename.$suffix;
        $data['content'] = $name;
        $data['fromid'] = $fromid;
        $data['toid'] = $toid;
        $data['fromname'] = user::getNickName($fromid);
        $data['toname'] = user::getNickName($toid);
        $data['time'] = time();
        $data['isread'] = 0;
        $data['type'] = 2;
        $retId = communication::saveDate($data);
        if($retId) return ['status'=>'ok','img_name' =>$name];
        return ['status'=>'error','msg'=>'save upload error'];
    }

}