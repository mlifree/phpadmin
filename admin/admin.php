<?php 
	include "model.php";
	class AppAdmin extends Admin{
		public $list_display = array("app_name");
		public $list_display_links;
		public $list_editable;
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
		public $inlines;
		public $list_filter;
		public $list_max_show_all;
		public $list_per_page;
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
	}
 ?>