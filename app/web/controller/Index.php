<?php
namespace app\web\controller;
use app\admin\model\Area;
use app\admin\model\Article;
use app\admin\model\Category;
use app\admin\model\Column;
use app\admin\model\Consult;
use app\admin\model\Site;
use app\admin\model\Slider;
use app\admin\model\Workpermit;
use \think\Controller;

class Index extends Controller
{

    //首页-轮播图
    public function get_slides()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'];
        $model = new Slider();
        $res = $model->getSlider();
        $imgArr = [];
        foreach($res as $val){
            $imgArr[] = $host.$val['img'];
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
        $data['alias_name'] = request()->post('alias_name');
        $data['is_zh'] = request()->post('is_zh');
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
        $data = array_merge(request()->get(),request()->post());
        $is_zh = empty($data['is_zh'])?1:$data['is_zh'];//1中文 2英文
        $model = new Category();
        //是否英文 0中文 1英文
        $res = $model->getTree([['type'=>1,'is_zh'=>$is_zh]]);
        return $this->result($res,1,'请求成功','json');
    }
    //新闻列表
    public function newsList(){
        $data = array_merge(request()->get(),request()->post());
        $is_zh = empty($data['is_zh'])?1:$data['is_zh'];//1中文 2英文
        $is_index = empty($data['is_index'])?0:1;//是否首页推荐
        if(empty($data['cate_id'])){
            return $this->result([],1,'请求成功,分类id不能为空','json');
        }
        $where = [];
        if(!empty($is_index)){
            $where['top'] = 1;
            $where['type'] = 1;//1文章分类 2服务分类
            $where['is_en'] = $is_zh;//是否英文 1中文 2英文
            $where['pid'] = $data['cate_id'];
        }else{
            $where['type'] = 1;//1文章分类 2服务分类
            $where['is_en'] = $is_zh;//是否英文 1中文 2英文
            $where['pid'] = $data['cate_id'];
        }
        $model = new Article();
        //是否英文 0中文 1英文
        $res = $model->getNewsList($where);
        $host = 'http://'.$_SERVER['HTTP_HOST'];
        $res = $res->toArray();
        foreach($res['data'] as &$value){
            $value['thumb'] = $host.$value['thumb'];
        }
        return $this->result($res,1,'请求成功','json');
    }

    //服务分类列表
    public function serviceCate(){
        $data = array_merge(request()->get(),request()->post());
        $is_zh = empty($data['is_zh'])?1:$data['is_zh'];//1中文 2英文
        $model = new Category();
        //是否英文 0中文 1英文
        $res = $model->getTree([['type'=>2,'is_zh'=>$is_zh]]);
        return $this->result($res,1,'请求成功','json');
    }
    //服务列表
    public function serviceList(){
        $data = array_merge(request()->get(),request()->post());
        $is_zh = empty($data['is_zh'])?1:$data['is_zh'];//1中文 2英文
        $is_index = empty($data['is_index'])?0:1;//是否首页推荐
        if(empty($data['cate_id'])){
            return $this->result([],1,'请求成功,分类id不能为空','json');
        }
        $where = [];
        if(!empty($is_index)){
            $where['top'] = 1;
            $where['type'] = 2;//1文章分类 2服务分类
            $where['is_en'] = $is_zh;//是否英文 1中文 2英文
            $where['pid'] = $data['cate_id'];
        }else{
            $where['type'] = 2;//1文章分类 2服务分类
            $where['is_en'] = $is_zh;//是否英文 1中文 2英文
            $where['pid'] = $data['cate_id'];
        }
        $model = new Article();
        //是否英文 0中文 1英文
        $res = $model->getNewsList($where);
        $host = 'http://'.$_SERVER['HTTP_HOST'];
        $res = $res->toArray();
        foreach($res['data'] as &$value){
            $value['thumb'] = $host.$value['thumb'];
        }
        return $this->result($res,1,'请求成功','json');
    }

    public function services(){
        $data = array_merge(request()->get(),request()->post());
        $is_zh = empty($data['is_zh'])?1:$data['is_zh'];//1中文 2英文
        $is_index = empty($data['is_index'])?0:1;//是否首页推荐
        $where = [];
        if(!empty($is_index)){
            $where['top'] = 1;
            $where['is_en'] = $is_zh;//是否英文 1中文 2英文
        }else{
            $where['is_en'] = $is_zh;//是否英文 1中文 2英文
        }
        $model = new Article();
        //是否英文 0中文 1英文
        $res = $model->getServiceList($where);
        $host = 'http://'.$_SERVER['HTTP_HOST'];
        foreach($res as &$value){
            $value['thumb'] = $host.$value['thumb'];
        }
        return $this->result($res,1,'请求成功','json');
    }
    //首页worker-permit
    public function workerPermit(){
        $model = new Workpermit();
        $res = $model->getCol();
        return $this->result($res,1,'请求成功','json');
    }
    //首页地区选择
    public function areaSelect(){
        $model = new Area();
        $res = $model->getCol();
        return $this->result($res,1,'请求成功','json');
    }


    //首页获取用户需求
    public function fetchRequest(){
        $data = array_merge(request()->get(),request()->post());
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
