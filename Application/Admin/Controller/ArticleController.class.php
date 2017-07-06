<?php
namespace Admin\Controller;
use Think\Controller;
class ArticleController extends CommonController {
    
    public function add(){
        if(!IS_AJAX){
            $where = array();
            $where2 = array();
            // if(session('ADMIN_AUTH_NAME')!=C('AUTH_SUPERADMIN')){
            $user =session('ADMIN_AUTH_USERINFO'); 
           if($user['role']=='频道管理员'){
               // $user = session('ADMIN_AUTH_USERINFO');
                $topcate = M('Cate')->where(array('id'=>array('in', $user['power'])))->getField('pid',true);
                $topcate && $topcate = implode(',',  $topcate);
                $where[] = array('id'=>array('in',$topcate.','.$user['power']));
            }
            
            $cates = M('Cate')->where($where)->select();
            $cates = sty_cates($cates);
            //var_dump($this->cates);exit;
            //总页数
            $cid==0 && $cid = $cates[1]['id'];

            $this->cates = $cates;
            //领导
            $this->leaders = M('User')->field(array('id','username'=>'name'))->where(array('role'=>'领导'))->select();
            //一级栏目
            
            $this->display();            
        }else{
            //"isShowOutNet":"on","content":"asdfasdfasdf","file":""}
            $data = array();
            $data['title'] = I('post.title','','trim');
            $data['c_id'] = I('post.catetory',0,'intval');
            $data['author'] = I('post.author','','trim');
            $data['origin'] = I('post.origin','','trim');
            if(I('post.isShowOutNet','','trim')) $data['outside'] = 1;
            $data['imgsrc'] = I('post.thumb_src','','trim');
            $data['comment'] = I('post.content','','trim');
            $data['leader_id'] = I('post.leader',0,'intval');
            $data['hot'] = I('post.hot',0,'intval');
            $data['top'] = I('post.top',0,'intval');
            $data['time'] = time();
            if($data['c_id']==0||$data['author']==''||$data['comment']==''||$data['title']==''){
                $this->result->msg = "请填写完整！";
                $this->ajaxReturn($this->result);
                
            }
            if($data['leader_id']==0){
                $this->result->msg = "请选择审核这篇文章的领导！";
                $this->ajaxReturn($this->result);
            }
            if(M('News')->add($data)){
                session('thumb_src',null);
                $this->result->success=true;
            }else{
                $this->result->msg = '添加失败！';
            }
            $this->ajaxReturn($this->result);
        }
    }
    
    /**
     * 编辑文章
     */
    public function edit(){
        if(!IS_AJAX){
            $id = I('get.id',0,'intval');
            $id || $this->redirect('Article/add');
            $article = M('News as n')->field('n.*,c.pid')
                                           ->join('__CATE__ as c on n.c_id = c.id')
                                           ->where(array('n.id'=>$id))
                                           ->find();
            $this->article = $article;
            //var_export($this->article);exit;
            //一级栏目
           $where = array();
           // if(session('ADMIN_AUTH_NAME')!=C('AUTH_SUPERADMIN')){
           $user =session('ADMIN_AUTH_USERINFO'); 
            if($user['role']=='频道管理员'){
               // $user = session('ADMIN_AUTH_USERINFO');
                $topcate = M('Cate')->where(array('id'=>array('in', $user['power'])))->getField('pid',true);
                $topcate && $topcate = implode(',',  $topcate);
                $where[] = array('id'=>array('in',$topcate.','.$user['power']));
            }
            
            $cates = M('Cate')->where($where)->select();
            $cates = sty_cates($cates);
            //var_dump($this->cates);exit;
            //总页数
            $this->cid = $article['c_id'];

            $this->cates = $cates;
         
            //var_dump($user);exit;
            if(session('ADMIN_AUTH_NAME')==C('AUTH_SUPERADMIN')|| $user['id']==$article['leader_id']){
                $this->show = 'show';
            }else{
                $this->show = '';
            }
            $this->display();
        }else {
            $data = array();
            $data['id'] = I('post.article_id');
            $data['title'] = I('post.title','','trim');
            $data['c_id'] = I('post.catetory',0,'intval');
            $data['author'] = I('post.author','','trim');
            $data['origin'] = I('post.origin','','trim');
            $data['hot'] = I('post.hot',0,'intval');
            $data['top'] = I('post.top',0,'intval');
            
            if(I('post.showOutNet','','trim')=='on'){
                $data['outside'] = 1;
            }else{
                $data['outside'] = 0;
            }
            $data['imgsrc'] = I('post.thumb_src','','trim');
            $data['comment'] = I('post.content','','trim');
            $data['time'] = time();
            if($data['c_id']==0||$data['author']==''||$data['comment']==''||$data['title']==''){
                $this->result->msg = "请填写完整！";
            }
            if(M('News')->save($data)){
                
                $this->result->success=true;
            }else{
                $this->result->msg = '修改失败！';
            }
            $this->ajaxReturn($this->result);
        }
        
    }
    public function delete(){
        $id = I('get.id',0,'intval');
        $_rel = M('News')->delete($id);
        if($_rel > 0){
            $this->result->success = true;
        }
        $this->ajaxReturn($this->result);
    }
    /**
     * 文章列表
     */
    public function myList(){
        $this->size = 8;
        $this->total = 0;
        if(!IS_AJAX){
            $cid = I('get.cid',0,'intval');
            
            //一级栏目
            $where = array();
            $user =session('ADMIN_AUTH_USERINFO');
           // if(session('ADMIN_AUTH_NAME')!=C('AUTH_SUPERADMIN')){
            if($user['role']=='频道管理员'){
               // $user = session('ADMIN_AUTH_USERINFO');
                $topcate = M('Cate')->where(array('id'=>array('in', $user['power'])))->getField('pid',true);
                $topcate && $topcate = implode(',',  $topcate);
                $where[] = array('id'=>array('in',$topcate.','.$user['power']));
            }
            
            $cates = M('Cate')->where($where)->select();
            $cates = sty_cates($cates);
            //var_dump($this->cates);exit;
            //总页数
            $cid==0 && $cid = $cates[1]['id'];

            $this->cates = $cates;
            $_where = array('c_id'=>$cid);
            $this->total = ceil(M('News')->where($_where)->count()/$this->size);
            
            $this->articles = M('News as n')->field('n.id,n.title,n.time,n.outside,n.author,c.name')
                                            ->join('__CATE__ as c on c.id = n.c_id','left')
                                            ->where($_where)
                                            ->limit(0,$this->size)
                                            ->order('time desc')
                                            ->select();
            //var_dump(D()->getLastSql());exit;
            $this->cid = $cid;
            $this->display('list');
        }else{
            $cid = I('get.cid',0,'intval');
            $page = I('get.page',1,'intval');
            $size = ($page-1)*$this->size;
            $articles = M('News as n')->field('n.id,n.title,n.time,n.outside,n.author,c.name')
                                            ->join('__CATE__ as c on c.id = n.c_id','left')
                                            ->where(array('c_id'=>$cid))
                                            ->limit($size,$this->size)
                                            ->order('time desc')
                                            ->select();
            //echo D()->getLastSql();exit;
            $key = 1;
            foreach ($articles as $article){
                $content.=' <tr><td>'.$key++.'</td>
                                <td>'.$article['name'].'</td>
                                <td>'.$article['title'].'</td>
                                <td>'.$article['author'].'</td>
                                <td>'.date('Y-m-d H:i',$article['time']).'</td>
                                <td style="text-align:center;">';
                   if($article['outside'] == '1'){
                       $content.= '<i class="layui-icon" style="color:green;"></i>';
                   }else{
                       $content.= '<i class="layui-icon" style="color:red;"></i>';
                   }
                                
                   $content.=' </td>
                                    <td>
                                    <a href="javascript:;"  data-id="'.$article['id'].'" class="layui-btn layui-btn-mini edit_btn">编辑</a>
                                    </td>
                                    <td>
                                    <a href="javascript:;" data-id="'.$article['id'].'"  class="layui-btn layui-btn-danger layui-btn-mini del_btn">删除</a>
                                    </td>
                                    </tr>';
               
            }
            $this->result->msg = $content;
            $this->result->success = true;
            $this->ajaxReturn($this->result);
        }
    }
    /**
     * 返回次级栏目
     */
    public function catelist(){
        $where = array();
        $id = I('get.id',0,'intval');
        $where[] = array('pid'=>$id);
       // if(session('ADMIN_AUTH_NAME')!=C('AUTH_SUPERADMIN')){
        $user =session('ADMIN_AUTH_USERINFO'); 
        if($user['role']=='频道管理员'){
             $user = session('ADMIN_AUTH_USERINFO');
             $where[] = array('id'=>array('in', $user['power']));
         }
        if($id==0) $this->ajaxReturn($this->result);
        $cates = M('Cate')->where($where)->select();
        $content = '<option value="" selected>请选择</option>';
        foreach ($cates as $cate){
            $content .= '<option value="'.$cate['id'].'">'.$cate['name'].'</option>';
        }
        if($content==''){
            //$cate = M('Cate')->find($id);
            $content .= '<option value="'.$cate['id'].'">'.无子栏目.'</option>';
        }
        $this->result->msg = $content;
        $this->result->success = true;
        $this->ajaxReturn($this->result);
    }
    
    /**
     * 上传图片
     */
    public function uploadThumb(){
        $upload = new \Think\Upload();// 实例化上传类   
        $upload->maxSize   =     3145728 ;// 设置附件上传大小   
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型    
        $upload->savePath  =      './'; // 设置附件上传目录    
        // 上传单个文件     
        $info   =   $upload->uploadOne($_FILES['newsPic']);    
        if(!$info) {// 上传错误提示错误信息        
            $this->result->msg="上传失败！";
        }else{// 上传成功 获取上传文件信息         
            $src = substr($info['savepath'].$info['savename'], 1) ; 
            $this->result->success = true;
            $this->result->url = $src;
        }
        $this->ajaxReturn($this->result);
       
    }
    
    /**
     * 编辑器图片上传
     */
    public function editimage(){
            $std = array();
            $std['code'] = 1;
            $std['msg'] = '';
            $std['data']['src'] ='';
            $std['data']['title'] = '';
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->savePath  =      './'; // 设置附件上传目录
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['file']);
            if(!$info) {// 上传错误提示错误信息
               $std['msg']="上传失败！";
            }else{// 上传成功 获取上传文件信息
                $src = substr($info['savepath'].$info['savename'], 1) ;
                $std['code'] = 0;
                $std['data']['src'] = __ROOT__.'/Uploads/'.$src;
                $std['data']['title'] = $info['savename'];
            }
            
            $this->ajaxReturn($std);
        
    }
}