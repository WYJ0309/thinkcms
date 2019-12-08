<?php
namespace app\admin\controller\api;
use think\Controller;
use think\Cookie;
use think\Request;

class WorkpermitApi extends Controller
{
    public function access(){
        if(!isLogin()){
            $this->redirect(ADMIN_ROUTE.'login');
        }
    }

    //添加或编辑栏目
    public function edit(Request $request){
        $this->access();

        $w_id=$request->param('id');
        $data=$request->param();
        unset($data['id']);

        //判断是否有id
        if($w_id){
            //更新
            db('workpermit')->where('id',$w_id)->update($data);
            Cookie::set('add_permit','1');
            $this->redirect(ADMIN_ROUTE.'workpermit/edit');
        }else{
            //添加
            $res=db('workpermit')->insert($data);
            if($res){
                Cookie::set('add_permit','1');
            }else{
                Cookie::set('add_permit','0');
            }
            $this->redirect(ADMIN_ROUTE.'workpermit');
        }
    }

    //删除栏目
    public function delete(Request $request){
        $id=$request->param('id');
        $id=explode(",",$id);
        if($id){
            if(db('workpermit')->delete($id)){
                return ['success'=>true,'msg'=>'删除成功'];
            }
            return ['fail'=>true,'msg'=>'删除失败'];
        }
    }

}
