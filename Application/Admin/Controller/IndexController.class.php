<?php
namespace Admin\Controller;

use Think\Controller;

class IndexController extends CommonController
{

    public function index()
    {
        //var_dump($_SERVER);exit;
        $this->display();
    }

    public function main()
    {
        $this->ip = gethostbyname($_SERVER["SERVER_NAME"]);
        $this->server = $_SERVER['SERVER_SOFTWARE'];
        $this->root = ROOT;
        $this->display();
    }

    public function navlist()
    {
        
        if (! $nav = F('admin_navcache')) {
            $nav = array(
                array(
                    "title" => "栏目管理",
                    "icon" => "&#xe647;",
                    "href" => U('Catetory/myList'),
                    "spread" => false
                ),
                array(
                    "title" => "文章管理",
                    "icon" => "&#xe62a;",
                    "spread" => true,
                    "children" => array(
                          array(
                            "title" => "文章目录",
                            "icon" => "&#xe615;",
                            "href" => U("Article/myList",array(),true,true)
                              
                          ),
                            array(
                                "title" => "添加文章",
                                "icon" => "&#xe609;",
                                "href" => U("Article/add",array(),true,true),
                            ),
                        )
                ),
                array(
                    "title" => "友情链接",
                    "href" => U("Friendlink/myList",array(),true,true),
                    "icon" => "&#xe64c;",
                    "spread" => false
                ),
                array(
                    "title" => "用户管理",
                    "href" => U("User/myList",array(),true,true),
                    "icon" => "&#xe612;",
                    "spread" => false
                ),
                array(
                    "title" => "日志管理",
                    "href" => U("daily/myList",array(),true,true),
                    "icon" => "&#xe637;",
                    "spread" => false
                ),
                array(
                    "title" => "数据管理",
                    "icon" => "&#xe631;",
                    "spread" => false,
                    "children" => array(
                          array(
                            "title" => "数据备份",
                            "icon" => "&#xe601;",
                            "href" => U("Backup/index",array(),true,true)
                              
                          ),
                            array(
                                "title" => "数据还原",
                                "icon" => "&#xe62f;",
                                "href" => U("Backup/dataImport",array(),true,true),
                            ),
                        )
                ),
            );
            F('admin_navcache',$nav);
        }
        if(session('ADMIN_AUTH_NAME')!==C('AUTH_SUPERADMIN'))$this->ajaxReturn(array($nav[1]));
        $this->ajaxReturn($nav);
    }
}