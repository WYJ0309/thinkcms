<?php
namespace app\web\controller;
use app\admin\model\Article;
use app\admin\model\Category;
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

    //列表
    public function listing($id)
    {
        if(intval($id)){
            //分页
            $this->assign('list',getPA($id));
            //分类名
            $this->assign('cate',getCName($id));
            return $this->fetch('list');
        }
        abort(404,'页面不存在');
    }

    //内容页
    public function view($id){
        if(intval($id)){
            $this->assign('article',getA($id));
            return $this->fetch();
        }
        abort(404,'页面不存在');
    }

    //栏目页
    public function column($name){

        $column=getColumn($name);
        if($column){
            $this->assign('column',$column);
            return $this->fetch();
        }
        abort(404,'页面不存在');
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
            $where['is_en'] = 1;//是否英文 1中文 2英文
        }
        $model = new Article();
        //是否英文 0中文 1英文
        $res = $model->getNewsList($where);
        return $this->result($res,1,'请求成功','json');
    }
}
