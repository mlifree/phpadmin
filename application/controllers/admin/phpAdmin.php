<?php

include FCPATH . "/phpadmin/ModelAdmin.class.php";

//define("AdminControlPath", __DIR__);
class phpAdmin extends ModelAdmin {

    public $AdminControlPath = __DIR__;
    // 注册的 Model，只有注册的 Model 才能通过地址栏访问，进行数据管理
    public $register_model = array(
        "应用" => "apps",
        "开发者" => "developer",
        // 将一些 Model 统一注册到某个导航菜单下
        "更多" => array(
            "标签" => "tags",
            "permissions",
            // 某个导航菜单下的自定义菜单
            array(
                "url" => "http://www.weibo.cn",
                "text" => "Weibo",
            ),
        ),
        // 某个自定义菜单
        array(
            "url" => "/admin/demo", //可能会出现打开的是外部URL
            "text" => "Baidu",
            "target" => "_blank",
        ),
    );

}
