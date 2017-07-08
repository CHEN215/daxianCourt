<?php
namespace Home\Widget;

use Think\Controller;

class CommonWidget extends Controller
{

    public function header()
    {
        $cates = M('Cate')->field(array('id','name','pid'))->where(array('id'=>array('not in','66,67,68')))->select();
        $this->cates = arr2tree($cates);
       // var_dump(arr2tree($this->cates));
        $this->display('Common:header');
    }
}
