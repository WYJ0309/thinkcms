<?php
namespace app\admin\model;
use think\Model;

class Site extends Model{

  public function getSite($where=null){
      if(empty($where)){
          return $this->where('site_id',1)->find();
      }else{
          return $this->where('site_id',2)->find();
      }
  }

  //保存site数据
  public function savaData($data){
      return $this->update($data);
  }

  public function getList(){
      return $this->select();
  }

}