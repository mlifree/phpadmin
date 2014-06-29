<?php

/**
 * Tags Model
 * @author Zank Bo <z@zankbo.com>
 */
//include FCPATH . "phpadmin/ModelField.class.php";

class Screenshot_model extends CI_Model {

    public function _fields() {
        $this->id = new IntField(array(
            "primary_key" => TRUE,
        ));
        $this->app_id = new ForeignKey("apps");
        $this->src = new ImageField(array(
            "label" => "图片地址",
        ));
    }

}
