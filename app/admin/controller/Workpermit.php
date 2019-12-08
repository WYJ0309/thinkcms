<?php
namespace app\admin\controller;
use \think\Controller;
use \think\Cookie;


class Workpermit extends Controller
{
    public function _initialize(){
        if(!isLogin()){
            $this->redirect(ADMIN_ROUTE.'login');
        }
    }
    public function model(){
        return new \app\admin\model\Workpermit();
    }

    public function index()
    {
        $this->assign('list',$this->model()->getCol());
        return $this->fetch('list');
    }

    //添加或编辑文章
    public function edit(){
        $id = request()->get('id');
        $permit = false;
        if($id){
            $permit =$this->model()->getCol($id);
        }
        $this->assign('w_id',$id);
        $this->assign('permit',$permit);
        return $this->fetch('edit');
    }

}
