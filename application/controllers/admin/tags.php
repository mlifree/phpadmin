<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tags extends ModelAdmin {

    public $list_display = array(
        "tag_name",
//        "pid"
    );
    public $inlines = array('apps');

}
