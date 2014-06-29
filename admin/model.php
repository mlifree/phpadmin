<?php
	include "../phpadmin/ModelField.class.php";
	class App{
		public function __construct(){
			$this->app_name = new CharField(array(
								"default" => "app_name",
								"help_text" => "请输入软件名称，如：手机QQ"
							));
			$this->package_id = new CharField();
			$this->package_size = new IntField();
		}

		public function __toString(){
			return strval($this->app_name);
		}
	}
 ?>