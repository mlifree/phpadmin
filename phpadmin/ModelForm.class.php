<?php

class ModelForm {

    public $form;
    public $cache_header = array();

    /**
     * 整个表单的 HTML
     * @var string 
     */
    public $form_html = "";

    /**
     * 扩展的头部引入，如 css 、js
     * @var string 
     */
    public $extra_header = "";

    /**
     * 扩展的底部 js 代码
     * @var string
     */
    public $extra_script = "";
    private $CI;
    private $inline_models = array();
    private $_closed_panel = false;

    public function __construct($ci) {
        $this->CI = $ci;
    }

    /**
     * 创建 Int 类型的 HTML 代码
     * @param string $name 字段名
     * @param type $field 字段的配置
     * @return string 组合好的 HTML 代码
     */
    public function create_int_field($name, $field) {
        $form_html = '<div class="form-group col-xs-7" id="form-group-' . $name . '">';
        $form_html.=$this->label_html . '<div class="col-sm-4">';
        $form_html.='<input type="text" ' . $this->id_name_html . ' class="form-control" value="' . $field->args['default'] . '" />';
        $form_html.='</div></div>';
        return $form_html;
    }

    public function create_unsigned_int_field($name, $field) {
        
    }

    public function create_decimal_field($name, $field) {
        
    }

    public function create_float_field($name, $field) {
        
    }

    public function create_file_size_field($name, $field) {
        $form_html = '<div class="form-group col-xs-7" id="form-group-' . $name . '">';
        $form_html.=$this->label_html . '<div class="col-sm-2">';
        $form_html.='<input type="text" ' . $this->id_name_html . ' class="form-control" value="' . $field->args['default'] . '" />';
        $form_html.='</div></div>';
        return $form_html;
    }

    public function create_char_field($name, $field) {
        $form_html = '<div class="form-group col-xs-7" id="form-group-' . $name . '">';
        $form_html.=$this->label_html . '<div class="col-sm-5">';
        // CharField 
        if ($field->args['choices']) {
            $form_html.= '<select ' . $this->id_name_html . ' class="form-control"><option value="">-----</option>';
            $selected = '';
            foreach ($field->args['choices'] as $key => $value) {
                if ($key == $field->args['default']) {
                    $selected = 'selected';
                }
                $form_html.='<option values="' . $key . '" ' . $selected . '>' . $value . '</option>';
            }
            $form_html.='</select>';
        } else {
            $form_html.= '<input type="text" ' . $this->id_name_html . ' class="form-control" value="' . $field->args['default'] . '" />';
        }
        $form_html.='</div></div>';
        return $form_html;
    }

    private function _check_header_exist($key, $cache = TRUE) {
        $exists = array_key_exists($key, $this->cache_header);
        if (!$exists && $cache) {
            $this->cache_header[$key] = TRUE;
        }
        return $exists;
    }

    /**
     * 生成 link、script 标签
     * @param arry $config array(
     *                          'css'=>array(
     *                                  ),
     *                          'js'=>array(
     *                                  ),
     *                      )
     */
    protected function _header_tag($config) {
        $tag = '';
        foreach ($config as $type => $value) {
            foreach ($value as $path) {
                $base_url = base_url();
                if (substr($path, 0, 5) == 'http:' || substr($path, 0, 6) == 'https:' || substr($path, 0, 2) == '//') {
                    $base_url = '';
                }
                $url_path = $base_url . $path;
                if ($type == 'css') {
                    $tag.='<link rel="stylesheet" href="' . $url_path . '">';
                } elseif ($type == 'js') {
                    $tag.='<script src="' . $url_path . '"></script>';
                }
            }
        }
        return $tag;
    }

    public function create_editor_field($name, $field) {
        $form_html = '<div class="form-group col-xs-7" id="form-group-' . $name . '">';
        $form_html.=$this->label_html . '<div class="col-sm-5">';
        if ($field->args['style'] == 'ueditor') {
            if (!$this->_check_header_exist('ueditor')) {
                $this->extra_header.=$this->_header_tag(array(
                    'js' => array(
                        'static/plugins/ueditor/ueditor.config.js',
                        'static/plugins/ueditor/ueditor.all.js',
                        'static/plugins/ueditor/lang/zh-cn/zh-cn.js',
                    ),
                ));
            }
            $width = $field->args['width'];
            $height = $field->args['height'];
            $form_html.='<textarea style="width:' . $width . ';height:' . $height . ';" ' . $this->id_name_html . '>' . $field->args['default'] . '</textarea>';
            $this->extra_script.='UE.getEditor("field-' . $name . '",{});';
        }
        $form_html.='</div></div>';
        return $form_html;
    }

    public function create_email_field($name, $field) {
        return $this->create_char_field($name, $field);
    }

    public function create_u_r_l_field($name, $field) {
        return $this->create_char_field($name, $field);
    }

    public function create_date_field($name, $field) {

        $form_html = '<div class="form-group col-xs-7" id="form-group-' . $name . '">';
        $form_html.=$this->label_html . '<div class="col-sm-5"  style="z-index:999;">';
        $form_html.='<input type="text" class="form-control" ' . $this->id_name_html . ' value="' . $field->args['default'] . '" />';
        if (!$this->_check_header_exist('datepicker')) {
            $this->extra_header.=$this->_header_tag(array(
                'css' => array(
                    'static/plugins/bootstrap-datepicker/css/datepicker.css'
                ),
                'js' => array(
                    'static/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'
                ),
            ));
        }
        $this->extra_script .= '$("#' . $this->field_id . '").datepicker({
	    format: "yyyy-mm-dd",
	    todayBtn: true,
	    todayHighlight: true,
	    showTime: false,
//        initialDate : "",
	});';
        $form_html.='</div></div>';
        return $form_html;
    }

    public function create_time_field($name, $field) {
        
    }

    public function create_date_time_field($name, $field) {
        $form_html = '<div class="form-group col-xs-7" id="form-group-' . $name . '">';
        $form_html.=$this->label_html . '<div class="col-sm-5">';
        $form_html.='<input type="datetime" ' . $this->id_name_html . ' value="' . $field->args['default'] . '" />';
        $form_html.='</div></div>';
        return $form_html;
    }

    /**
     * 根据 args 配置组合一些标签属性，如 width="" height=""
     * @param type $field
     * @return string attr html
     */
    private function _attrs_html($field) {
        $attrs = ' ';
        if ($field->args['width']) {
            $attrs.='width="' . $field->args['width'] . '" ';
        }
        if ($field->args['height']) {
            $attrs.='height="' . $field->args['height'] . '" ';
        }
        return $attrs;
    }

    public function create_text_field($name, $field) {
        $form_html = '<div class="form-group col-xs-7" id="form-group-' . $name . '">';
        $form_html.=$this->label_html . '<div class="col-sm-8">';
        if ($field->args['default']) {
            $field->args['default'] = str_replace("<br />", "\n", $field->args['default']);
        }
        $attrs = ' ';
        if ($field->args['width']) {
            $attrs.='width="' . $field->args['width'] . '" ';
        }
        if ($field->args['height']) {
            $attrs.='height="' . $field->args['height'] . '" ';
        }
        $form_html.='<textarea class="form-control" ' . $attrs . $this->id_name_html . ' rows="8">' . $field->args['default'] . '</textarea>';
        $form_html.='</div></div>';
        return $form_html;
    }

    public function create_file_field($name, $field) {
        $form_html = '';
        $default_html = '';
        if (!empty($field->args['default'])) {
            $default_html = '<img src="' . $field->args['default'] . '"'.$this->_attrs_html($field).'/>';
        }
        if ($field->args['style'] == 'default') {
            $form_html.='<div class="form-group col-xs-7" id="form-group-' . $name . '">';
            $form_html.=$this->label_html . '<div class="col-sm-5">';
            $form_html.='<input type="file" ' . $this->id_name_html . ' />';
        } elseif ($field->args['style'] == 'webuploader') {
            $this->extra_header.=$this->_header_tag(array(
                'css' => array(
                    'static/plugins/webuploader/webuploader.css',
                ),
                'js' => array(
                    'static/plugins/webuploader/webuploader.js',
                ),
            ));
            $form_html.='<div class="form-group col-xs-7" id="form-group-' . $name . '">';
            $form_html.=$this->label_html . '<div class="col-sm-5">';
        } elseif ($field->args['style'] == 'uploadify') {
            if (!$this->_check_header_exist('uploadify')) {
                $this->extra_header.=$this->_header_tag(array(
                    'css' => array(
                        'static/plugins/uploadify/uploadify.css',
                    ),
                    'js' => array(
                        'static/plugins/uploadify/jquery.uploadify.min.js',
                    ),
                ));
            }

            $form_html.='<div class="form-group col-xs-7" id="form-group-' . $name . '">';
            $form_html.=$this->label_html . '<div class="col-sm-5">';
            $form_html.='<input type="file" ' . $this->id_name_html . ' />' . $default_html;
            $this->extra_script .= '$("#' . $this->field_id . '").uploadify({
        "swf"      : "' . base_url() . 'static/plugins/uploadify/uploadify.swf",
        "uploader" : "uploadify.php"
    });';
        }
        $form_html.='</div></div>';
        return $form_html;
    }

    public function create_image_field($name, $field) {
        return $this->create_file_field($name, $field);
    }

    public function create_file_path_field($name, $field) {
        
    }

    public function create_i_p_address_field($name, $field) {
        
    }

    public function create_bool_field($name, $field) {
        $checked = $field->args['default'] ? 'checked' : '';
        $form_html = '<div class="form-group col-xs-7" id="form-group-' . $name . '">';
        $form_html.=$this->label_html . '<div class="col-sm-5">';
        $form_html.='<input type="checkbox" ' . $this->id_name_html . ' value="1" ' . $checked . ' />';
        $form_html.='</div></div>';
        return $form_html;
    }

    public function create_one_to_one_field($name, $field) {
        
    }

    public function create_foreign_key($name, $field) {
        $model_name = $this->model_name;
        $form_html = '';
        if ($field instanceof OneToOneField || $field instanceof ForeignKey) {
            $form_html.='<div class="form-group col-xs-7" id="form-group-' . $name . '">';
            $form_html.=$this->label_html . '<div class="col-sm-5">';

            $relate_model = $field->_RelatedModel;
            $relate_model_name = $relate_model . '_model';
            $enforce_list = $this->CI->$model_name->$name->args['enforce_list'];
            if ($relate_model == 'self') {
                $relate_model = $this->CI->_model_name;
            }
            $ajax_url = site_url('admin/' . $this->CI->_control_name . '/related_search?_field=' . $name);
            //开启了强制显示所有可以选择的关联模型数据
            if ($enforce_list) {
                $form_html.= '<select ' . $this->id_name_html . ' class="form-control"><option value="">-----</option>';
                $this->load->database();

                $data = $this->db->get($relate_model)->result_array();
                foreach ($data as $value) {
                    $form_html.='<option values="' . $value['developer_id'] . '">' . $value['name'] . '</option>';
                }
                $form_html.='</select>';
            } else {
                $form_html.='<input type="text" ' . $this->id_name_html . ' />';
                if (!$this->_check_header_exist('select2')) {
                    $this->extra_header.=$this->_header_tag(array(
                        'css' => array(
                            'static/plugins/select2/select2.css',
                        ),
                        'js' => array(
                            'static/plugins/select2/select2.js'
                        ),
                    ));
                }
                $this->extra_script.='$("#' . $this->field_id . '").select2({
    placeholder: "Search for a ' . $name . '",
    minimumInputLength: 2,
    ajax: { 
        url: "' . $ajax_url . '",
        dataType: "json",
        data: function (term, page) {
            return {
                q: term,
            };
        },
        results: function (data, page) { 
            return {results: data};
        }
    },
//    formatResult:function(item){
//        return item.name + item.email;
//    },
//    formatSelection: function(item){ // omitted for brevity, see the source of this page
//        return "<p>"+item.name + item.email+"</p>";
//    },
//    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
    escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
});';
            }
        }
        $form_html.='</div></div>';
        return $form_html;
    }

    public function create_many_to_many_field($name, $field) {
        // * 有可能出现多对多中间表出现其他字段的情况，如：app 可能在不同栏目有不同的权重
        $model_name = $this->model_name;
        $form_html = '';
        //结束上一个面版
        if (!$this->_closed_panel) {
            $form_html .= '</div></div>';
            $this->_closed_panel = TRUE;
        }
        //开始新的多对表字段的面板
        $form_html.='<div class="panel panel-primary"><div class="panel-heading">' . $name . '</div>';
//        $form_html.='<div class="form-group col-xs-7" id="form-group-' . $name . '">';
//        $form_html.=$this->label_html . '<div class="col-sm-5">';


        $through_model = $field->_ThroughMolde;
        $through_model_name = $through_model . '_model';
        $this->CI->get_model_instance($through_model);

        $relate_model = $field->_RelatedModel;
        $relate_model_primary = $this->CI->_get_model_primary_key($field->_RelatedModel, FALSE);
        $id_name_html = 'id="' . $this->field_id . '" name="' . $name . '[0][' . $relate_model_primary . ']"';
//        $relate_model_name = $relate_model . '_model';
        $inline_obj = $this->inline_models[$name];
        $enforce_list = $this->CI->$model_name->$name->args['enforce_list'];
        $ajax_url = site_url('admin/' . $this->CI->_control_name . '/related_search?_field=' . $name);
        $through_field_html = '';
        if ($inline_obj instanceof CI_Model) {
            if ($enforce_list) {
                $through_field_html.= '<select ' . $id_name_html . ' class="form-control"><option value="">-----</option>';
//                $this->CI->load->model($relate_model_name);
                $this->CI->load->database();
                $data = $this->CI->db->get($relate_model)->result_array();
                foreach ($data as $value) {
                    $through_field_html.='<option values="' . $value['tag_id'] . '">' . $value['tag_name'] . '</option>';
                }
                $through_field_html.='</select>';
            } else {
                $through_field_html.='<input type="text" class="' . $this->field_id . '" ' . $id_name_html . ' />';
                if (!$this->_check_header_exist('select2')) {
                    $this->extra_header.=$this->_header_tag(array(
                        'css' => array(
                            'static/plugins/select2/select2.css',
                        ),
                        'js' => array(
                            'static/plugins/select2/select2.js'
                        ),
                    ));
                }
                $this->extra_script.='$(".' . $this->field_id . '").select2({
    placeholder: "Search for a ' . $name . '",
    minimumInputLength: 1,
    ajax: { 
        url: "' . $ajax_url . '",
        dataType: "json",
        data: function (term, page) {
            return {
                q: term,
            };
        },
        results: function (data, page) { 
            return {results: data};
        }
    }
});';
            }
        } elseif ($inline_obj instanceof Inline) {
            
        }

        $form_html.='<table class="table table-bordered">';
        $form_html.='<tr>';
        $through_html = '<tr>';
        foreach ($this->CI->$through_model_name as $field_name => $through_field) {
            if ($field_name == $this->CI->_primary_key) {
                continue;
            }
            $form_html.='<th>';
            $form_html.=$through_field->args['label'] ? : $field_name;
            $form_html.='</th>';
            $through_html .= '<td>';
            if ($field_name == $relate_model_primary) {
                $through_html.=$through_field_html;
            } else {
                $this->label_html = '';
                $this->id_name_html = 'id="field-' . $name . '-' . $field_name . '" name="' . $name . '[0][' . $field_name . ']"';
                $this->field_id = 'field-' . $name . '-' . $field_name;
                $field_method = $this->_get_field_method($through_field);
                $through_html .= $this->$field_method($field_name, $through_field);
            }
            $through_html .= '</td>';
        }
        $through_html .= '<td><a href="###"><span class="glyphicon glyphicon-trash"></span></a></td>';
        $through_html .= '</tr>';
        $form_html.='<th>操作</th>';
        $form_html.='</tr>';
        $form_html.=$through_html;
        $form_html.='</table>';


        //结束新的多对表字段的面板
        $form_html.='</div>';
        return $form_html;
    }

    /**
     * 同时需要添加那些关联模型中的数据
     */
    private function _get_inline_models() {
        /**
         * 控制器中 Inline 可能的值：
         *  - $inlines = array('tags','permissions'); 直接写 Model 名称，则默认显示 inline model 中的所有字段
         *  - $inlines = array('tagsInline'); 通过 Inline 的方式，可以自定义显示的字段、字段排序
         */
        foreach ($this->CI->inlines as $inline) {
            $inline_model = $inline;
            // 自定义关联模型的显示方式
            if (substr($inline, -6) == 'Inline') {
                $inline_obj = new $inline;
                $inline_model = $inline_obj->model;
                if (!$inline_model) {
                    $inline_model = strtolower(substr($inline, 0, -6));
                }
                $this->inline_models[$inline_model] = $inline_obj;
            } else {
                $inline_model_name = $inline . "_model";
                $this->CI->load->model($inline_model_name);
                $this->CI->$inline_model_name->_fields();
                $this->inline_models[$inline] = $this->CI->$inline_model_name;
            }
            //* 有可能出现反向 inline 的情况，如添加 tag 的时候同时添加 App
        }
    }

    private function _get_field_method($field) {
        return 'create' . strtolower(strtr(addcslashes(get_class($field), 'A..Z'), '\\', '_'));
    }

    /**
     * 生成所以字段的 HTML
     */
    public function html() {
        $model_name = $this->CI->_model_name . "_model";
        $this->model_name = $model_name;

        /**
         * 可能出现自定义字段显示顺序、字段分组的情况。
         * 总之这里应该做成具有很好的灵活性：
         *   - 最简单的情况，即这时的表单会依次显示 Model 中的所有字段
         *   - 用户可以很方便的把几个 checkbox 字段放在一起（一个面板中）
         *   - 也可能出现把字段放到不同的选项卡中（就像 ecshop 添加商品那样）
         *   - 可能会出现其他的布局方式，暂时未想到
         */
        if (!$this->form) {
            // 最简单的情况
            $this->form = $this->CI->form;
        }
        if (!$this->form) {
            $this->CI->load->model($model_name);
            $this->CI->$model_name->_fields();
            $this->form = $this->CI->$model_name;
        }

        $this->_get_inline_models();

        $this->form_html.='<div class="panel panel-primary"><div class="panel-heading">' . $this->CI->_verbose_name . '</div><div class="panel-body">';
        foreach ($this->form as $name => $field) {
            if (!$field instanceof Field) {
                continue;
            }

            // 忽略主键的界面显示
            //   * 需要注意主键有可能是一个字符串，这时候是应该可以在界面显示，被用户填写的，
            //   * 此处可能会有问题！！ 因为可能会出现存在多个主键的情况
            if (($field instanceof IntField) && $field->args["primary_key"]) {
                continue;
            }
            $this->label = $field->args["label"];
            if (!$this->label) {
                $this->label = strtr($name, '_', ' ');
            }
            if ($field instanceof ManyToManyField && !isset($this->inline_models[$name])) {
                continue;
            }


            $this->label_html = '<label for="field-' . $name . '" class="col-sm-2 control-label">' . $this->label . '</label>';
            $this->field_id = 'field-' . $name;
            $this->id_name_html = 'id="' . $this->field_id . '" name="' . $this->CI->_model_name . '[' . $name . ']"';
            $method_name = $this->_get_field_method($field);
            $this->form_html.=$this->$method_name($name, $field);
        }
        $this->form_html.='</div></div>';
        return $this->form_html;
    }

}
