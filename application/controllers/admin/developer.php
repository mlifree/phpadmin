<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Developer extends ModelAdmin {

    public $list_display = array(
        "name",
        "email",
        "intro",
        "website",
        "verified",
    );
    
    public $inlines = array('apps');

    const VERBOSE_NAME = "开发者";

}
