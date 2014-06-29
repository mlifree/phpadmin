<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inline {

    // django https://docs.djangoproject.com/en/dev/ref/contrib/admin/#django.contrib.admin.InlineModelAdmin
    const MAX_NUM = 3;

}

class TagsInline extends Inline {

    public $model = 'tags';

//    public $fields="";
}

class Apps extends ModelAdmin {

    public $list_display = array(
        "name",
        "logo",
        "package_id",
        // "创建时间" => "created",
        "package_size",
        "status",
//        "开发者" => "developer_name",
        "developer__name",
//        "developer__website",
//        "developer__city__name",
//        "tags__tag_name",
    );
//    public $list_display_links = array("name");
    public $list_editable = array("name", "status");
    public $search_field = array(
        "name",
        "developer__name"
    );
    public $list_filter = array("developer", "tags");
    public $actions = array(
        "关闭选中项" => "close_status",
        "显示选中项" => "close_status",
    );
//    public $buttons = array(
//        "添加标签" => "/admin/tag/add",
//        "添加作者" => array("/admin/dev/add", "btn-info")
//    );
    public $inlines = array('tags', 'permissions');

//    public function __construct() {
//        parent::__construct();
//        $this->form = new AppsForm();
//    }
    // const MODEL = "apps";
//    const LIST_PER_PAGE = 10;
    const VERBOSE_NAME = "应用";

    // const DISABLE_ADD = FALSE;

    public function show() {
        echo 'test method';
    }

    public function _field_developer_name(&$item) {
//        echo self::MODEL;
        return $item["name"] . "_field_developer_name";
    }

    public function _action_close_status($id_list) {
        print_r("_action_close_status");
        print_r($id_list);
    }

}
