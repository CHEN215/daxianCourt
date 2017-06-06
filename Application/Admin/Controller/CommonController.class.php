<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller {
    protected $result;
    
    public function __construct(){
        parent::__construct();
        $this->result= new Result();
    }
    
    public function _initialize(){
		if(!session('?ADMIN_AUTH_KEY')){
			$this->redirect('Login/index');
		}
		$this->checkadmin();
	}
	
	public function checkadmin(){
	    if(session('ADMIN_AUTH_NAME')!=C('AUTH_SUPERADMIN')){
	        $auth = array(
	            'Index'=>array('index','main','navlist'),
	            'Article'=>array('add','edit','delete','catelist','editimage','mylist','uploadthumb'),
	        );
            if(!in_array(strtolower(ACTION_NAME), $auth[CONTROLLER_NAME])){
                echo '没有权限，请与管理员联系！';
                exit;
            }
	    }
	}
}

Class Result {
    public $msg;
    public $success;
    function __construct(){
        $this->msg = '';
        $this->success = false;
    }
}