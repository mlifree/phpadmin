<?php

/**
 * Tags Model
 * @author Zank Bo <z@zankbo.com>
 */
//include FCPATH . "phpadmin/ModelField.class.php";

class App_tags_model extends CI_Model {

    public function _fields() {
        $this->tag_id = new IntField(array(
            "db_index" => TRUE,
        ));
        $this->app_id = new IntField(array(
            "db_index" => TRUE,
        ));
        $this->weights = new IntField(array(
            "label" => "权重",
            "default" => 0,
        ));
    }

    public function _indexes_together() {
        return array(
            array("tag_id", "app_id", TRUE),
        );
    }

}
