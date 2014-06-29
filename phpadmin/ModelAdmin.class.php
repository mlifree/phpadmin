<?php

include FCPATH . "phpadmin/ModelField.class.php";

class ModelAdmin extends CI_Controller {

    protected $list_display = array();
    public $list_display_links = array();
    public $list_editable = array();
    public $actions = array();
    public $actions_on_top = array();
    public $actions_on_bottom = array();
    public $date_hierarchy;
    public $fields;
    public $fieldsets;
    public $filter_horizontal;
    public $filter_vertical;
    public $form;
    public $formfield_overrides;
    public $inlines = array();
    public $list_filter = array();
    public $list_max_show_all;
    public $list_select_related;
    public $ordering;
    public $paginator;
    public $prepopulated_fields;
    public $preserve_filters;
    public $radio_fields;
    public $raw_id_fields;
    public $readonly_fields;
    public $save_as;
    public $save_on_top;
    public $search_fields;
    public $view_on_site;
    public $add_form_template;
    public $change_form_template;
    public $change_list_template;
    public $delete_confirmation_template;
    public $delete_selected_confirmation_template;
    public $buttons = array();
    public $register_model = array();
    //导航菜单栏
    private $register_menu = array();

    const MODEL = "";
    const VERBOSE_NAME = "";
    const VERBOSE_NAME_PLURAL = "";
    const LIST_PER_PAGE = 25;
    const DISABLE_ADD = FALSE;
    const DISABLE_DEL = FALSE;

    /**
     * 禁止的项目，值列表：ADD_DATA,DEL_DATA 
     * @var array 
     */
    protected $disabled;
    protected $AdminControlPath = "";
    private static $_admin_instance = NULL;

    public function __construct() {
        parent::__construct();
    }

    public static function _get_instance($control) {
        if (is_null(self::$_admin_instance)) {
//            parent::get_instance();
            self::$_admin_instance = new $control();
        }
        return self::$_admin_instance;
    }

    /**
     * load model
     * @param string $model
     */
    public function get_model_instance($model) {
        $model_name = $model . "_model";
        if (!isset($this->$model_name)) {
            $this->load->model($model_name);
            $this->$model_name->_fields();
        }
    }

    private function _check_login() {
        if (uri_string() != "admin/auth" && (!isset($_SESSION["logined"]) || !$_SESSION["logined"])) {
//            redirect("/admin/auth");
        }
    }

    private function _check_user_permissons() {
        
    }

    public function delete_model() {
        
    }

    /**
     * 删除选中的数据行
     * @param array $id_list 选中行的主键值列表
     */
    protected function _action_delete_selected($id_list) {
        print_r($id_list);
    }

    /**
     * 保存用户的操作日志
     */
    protected function save_logging() {
        
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

    public function related_search() {
        $related_field = $this->input->get('_field');
        $q = $this->input->get('q');
        $control_model = $this->_model_name . '_model';
        $this->get_model_instance($this->_control_name);
        $search_fields = array();
        if (!empty($this->$control_model->$related_field->args['search_fields'])) {
            $search_fields = $this->$control_model->$related_field->args['search_fields'];
        }
        $related_field_obj = $this->$control_model->$related_field;
        if ($related_field_obj instanceof ForeignKey && $related_field_obj->_RelatedModel == 'self') {
            // 是一个 ForeignKey 字段，并且关联到了自己。类似于父级 id 字段
            $related_model = $control_model;
            $related_field = $this->_model_name;
        } else {
            $related_model = $related_field . '_model';
            $this->get_model_instance($related_field);
        }
        if (!$search_fields) {
            foreach ($this->$related_model as $field_name => $field) {
                if ($field->args['search_field']) {
                    $search_fields[] = $field_name;
                }
            }
        }
        if (!$search_fields) {
            show_error('没有在 ' . $control_model . '.' . $related_field . ' 或 ' . $related_model . ' 中找到可供搜索的字段。<a href="#" target="_blank">如何设置？</a>', 501);
        }
        $this->load->database();
        foreach ($search_fields as $field) {
            $this->db->or_like($field, $q);
        }
        $result = $this->db->get($related_field)->result_array();
        $related_model_primary = $this->_get_model_primary_key($related_field, FALSE);
        $format_result = array();
        $has_tostring = method_exists($this->$related_model, '__toString');
        foreach ($result as $item) {
            foreach ($item as $field => $value) {
                $this->$related_model->$field = $value;
            }
            $text = $has_tostring ? strval($this->$related_model) : $related_field . ' Object';
            $format_result[] = array(
                'id' => $item[$related_model_primary],
                'text' => $text,
            );
        }
        exit(json_encode($format_result));
    }

    public function add_view() {
        
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            p($_POST);
        }
        include FCPATH . "phpadmin/ModelForm.class.php";
        $this->_primary_key = $this->_get_model_primary_key($this->_model_name, FALSE);
        $form = new ModelForm($this);
        $form_html = $form->html();
        $context = array(
            'extra_header' => $form->extra_header,
            'extra_script' => $form->extra_script,
            'form_html' => $form_html,
        );
        $this->load->view("admin/add.php", $context);
    }

    /**
     * 修改数据界面
     * @param type $primary_value
     */
    public function edit($primary_value) {
        $primary_key = $this->_primary_key;
        if ($this->_model_instance->$primary_key instanceof IntField) {
            $primary_value = intval($primary_value);
        }
        $this->get_model_instance($this->_model_name);
        $model_class = $this->_model_class;
        $this->form = $this->$model_class;
        $this->load->database();
        $cond = array(
            $this->_primary_key => $primary_value,
        );
        $result = $this->db->where($cond)->get($this->_model_name)->row_array();
        foreach ($result as $field_name => $value) {
            if (isset($this->form->$field_name)) {
                $this->form->$field_name->args['default'] = $value;
            }
        }
        include FCPATH . "phpadmin/ModelForm.class.php";
        $form = new ModelForm($this);
        $form_html = $form->html();
        $context = array(
            'extra_header' => $form->extra_header,
            'extra_script' => $form->extra_script,
            'form_html' => $form_html,
        );
        $this->load->view("admin/add.php", $context);
    }

    public function save_model() {
        
    }

    /**
     * 获取某个 Model 的主键字段
     * @param string $model Model 名称
     */
    public function _get_model_primary_key($model, $concat = TRUE) {
        $model_name = $model . "_model";
        $this->get_model_instance($model);
        $primary_key = $model . "_id";
        foreach ($this->$model_name as $field => $field_obj) {
            if ($field_obj->args["primary_key"]) {
                $primary_key = $field;
                break;
            }
        }
        return $concat ? $model . '.' . $primary_key : $primary_key;
    }

    /**
     * 列表页处理
     */
    protected function list_view() {
        $model_class = $this->_model_class;

        $_s = $this->input->get('_s');
        $model_primary_key = $this->_get_model_primary_key($this->control_name);
        $model_primary_field = strtr($model_primary_key, '.', '_');
        if (!$this->list_display_links) {
            $this->list_display_links = array_slice($this->list_display, 0, 1);
        }
        foreach ($this->list_display_links as &$link_field) {
            if (property_exists($this->$model_class, $link_field)) {
                $link_field = $this->model_name . "_" . $link_field;
            }
        }

        $this->load->database();

        $custom_field = array();
        $select_field = array();
        $field_info = array();
        $join_table = array();

        $_select_field = $this->list_display;
        // * 注意 list_display 中可能出现多对对的字段，暂时应该不予支持
        foreach ($_select_field as $label => $field) {
            if (is_int($label)) {
                $label = $field;
            }
            if (property_exists($this->$model_class, $field)) {
                //标准 Model 中的字段
                $label = $this->$model_class->$field->args["label"];
                $model_field = $this->_model_name . "." . $field;
                $field_name = strtr($model_field, ".", "_");
                $field_key = $model_field . " AS " . $field_name;
                $select_field[] = $field_key;
                $field_type = get_class($this->$model_class->$field);
                $field_desc = array(
                    "field" => $field,
                    "model_field" => $model_field,
                    "table" => $this->_model_name,
                    "label" => $label,
                    "field_type" => $field_type,
                    "field_object" => &$this->$model_class->$field,
                );
            } else if (strpos($field, "__") !== FALSE) {
                /*
                 *  显示关联model中的字段
                 *  可能出现的形式有：
                 *      developer__name 开发者的姓名，一张表关联
                 *      developer__city__name 开发者资料的城市名, 关联多张表
                 *  $list_display 中可能有显示多个关联 model 中的字段
                 *      public $list_display = array("developer__name", "anthortable__file")
                 */
                $field_ex = explode('__', $field);
                $last_field = array_pop($field_ex);
                $last_table = end($field_ex);
                $join_table = array_merge($join_table, $field_ex);

                $last_table_model = $last_table . "_model";
                $this->load->model($last_table_model);
                $this->$last_table_model->_fields();

                $model_field = $last_table . "." . $last_field;
                $field_name = strtr($model_field, ".", "_");
                $field_key = $model_field . " AS " . $field_name;
                $select_field[] = $field_key;
                $field_type = get_class($this->$last_table_model->$last_field);
                $field_desc = array(
                    "field" => $last_field,
                    "model_field" => $model_field,
                    "table" => $last_table,
                    "label" => $this->$last_table_model->$last_field->args["label"],
                    "field_type" => $field_type,
                    "field_object" => &$this->$last_table_model->$last_field,
                );
            } else if (method_exists($this, "_field_" . $field)) {
// 自定义的字段，即控制器中以_field_开始的方法
                $custom_field[$field] = "_field_" . $field;
                $field_name = $field;
                $field_desc = array(
                    "field" => $field,
                    "model_field" => NULL,
                    "table" => NULL,
                    "label" => $label,
                    "field_type" => NULL,
                    "field_object" => NULL,
                );
            } else {
                _exception_handler(E_ERROR, "未知的显示字段:" . $field, __FILE__, __LINE__);
            }
            $field_desc["editable"] = in_array($field, $this->list_editable);
            $field_info[$field_name] = $field_desc;
        }

        // 搜索数据
        $search_field = array();
        $display_search_form = !empty($this->search_field);
        $handle_search = $display_search_form && !empty($_s);
        if ($handle_search) {
            // 可能出现 list_display 里的字段没有，但是 search_field 中需要搜索该字段。所以需要再次处理 $join_table
            foreach ($this->search_field as $field_name) {
                if (strpos($field_name, "__") !== FALSE) {
                    $field_ex = explode('__', $field_name);
                    $last_field = array_pop($field_ex);
                    $last_table = end($field_ex);
                    $join_table = array_merge($join_table, $field_ex);
                    $field = $last_table . '.' . $last_field;
                } else {
                    $field = $this->_model_name . '.' . $field_name;
                }
                $search_field[] = $field;
            }
        }

        // list_filter
//        p($this->list_filter);
        foreach ($this->list_filter as $field_name) {
            // 如果过滤字段包括 __ 
            if (strpos($field_name, "__") !== FALSE) {
                 
            } else if (property_exists($this->_model_instance, $field_name)) {
                // 过滤字段为本 Model 下的字段
                $field = $this->_model_instance->$field_name;
                if ($field instanceof ForeignKey) {
//                    p($this->db->select('developer_id,name')->get($field->_RelatedModel)->result_array());
                } else if ($field instanceof ManyToManyField) {
                    
                }
            }
        }

        $join_table = array_unique($join_table);

        /**
         * 处理 join table
         */
        foreach ($join_table as $table) {
            if (property_exists($this->_model_instance, $table)) {
                $db_column = $this->_model_instance->$table->args['db_column'];
                if ($db_column) {
                    $master_join_field = $this->_model_name . '.' . $db_column;
                } else {
                    $master_join_field = $this->_model_name . '.' . $table . '_id';
                }
                $join_table_field = $this->_get_model_primary_key($table);
                $this->db->join($table, $master_join_field . "=" . $join_table_field);
            } else {
                show_error($master_join_field . ' table:' . $table, 500, '处理 list_display 或 search_field 时发现未知的 JOIN 字段');
            }
        }

        // 搜索数据
        if ($handle_search) {
            foreach ($search_field as $field_name) {
                $this->db->or_like($field_name, $_s);
            }
        }

        $this->load->library('pagination');
        $counter_db = clone $this->db;
        $total_rows= $counter_db->count_all_results($this->model_name);
        $config = array(
// "base_url" => current_url(),
            "total_rows" => $total_rows,
            "per_page" => $this::LIST_PER_PAGE,
            "num_links" => 5,
            "page_query_string" => TRUE,
            "reuse_query_string" => TRUE,
            "full_tag_open" => '<ul class="pagination">',
            "full_tag_close" => "</ul>",
            "cur_tag_open" => '<li class="active"><a href="#">',
            "cur_tag_close" => "</a></li>",
            "num_tag_open" => "<li>",
            "num_tag_close" => "</li>",
            "next_link" => "&raquo;",
            "next_tag_open" => "<li>",
            "next_tag_close" => "</li>",
            "prev_link" => "&laquo;",
            "prev_tag_open" => "<li>",
            "prev_tag_close" => "</li>",
            "first_link" => "First",
            "first_tag_open" => "<li>",
            "first_tag_close" => "</li>",
            "last_link" => "Last",
            "last_tag_open" => "<li>",
            "last_tag_close" => "</li>",
        );
        $this->pagination->initialize($config);
        $per_page = intval($this->input->get("per_page"));

        // SELECT 增加表的主键字段
//        p($select_field);
        $select_field[] = $model_primary_key . ' AS ' . strtr($model_primary_key, '.', '_');
//        p($select_field);

        $this->db->select(join(",", $select_field))
                ->limit($this::LIST_PER_PAGE, $per_page);

        $query = $this->db->get($this->model_name);

        $data = array();
//        p($field_info);
        while ($row = $query->_fetch_assoc('array')) {
            foreach ($row as $field => $value) {
                if (!isset($field_info[$field]))
                    continue;
                $table = $field_info[$field]["table"];
                if ($table) {
                    $tmp_model_class = $table . "_model";
                    if (method_exists($this->$tmp_model_class->$field_info[$field]["field"], "format_value")) {
                        $row["#!" . $field . "!#"] = $this->$tmp_model_class->$field_info[$field]["field"]->format_value($value);
                    }
                }
            }
            foreach ($custom_field as $key => $field_func) {
                $row[$key] = $this->$field_func($row);
            }
            $data[] = $row;
        }
        
        $context = array(
            "data" => $data,
            "total_rows" => $total_rows,
            "pages" => $this->pagination->create_links(),
            "primary_key" => $model_primary_key,
            "primary_field" => $model_primary_field,
            "actions" => $this->actions,
            "field_info" => $field_info,
            "verbose_name" => $this->_verbose_name,
            "disable_add" => $this::DISABLE_ADD,
            "disable_del" => $this::DISABLE_DEL,
            "current_url" => current_url(),
            "list_display_links" => $this->list_display_links,
            "buttons" => $this->buttons,
            "model_class" => $model_class,
            "display_search_form" => $display_search_form,
            "_s" => $_s,
        );

        $this->load->view("admin/list.php", $context);
        unset($data, $counter_db);
    }

    public function action_view() {
        
    }

    private function _level_menu($key, $menu) {
        if (array_key_exists("url", $menu)) {
            if (is_string($key)) {
                $this->register_menu[$key][] = $menu;
            } else {
                $this->register_menu[] = $menu;
            }
            return;
        }

        foreach ($menu as $key2 => $value2) {
            if (is_string($value2)) {
                $name = is_string($key2) ? $key2 : $value2;
                $menu_info = array(
                    "url" => site_url("admin/" . $value2),
                    "text" => $name,
                    "target" => "",
                );
                if (is_string($key)) {
                    $this->register_menu[$key][] = $menu_info;
                } else {
                    $this->register_menu[] = $menu_info;
                }
            } else {
                $this->_level_menu($key, $value2);
            }
        }
    }

    /**
     * 校验注册的控制器或自定义路径
     */
    private function _valid_register_model($register) {
        foreach ($register as $key => $menu) {
            //单独注册
            $name = "";
            if (is_string($menu)) {
                $name = is_string($key) ? $key : $menu;
                $menu_info = array(
                    "url" => site_url("admin/" . $menu),
                    "text" => $name,
                    "target" => "",
                );
                $this->register_menu[] = $menu_info;
            } else {
                // 注册在某个菜单下
                $this->_level_menu($key, $menu);
            }
        }
    }

    /**
     * 验证用户权限
     */
    private function _valid_permissions() {
        
    }

    /**
     * 后台主页
     */
    public function home() {
        $this->load->view('admin/home.php');
    }

    public function index() {
        $this->load->helper("url");
        //检测是否登录
//        $this->_check_login();

        $control_name = $this->uri->segment(2);
        $method_name = $this->uri->segment(3);
        $admin_opt = $this->input->get("_admin_opt");
        $is_action_opt = $admin_opt == "action";
        $id_list = array();

        if ($control_name == "auth") {
            $this->AdminControlPath = __DIR__ . "/auth/controllers";
            $this->load->add_package_path("phpadmin/auth");
            $control_name = $method_name;
            if (!$control_name) {
                $control_name = "auth";
            }

            $method_name = $this->uri->segment(4);
            if (!$method_name) {
                $method_name = "index";
            }
        }

        /**
         * http://localhost/~zhangbo/phpadmin/index.php/admin 情况处理
         */
        if (!$control_name) {
            $control_name = "phpAdmin";
            $method_name = "home";
        }

        if (!$method_name) {
            $method_name = "list_view";
        }

        //检测是否拥有操着该页面的权限
        $this->_check_user_permissons();


        $this->_valid_register_model($this->register_model);

        $control_file = $this->AdminControlPath . "/" . $control_name . ".php";
        if (file_exists($control_file)) {
            include_once $control_file;
        } else {
            show_error("control name : " . $control_name . "<br /> find path : " . $control_file, 404, "Admin control not found");
        }

        $control_class_name = ucfirst($control_name);
        $instance = self::_get_instance($control_class_name);

//        $control->config->load('phpadmin');
        if (ENVIRONMENT == "development") {
            $this->output->enable_profiler(TRUE);
        }
        if ($is_action_opt) {
            $id_list = $this->input->post("id_list");
            $method_name = "_action_" . $this->input->get("action");
        }

//        $admin_uri_name = $control->config->item("admin_uri_name");
//        $control->control = $control;
        $instance->control_name = $control_name;
        $instance->method_name = $method_name;
        $instance->model_name = $control_name ? $control_name : $instance::MODEL;

        $instance->_control_name = $control_name;
        $instance->_method_name = $method_name;
        $instance->_model_name = $control_name ? $control_name : $instance::MODEL;
        $model_class = $instance->_model_name . '_model';
        $instance->_model_class = $model_class;
        $instance->_verbose_name = $instance::VERBOSE_NAME ? $instance::VERBOSE_NAME : $control_name;
        if ($control_name != 'phpAdmin' && $method_name != 'home') {
            $instance->_primary_key = $instance->_get_model_primary_key($instance->_model_name, FALSE);
            $instance->get_model_instance($instance->_model_name);
            $instance->_model_instance = $instance->$model_class;
        }

        if (method_exists($instance, $method_name)) {
            $context = array(
                "register_menu" => $this->register_menu,
            );
            $instance->load->vars($context);
            if ($is_action_opt) {
                call_user_func_array(array($instance, $method_name), array($id_list));
            } else {
                call_user_func_array(array($instance, $method_name), array_slice($this->uri->segment_array(), 3));
            }
        } else {
            show_404();
        }
    }

}
