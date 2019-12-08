<?php
namespace app\admin\controller;
use \think\Controller;
use \app\admin\model\Site;
use think\Cookie;
use think\Request;

class Sites extends Controller
{
    public function _initialize(){
        if(!isLogin()){
            $this->redirect(ADMIN_ROUTE.'login');
        }
    }
    public function model(){
        $Site=new Site;
        return $Site;
    }

    //主页
    public function index(){
        $list=$this->model()->getList();
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function edit(Request $request){
        $query=$request->param();
        if($request->isPost()){
            if($this->model()->savaData($query)){
                Cookie::set('site_save','1');
            }else{
                Cookie::set('site_save','0');
            }
            $this->redirect(ADMIN_ROUTE.'site');
        }
        $where = $query['site_id']==1?'':2;
        $this->assign('site',$this->model()->getSite($where));
        return $this->fetch();
    }

}
