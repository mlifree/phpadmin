<?php

//include FCPATH . "phpadmin/ModelField.class.php";

class City_model extends CI_Model {

    public function _fields() {
        $this->city_id = new IntField(array(
            "primary_key" => TRUE,
        ));
        $this->name = new CharField(array(
            "label" => "城市名",
            "max_length" => 145,
        ));
    }

}
