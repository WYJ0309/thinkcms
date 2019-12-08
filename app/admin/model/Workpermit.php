<?php
namespace app\admin\model;
use think\Model;

class Workpermit extends Model{

    public function getCol($id=null){
        if(intval($id)){
            return $this->find($id);
        }
        return $this->order('id desc')->select();
    }
    //根据单页面别名获取
    public function getLine($where){
        return $this->where($where)->find();
    }

}