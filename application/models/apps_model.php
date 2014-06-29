<?php

//include FCPATH . "phpadmin/ModelField.class.php";

class Apps_model extends CI_Model {

    // function __construct(){
    // parent::__construct();
    // }

    public function _fields() {
        $this->app_id = new IntField(array(
            "primary_key" => TRUE,
        ));
        $this->name = new CharField(array(
            "default" => "",
            "label" => "软件名称",
            "help_text" => "请输入软件名称，如：手机QQ"
        ));
        $this->alias_name = new CharField(array(
            "label" => "别名",
        ));
        $this->package_id = new CharField(array(
            "label" => "包ID",
        ));
        $this->logo = new ImageField(array(
            'label' => "Logo",
            'style' => 'uploadify',
            'width' => "55",
        ));
        $this->description = new EditorField(array(
            "label" => "描述",
        ));
        $this->is_gooddev = new BoolField(array(
            "label" => "优质应用",
        ));
        $this->safe = new BoolField(array(
            "label" => "安全应用",
        ));
        $this->trusted = new BoolField(array(
            "label" => "受信任应用",
        ));
        $this->no_ad = new BoolField(array(
            "label" => "无广告",
        ));
        $this->official = new BoolField(array(
            "label" => "官方应用",
        ));
        $this->updated = new DateField(array(
            "label" => "更新日期",
        ));
        $this->version = new CharField(array(
            "label" => "版本",
        ));
        $this->beta = new BoolField(array(
            "label" => "是否 beta 版",
        ));
        $this->app_type = new CharField(array(
            "choices" => array(
                "APP" => "软件",
                "GAME" => "游戏",
            ),
        ));
        $this->editor_comment = new EditorField(array(
            "label" => "编辑简介",
        ));
        $this->changelog = new TextField(array(
            "label" => "更新日志",
        ));
        $this->comments_count = new IntField(array(
            "label" => "评论总数",
        ));
        $this->download_count = new IntField(array(
            "label" => "下载量",
        ));
        $this->installed_count = new IntField(array(
            "label" => "安装量",
        ));
        $this->package_size = new FileSizeField(array(
            "label" => "软件大小",
//            "validators" => array(),
        ));
        $this->status = new BoolField(array(
            "label" => "状态",
        ));

        $this->developer = new ForeignKey("developer", array(
            'search_fields' => array('name', 'email'),
        ));
        $this->tags = new ManyToManyField("tags", "app_tags", array(
            'search_fields' => array('tag_name'),
//            'enforce_list' => TRUE,
             "label" => "标签",
        ));
        $this->permissions = new ManyToManyField("permissions", "apps_has_permissions");
    }

    /**
     * 如果需要验证某个字段，则以 _valid_字段名 的方式命名方法，并做验证
     * @param mixed $value POST获取的值
     * @return bool 如果字段安全则返回 TRUE，否则 FALSE
     */
    public function _valid_package_size($value) {
        
    }

    /*
     *  配置 Model 的索引
     */

    public function _indexes_together() {
//        return array(
//            array("app_name", "package_id"),
//            "index_name" => array("app_name", "package_id"),
//            array("app_name", "package_id",PRIMARY_INDEX),//最后一个值为 PRIMARY_INDEX 时，为Primary key index
//        );
    }

    public function __toString() {
        return strval($this->app_name);
    }

}
