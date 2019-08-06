<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class Index extends Controller
{
    public function index()
    {
        $fromid = $this->request->param('fromid');
        $toid = $this->request->param('toid');
        $this->assign('fromid',$fromid);
        $this->assign('toid',$toid);
        return $this->fetch();
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
