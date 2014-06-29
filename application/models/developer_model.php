<?php

//include_once FCPATH . "phpadmin/ModelField.class.php";

class Developer_model extends CI_Model {

    public function _fields() {
        $this->developer_id = new IntField(array(
            "primary_key" => TRUE,
        ));
        $this->name = new CharField(array(
            "label" => "开发者名称",
            "max_length" => 145,
        ));
        $this->email = new EmailField(array(
            "label" => "Email",
            "max_length" => 45,
        ));
        $this->intro = new CharField(array(
            "label" => "简介",
            "max_length" => 245,
        ));
        $this->website = new URLField(array(
            "label" => "官方网站",
            "max_length" => 145,
        ));
        $this->weibo = new CharField(array(
            "label" => "微博",
            "max_length" => 145,
        ));
        $this->verified = new BoolField(array(
            "label" => "官方认证",
            "default" => FALSE,
        ));
    }
    
    // 添加数据时数据格式，一般显示在使用 select2 插件的自动完成数据下拉界面
    public function __searchToString(){
        
    }
    
    // 筛选数据时数据格式，一般在列表页右侧的 filter 区域。为每条记录的样式
    public function __filterToString(){
        
    }
    
    // 默认的记录显示格式
    public function __toString() {
        $string = $this->name;
        if ($this->email) {
            $string.= '(email:' . $this->email . ')';
        }
        return $string;
    }

}
