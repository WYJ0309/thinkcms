<?php
namespace app\admin\controller\api;
use think\Controller;
use think\Cookie;
use think\Request;

class AreaApi extends Controller
{
    public function access(){
        if(!isLogin()){
            $this->redirect(ADMIN_ROUTE.'login');
        }
    }

    //添加或编辑栏目
    public function edit(Request $request){
        $this->access();

        $area_id=$request->param('id');
        $data=$request->param();
        unset($data['id']);

        //判断是否有id
        if($area_id){
            //更新
            db('area')->where('id',$area_id)->update($data);
            Cookie::set('add_area','1');
            $this->redirect(ADMIN_ROUTE.'area/edit');
        }else{
            //添加
            $res=db('area')->insert($data);
            if($res){
                Cookie::set('add_area','1');
            }else{
                Cookie::set('add_area','0');
            }
            $this->redirect(ADMIN_ROUTE.'arealist');
        }
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
