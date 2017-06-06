<?php
namespace Admin\Controller;
use Think\Controller;
class CatetoryController extends CommonController {
    public function add(){
        if(!IS_AJAX){
            $_cates = M('cate')->where(array('pid'=>0))->select();
            $this->cates = array_merge(array(array('id'=>0,'name'=>'根节点')),$_cates);
            $this->display();
        }else{
            $data['name'] = I('post.catetory','','trim');
            $data['pid'] = I('post.parent_catetory',0,'intval');
            if(M('Cate')->add($data)){
              $this->result->success = true;  
            }
            $this->ajaxReturn($this->result);
        }
    }
    
    public function edit(){
        if(!IS_AJAX){
            $id = I('get.id',0,'intval');
            $this->nowCate = M('cate')->find($id);
            $_cates = M('cate')->where(array('pid'=>0))->select();
            $this->cates = array_merge(array(array('id'=>0,'name'=>'根节点')),$_cates);
            //var_dump($this->cates);exit;
            $this->display();
        }else{
            $data = array();
            $data['name'] = I('post.catetory','','trim');
            $data['pid'] = I('post.parent_catetory',0,'intval');
            $data['id'] = I('post.catetory_id',0,'intval');
//             if(M('Cate')->where(array('name'=>array('like','%'.$data['name'].'%')))->find()){
//                 $this->result->msg = '该栏目已经存在！';
//                 $this->ajaxReturn($this->result);
//             }
            if ($data['name']==''){
                $this->result->msg = '栏目名称不能为空！';
                $this->ajaxReturn($this->result);
            }
            if(M('Cate')->save($data)===false){
                $this->result->msg = '修改失败！';
            }else{
                $this->result->success = true;
            }
            $this->ajaxReturn($this->result);
        }
    }
    /**
     * 栏目列表
     */
    public function myList(){
        $_cates = M('Cate as c')->field('c.*,count(n.c_id) as nums')
                                ->join('__NEWS__ n on c.id = n.c_id','left')
                                ->group('c.id')
                                ->select();
        $this->cates = sort_cates($_cates);
        //var_dump($this->cates);exit;
        $this->display('list'); 
    }
    
    public function delete(){
        $id = I('get.id',0,'intval');
        $_rel = M('Cate')->delete($id);
        if($_rel > 0){
            $this->result->success = true;
        }
        $this->ajaxReturn($this->result);
    }
}