<?php
namespace app\admin\controller\api;
use app\admin\model\Site;
use think\Controller;
use think\Cookie;
use think\Request;

class SitesApi extends Controller
{
    public function access(){
        if(!isLogin()){
            $this->redirect(ADMIN_ROUTE.'login');
        }
    }
    public function model(){
        $Site=new Site;
        return $Site;
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

    //删除栏目
    public function delete(Request $request){
        $id=$request->param('id');
        $id=explode(",",$id);
        if($id){
            if(db('area')->delete($id)){
                return ['success'=>true,'msg'=>'删除成功'];
            }
            return ['fail'=>true,'msg'=>'删除失败'];
        }
    }

}
