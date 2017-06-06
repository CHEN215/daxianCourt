<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController {
    public function add(){
        if(!IS_AJAX){
            $this->powers = M('cate')->field(array('id','name'))->where('pid != 0')->select();
            $this->display();
        }else{
            //var_dump($_POST);exit;
            $data = array();
            $data['username'] = I('post.username','','trim');
            $data['part'] = I('post.department','','trim');
            $data['password'] = I('post.password','','trim');
            $data['password_confirm'] = I('post.password_confirm','','trim');
            $data['power'] = I('post.power');
            $data['power'] = implode(',', $data['power']);
            is_null($data['power']) && $data['power'] = '';
            $data['role'] = I('post.role','','trim');
//             var_export($_POST);
//             var_export($data);exit;
            if($data['username']==''||$data['part']==''){
                $this->result->msg = '用户名和部门不能为空！';
                $this->ajaxReturn($this->result);
            }
            if($data['password']!=$data['password_confirm']){
                $this->result->msg = '两次密码不一致';
                $this->ajaxReturn($this->result);
            }
            if($data['role']==''){
                $this->result->msg = '请选择用户的角色';
                $this->ajaxReturn($this->result);
            }
            if(M('User')->where(array('username'=>$data['username']))->find()){
                $this->result->msg = '该用户已经存在';
                $this->ajaxReturn($this->result);
            }
            $data['password'] = md5($data['password']);
            if(M('user')->add($data)){
                $this->result->success = true;
            }else{
                $this->result->msg = '添加失败';
            }
            $this->ajaxReturn($this->result);
            
        }
    }
    
    public function edit(){
        if(!IS_AJAX){
            $id = I('get.id',0,'intval');
            $this->user = M('user')->find($id);
            $this->rules = explode(',', $this->user['power']);
            $this->powers = M('cate')->field(array('id','name'))->where('pid != 0')->select();
            $this->display();
        }else{
            $data = array();
            $data['username'] = I('post.username','','trim');
            $data['part'] = I('post.department','','trim');
            $data['power'] = I('post.power');
            $data['power'] = implode(',', $data['power']);
            //$data['role'] = session('ADMIN_AUTH_NAME')!=C('AUTH_SUPERADMIN')?'频道管理员':'超级管理员';
            $data['id'] = I('post.id',0,'intval');
            $user = M('user')->find($data['id']);
            //var_dump($user);
            $user['username'] != C('AUTH_SUPERADMIN') ? $data['role'] = $user['role'] : $data['role'] = '超级管理员';
            //echo $user['username']. C('AUTH_SUPERADMIN');exit;
            if($data['username']==''||$data['part']==''){
                $this->result->msg = '用户名和部门不能为空！';
                $this->ajaxReturn($this->result);
            }
            if(M('User')->where(array('id'=>array('neq',$data['id']),'username'=>$data['username']))->find()){
                $this->result->msg = '该用户已经存在';
                $this->ajaxReturn($this->result);
            }
            if(false !== M('user')->save($data)){
                $this->result->success = true;
            }else{
                echo D()->getLastSql();exit;
                $this->result->msg = '修改失败';
            }
            $this->ajaxReturn($this->result);
            
        }
    }
    public function myList(){
        if(!IS_AJAX){
            $this->users = M('User')->select();
            $this->display('list');
        }else{
        }
    }
    public function delete(){
        $id = I('get.id',0,'intval');
        $_rel = M('User')->delete($id);
        if($_rel > 0){
            $this->result->success = true;
        }
        $this->ajaxReturn($this->result);
    }
}