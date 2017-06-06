<?php
namespace Home\Widget;

use Think\Controller;

class CommonWidget extends Controller
{

    public function header()
    {
        $this->cates = M('Cate')->field(array('id','name'))->where(array('pid'=>0,id=>array('not in','66,67,68')))->select();     
        $this->display('Common:header');
    }
}
