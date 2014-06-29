<?php

//定义索引类型
define("PRIMARY_INDEX", 1);
define("UNIQUE_INDEX", 2);

/**
 * $args:
 *       primary_key
 *       unique
 *       verbose_name
 *       validators
 *       null
 *       blank
 *       default
 *       help_text
 *       editable
 *       choices
 *       db_column
 *       db_index
 *       
 *       error_messages
 */
class Field {

    public $args = array(
        "primary_key" => FALSE,
        "unique" => FALSE,
        "label" => "",
        "verbose_name" => "",
        "validators" => NULL,
        "null" => FALSE,
        "blank" => FALSE,
        "default" => "",
        "help_text" => "",
        "editable" => TRUE,
        "choices" => NULL,
        "db_column" => NULL,
        "db_index" => FALSE,
        "error_messages" => "",
        "max_length" => 0,
        "search_field" => FALSE, //是否为搜索字段，改设置并不会为字段创建索引，而仅仅用于手台添加界面获取关联模型数据时，如果数据太多默认则使用搜索，而不将数据全部列出来
    );

    function __construct($args = array()) {
        $this->args = array_merge($this->args, $args);
    }

    public function __toString() {
        return $this->args["default"];
    }

}

class IntField extends Field {

    function __construct($args = array()) {
        $this->args = array_merge($this->args, array(
            'unit' => '', //单位
                ), $args);
    }

    public function __toString() {
        return $this->args["default"];
    }

}

class UnsignedIntField extends Field {

    function __construct($args = array()) {
        $this->args = array_merge($this->args, $args);
    }

}

class DecimalField extends Field {
    
}

class FloatField extends Field {
    
}

class FileSizeField extends IntField {

    public function format_value($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

}

class BoolField extends Field {

    function __construct($args = array()) {
        $this->args = array_merge($this->args, array("default" => FALSE), $args);
    }

}

class CharField extends Field {

    function __construct($args = array()) {
        $this->args = array_merge($this->args, $args);
    }

    public function __toString() {
        return $this->args["default"];
    }

}

class TextField extends Field {

    function __construct($args = array()) {
        $this->args = array_merge($this->args, $args);
    }

}

class EditorField extends TextField {

    function __construct($args = array()) {
        $this->args = array_merge($this->args, array(
            "style" => "ueditor", //编辑器的样式，ueditor（百度）、simditor（tower）,
            "width" => "700px",
            "height" => "320px",
                ), $args);
    }

}

class TimeField extends DateField {
    
}

class DateField extends Field {

    function __construct($args = array()) {
        $this->args = array_merge(
                $this->args, array(
            "auto_now" => FALSE,
            "auto_now_add" => FALSE
                ), $args
        );
    }

}

class DateTimeField extends DateField {
    
}

class EmailField extends CharField {
    
}

class URLField extends CharField {
    
}

class FileField extends CharField {

    function __construct($args = array()) {
        $this->args['style'] = 'default'; //显示样式：default(默认的文件上传框),webuploader(百度出品),uploadify,jQueryFileUpload 
        $this->args = array_merge($this->args, $args);
    }

}

class FilePathField extends CharField {
    
}

class ImageField extends FileField {

    function __construct($args = array()) {
        parent::__construct();
        $this->args = array_merge(
                $this->args, array(
            "width" => "50%",
            "height" => 0,
                ), $args
        );
    }

}

class IPAddressField extends Field {
    
}

/**
 * 一对一关联
 */
class OneToOneField extends Field {

    public $_RelatedModel = "";

    function __construct($model, $args = array()) {
        $this->_RelatedModel = $model;
        $this->args = array_merge($this->args, array(
            'enforce_list' => FALSE,
                ), $args);
    }

}

class ForeignKey extends Field {

    public $_RelatedModel = "";

    /**
     * 一对多字段
     * @param string $name 关联 Model
     * @param array  $args 其他可选参数
     */
    function __construct($model, $args = array()) {
        $this->_RelatedModel = $model;
        $this->args = array_merge($this->args, array(
            'enforce_list' => FALSE,
//            'search_fields'=>array(), 可供搜索的字段、优先级高于在 Model field 中的设置
                ), $args);
    }

}

/**
 * 多对多关联
 */
class ManyToManyField extends Field {

    public $_ThroughMolde = "";
    public $_RelatedModel = "";

    /**
     * 多对多关联
     * @param string $model 关联的模型
     * @param string $through 中间表 Model，可以为空。默认为 table_has_anothertable Model
     * @param array $args 其他可选参数
     */
    function __construct($model, $through = "", $args = array()) {
//        args:
//            enforce_list  强制列出选择数据。默认使用搜索的方式，因为可能出现有大量数据的情况
        $this->_RelatedModel = $model;
        $this->_ThroughMolde = $through;
        $this->args = array_merge($this->args, array(
            'enforce_list' => FALSE,
            'foreign_field' => '', //关联表在中间表的字段名
            'parent_field' => '', //在中间表的字段名
                ), $args);
    }

}
