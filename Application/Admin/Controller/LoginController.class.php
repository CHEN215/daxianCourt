<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
use Think\Auth;
//use Think\Verify;
/**
 * 后台登陆控制器
 * @author Administrator
 *
 */
class LoginController extends Controller{
    protected $result;
    function __construct(){
        parent::__construct();
        $this->result = new \stdClass();
        $this->result->msg = '';
        $this->result->success = false;
    }
	/**
	 * 后台登陆首页
	 */
	public function index(){
        $data = array();
        $data['username'] = I('post.userName','','trim');
        $password = I('post.password','','trim');
	    if(IS_POST){
	        $data['password'] = md5($password);
            $user = M('User')->where($data)->find();
	        if(!$user){
	            $this->errMsg = '用户名或密码错误！';
	        }else{
    	        //保存会话
	            session('ADMIN_AUTH_KEY',$user['id']);
	            session('ADMIN_AUTH_NAME',$user['username']);
	            session('ADMIN_AUTH_USERINFO',$user);
	            //保存用户登录日志
	            M('Log')->add(array('user_id'=>$user['id'],'time'=>time(),'ip'=>get_client_ip()));
	            //记录用户最后登录的ip,时间
	            M('User')->where(array('id'=>$user['id']))
	                     ->save(array('logintime'=>time(),'loginip'=>get_client_ip()));
	        
	            $this->redirect('Index/index');
	        }
	    }
	    $this->username = $data['username'];
	    $this->password = $password;
		$this->display('login');
	}

	

	//退出登录
	public function logout(){
		session(null);
		session('[destroy]');
		$this->redirect('Login/index');
	}
	
	/**
	 * 修改密码
	 */
	public function changePassword() {
	    if(!IS_POST){
	        $this->display('changepwd');
	    }else{
	        $password = I('post.password','1','trim');
    	    $password_confirm = I('post.password_confirm','2','trim');
    	    if($password!=$password_confirm){
    	        $this->result->msg='两次密码不一样';
    	    }else {
    	        M('User')->where(array('id'=>session('ADMIN_AUTH_KEY')))->save(array('password'=>md5($password)))!==false?
    	        $this->result->success = true:
    	        $this->result->msg = '修改失败';
    	    }
    	    $this->ajaxReturn($this->result);
	    }
	    
	}

	/**
	 * 清除缓存
	 */
	public function cleanCache(){
	    //清文件缓存
	    $dirs	=	array(APP_PATH.'/Runtime/');
	    //清理缓存
	    foreach($dirs as $value) {
	        if($this->rmdirr($value)){
	         		 $this->result->msg.='文件夹'.$value.'删除成功;</br>';
	         		 @mkdir($value,0777,true);
	         		 $this->result->success=true;
	        }
	         
	    }
	    $this->ajaxReturn($this->result);
	
	}
	
	public function rmdirr($dirname) {
	    if (!file_exists($dirname)) {
	        return false;
	    }
	    if (is_file($dirname) || is_link($dirname)) {
	        return unlink($dirname);
	    }
	    $dir = dir($dirname);
	    if($dir){
	        while (false !== $entry = $dir->read()) {
	            if ($entry == '.' || $entry == '..') {
	                continue;
	            }
	            $this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
	        }
	    }
	    $dir->close();
	    return rmdir($dirname);
	}
}