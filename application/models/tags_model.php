<?php

/**
 * Tags Model
 * @author Zank Bo <z@zankbo.com>
 */
//include FCPATH . "phpadmin/ModelField.class.php";

class Tags_model extends CI_Model {

    public function _fields() {
        $this->tag_id = new IntField(array(
            "primary_key" => TRUE,
        ));
        $this->tag_name = new CharField(array(
            "label"=>"标签名",
            "max_length" => 45,
            "search_field"=>TRUE,
        ));
        $this->pid = new ForeignKey("self",array(
            "label"=>"父级标签",
        ));
        $this->status = new BoolField(array(
            "label"=>"显示",
            "default" => FALSE,
        ));
    }
    
    public function __toString() {
        return $this->tag_name;
    }

}
