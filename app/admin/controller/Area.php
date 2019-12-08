<?php
namespace app\admin\controller;
use \think\Controller;
use \think\Cookie;


class Area extends Controller
{
    public function _initialize(){
        if(!isLogin()){
            $this->redirect(ADMIN_ROUTE.'login');
        }
    }
    public function model(){
        return new \app\admin\model\Area();
    }

    public function index()
    {
        $this->assign('list',$this->model()->getCol());
        return $this->fetch('list');
    }

    //添加或编辑文章
    public function edit(){
        $id = request()->get('id');
        $area = false;
        if($id){
            $area =$this->model()->getCol($id);
        }
        $this->assign('area_id',$id);
        $this->assign('area',$area);
        return $this->fetch('edit');
    }

}
