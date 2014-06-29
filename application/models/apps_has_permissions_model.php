<?php

/**
 * Tags Model
 * @author Zank Bo <z@zankbo.com>
 */
//include FCPATH . "phpadmin/ModelField.class.php";

class Apps_has_permissions_model extends CI_Model {
    
//    public function __construct() {
//        parent::__construct();
//        $this->load->database();
//    }

    public function _fields() {
        $this->app_id = new IntField(array(
            "db_index" => TRUE,
        ));
        $this->permission_id = new IntField(array(
            "db_index" => TRUE,
        ));
    }
    
    public function _indexes_together() {
        return array(
            array("app_id","permission_id",TRUE),
        );
    }

}
