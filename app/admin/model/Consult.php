<?php
namespace app\admin\model;
use think\Model;

class Consult extends Model{

    //获取所有轮播图片
    public function getPage(){
        return $this->order('id desc')->paginate(50);
    }
    //获取单个文章
    public function getOne($id){
        $res=$this->where('id',$id)->find();
        return $res;
    }

}