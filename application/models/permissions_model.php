<?php

/**
 * Tags Model
 * @author Zank Bo <z@zankbo.com>
 */
//include FCPATH . "phpadmin/ModelField.class.php";

class Permissions_model extends CI_Model {

    public function _fields() {
        $this->permission_id = new IntField(array(
            "primary_key" => TRUE,
        ));
        $this->name = new CharField(array(
            "label" => "权限名称",
            "max_length" => 45,
            "search_field" => TRUE,
        ));
    }

    public function __toString() {
        return $this->name;
    }

}
