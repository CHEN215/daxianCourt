<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{

    public function index()
    {
        $this->articles = M('News')->field(array('id','title','imgsrc'))->where(array('imgsrc'=>array('like','%\.%')))->limit(4)->order('time desc')->select();
        //省级法院
        $this->sjLinks = M('Link')->field(array('name','address'))->where(array('type'=>1))->select();
        //基层法院
        $this->jcLinks = M('Link')->field(array('name','address'))->where(array('type'=>2))->select();
        //法院新闻
        $this->newsName = M('Cate')->field(array('name'))->where(array('id'=>68))->find();
        $news = M('News')->field(array('id','title','time','comment'))->where(array('c_id'=>68))->limit(5)->order('time desc')->select();
        $this->onenews = array_shift($news); 
        $this->news = $news;
        $this->display();
    }
   
    public function alist()
    {
        $id = I('get.id',0,'intval');
        if($id==0){
            $this->error('栏目不存在！');
        }
        
        
        $this->checked =  $checked = M('Cate')->field(array('pid','id','name'))->find($id); 
        if ($checked['pid'] == 0)
        {
            $this->topcate = $this->checked;
            $this->subcate = M('Cate')->field(array('pid','id','name'))->where(array('pid'=>$checked['id']))->select();
        }else{
            $this->topcate = $topcate =  M('Cate')->field(array('pid','id','name'))->find($checked['pid']);
            $this->subcate = M('Cate')->field(array('pid','id','name'))->where(array('pid'=>$topcate['id']))->select();
        }
       
        //文章列表
        $NewsModel = M('News'); // 实例化User对象
        $count     = $NewsModel->where(array('c_id'=>$checked['id']))->count();// 查询满足要求的总记录数
        $Page   = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show   = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        
        $this->articles = M('News')->field(array('id','title','time'))->where(array('c_id'=>$checked['id']))->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);// 赋值分页输出
        
        $this->display('list');
    }

    public function article()
    {
        $id = I('get.id',0,'intval');
        if($id==0){
            $this->error('文章不存在！');
        }
        
        $article = M('News')->find($id);
        $this->checked = $checked = M('Cate')->field(array('pid','id','name'))->find($article['c_id']);
       // $this->cates = M('Cate')->field(array('pid','id','name'))->where(array('pid'=>0))->select();
      //  $this->subcates = M('Cate')->field(array('pid','id','name'))->where(array('pid'=>$checked['pid']))->select();
        if ($checked['pid'] == 0)
        {
            $this->topcate = $this->checked;
            $this->subcate = M('Cate')->field(array('pid','id','name'))->where(array('pid'=>$checked['id']))->select();
        }else{
            $this->topcate = $topcate =  M('Cate')->field(array('pid','id','name'))->find($checked['pid']);
            $this->subcate = M('Cate')->field(array('pid','id','name'))->where(array('pid'=>$topcate['id']))->select();
        }
        $this->article = $article;
        $this->display();
    }
    
    
    public function getSubCates(){
        $result  = new \stdClass();
        $result->success=false;
        $id = I('get.id',0,'intval');
        if($id==0){
            $result->msg = "id 不存在！";
            $this->ajaxReturn($result);
        }
        $subcates = M('Cate')->field(array('pid','id','name'))->where(array('pid'=>$id))->select();
        foreach ($subcates as $cate){
            $str .= '<a href="'.U('Index/alist',array('id'=>$cate['id'])).'">'.$cate['name'].'</a>';
        }
        if($str == '') $str = '<a href="#">该栏目还没有子栏目！</a>';
        $result->msg = $str;
        $result->success=true;
        $this->ajaxReturn($result);
    }
    
    public function dosearch(){
        $keyword = I('get.keywork','','trim');
        
        $this->cates = M('Cate')->field(array('pid','id','name'))->where(array('pid'=>0))->select(); 
       // $this->checked = $checked;
        
        //文章列表
        $NewsModel = M('News'); // 实例化User对象
        $count  = $NewsModel->where(array('title'=>array('like','%'.$keyword.'%')))->count();// 查询满足要求的总记录数
        $Page   = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        
        $this->articles = M('News')->field(array('id','title','time'))->where(array('title'=>array('like','%'.$keyword.'%')))->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);// 赋值分页输出
        
        $this->display('alist');
    }
}