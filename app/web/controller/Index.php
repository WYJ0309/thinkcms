<?php
namespace app\web\controller;
use app\admin\model\Article;
use app\admin\model\Category;
use app\admin\model\Column;
use app\admin\model\Consult;
use app\admin\model\Site;
use app\admin\model\Slider;
use \think\Controller;

class Index extends Controller
{

    //首页-轮播图
    public function get_slides()
    {
        $model = new Slider();
        $res = $model->getSlider();
        $imgArr = [];
        foreach($res as $val){
            $imgArr[] = $val['img'];
        }
        return $this->result($imgArr,1,'请求成功','json');
    }

    //站点各项配置
    public function site(){
        $data = request()->post();
        $is_zh = empty($data['is_zh'])?0:$data['is_zh'];//1中文 2英文
        $model = new Site();
        $res = $model->getSite($is_zh);
        return $this->result($res,1,'请求成功','json');
    }

    //内容页
    public function view(){
        $data = request()->post();
        if(empty($data['id'])){
            return $this->error('id不能为空');
        }
        $model = new Article();
        $res = $model->getOneArticle($data['id']);
        return $this->result($res,1,'请求成功','json');
    }

    //栏目页
    public function column(){
        $data = request()->post();
        if(empty($data['alias_name'])){
            return $this->error('参数不能为空');
        }
        $is_en = empty($data['is_zh'])?1:$data['is_zh'];
        //是否英文 1中文 2英文
        $model = new Column();
        $where = ['alias_name'=>$data['alias_name'],'is_en'=>$is_en];
        $res = $model->getLine($where);
        return $this->result($res,1,'请求成功','json');
    }

    //新闻分类列表
    public function newsCate(){
        $data = request()->post();
        $is_zh = empty($data['is_zh'])?1:$data['is_zh'];//1中文 2英文
        $is_zh = ($is_zh == 1)?0:1;
        $model = new Category();
        //是否英文 0中文 1英文
        $res = $model->getTree(['type'=>1,'is_zh'=>$is_zh]);
        return $this->result($res,1,'请求成功','json');
    }
    //新闻列表
    public function newsList(){
        $data = request()->post();
        $is_zh = empty($data['is_zh'])?1:$data['is_zh'];//1中文 2英文
        $is_zh = ($is_zh == 1)?0:1;
        $is_index = empty($data['is_index'])?0:1;//是否首页推荐
        $where = [];
        if(!empty($is_index)){
            $where['top'] = 1;
            $where['type'] = 1;//1文章分类 2服务分类
            $where['is_en'] = empty($is_zh)?1:2;//是否英文 1中文 2英文
        }else{
            $where['type'] = 1;//1文章分类 2服务分类
            $where['is_en'] = empty($is_zh)?1:2;//是否英文 1中文 2英文
        }
        if(!empty($data['cate_id'])){
            $where['pid'] = $data['cate_id'];
        }
        $model = new Article();
        //是否英文 0中文 1英文
        $res = $model->getNewsList($where);
        return $this->result($res,1,'请求成功','json');
    }

    //服务分类列表
    public function serviceCate(){
        $data = request()->post();
        $is_zh = empty($data['is_zh'])?1:$data['is_zh'];//1中文 2英文
        $is_zh = ($is_zh == 1)?0:1;
        $model = new Category();
        //是否英文 0中文 1英文
        $res = $model->getTree(['type'=>2,'is_zh'=>$is_zh]);
        return $this->result($res,1,'请求成功','json');
    }
    //服务列表
    public function serviceList(){
        $data = request()->post();
        $is_zh = empty($data['is_zh'])?1:$data['is_zh'];//1中文 2英文
        $is_zh = ($is_zh == 1)?0:1;
        $is_index = empty($data['is_index'])?0:1;//是否首页推荐
        $where = [];
        if(!empty($is_index)){
            $where['top'] = 1;
            $where['type'] = 2;//1文章分类 2服务分类
            $where['is_en'] = empty($is_zh)?1:2;//是否英文 1中文 2英文
        }else{
            $where['type'] = 2;//1文章分类 2服务分类
            $where['is_en'] = 1;//是否英文 1中文 2英文
        }
        $model = new Article();
        //是否英文 0中文 1英文
        $res = $model->getNewsList($where);
        return $this->result($res,1,'请求成功','json');
    }


    //首页获取用户需求
    public function fetchRequest(){
        $data = request()->post();
        $insertArr = [];
        $insertArr['username'] = $data['username'];
        $insertArr['email'] = $data['email'];
        $insertArr['phone'] = $data['phone'];
        $insertArr['work_permit'] = $data['work_permit'];
        $insertArr['address'] = $data['address'];
        $insertArr['content'] = $data['content'];
        $model = new Consult();
        $model->insert($insertArr);
        return $this->result('需求已收到',1,'请求成功','json');
    }


}
