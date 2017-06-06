<?php
namespace Admin\Controller;
use Think\Controller;
class FriendlinkController extends CommonController {
    public function add(){
        
        if(!IS_AJAX){
            $this->display();
        }else{
             $data['address'] = I('post.site_address','','trim');
             $data['name'] = I('post.site_name','','trim');
             $data['type'] = I('post.type',1,'intval');
             if($data['address']==''||$data['name']==''){
                 $this->result->msg = '请填写完整！';
                 $this->ajaxReturn($this->result);
             }
             if(M('Link')->add($data)){
                 $this->result->success = true;
             }
            $this->ajaxReturn($this->result);
        }
    }
    
    /**
     * 添加链接
     */
    public function edit(){
        if(!IS_AJAX){
            $id = I('get.id',0,'intval');
            $this->link = M('link')->find($id);
            $this->display();            
        }else{
            $data = array();
            $data['address'] = I('post.site_address','','trim');
            $data['name'] = I('post.site_name','','trim');
            $data['id'] = I('post.link_id',0,'intval');
            $data['type'] = I('post.type',1,'intval');
            
            if($data['address']==''||$data['name']==''||$data['id']==0){
                $this->result->msg = '请填写完整！';
                $this->ajaxReturn($this->result);
            }
            if(M('Link')->save($data)!==false){
                $this->result->success = true;
            }else{
                $this->result->msg = '修改失败！'; 
            }
            $this->ajaxReturn($this->result);
            
        }
    }
    public function myList(){
        $this->links = M('Link')->field(array('id','name','address','type'))->select();
        $this->display('list');
    }
    /**
     * 删除友情链接
     */
    public function delete(){
        $id = I('get.id',0,'intval');
        $_rel = M('Link')->delete($id);
        if($_rel > 0){
            $this->result->success = true;
        }
        $this->ajaxReturn($this->result);
    }
}