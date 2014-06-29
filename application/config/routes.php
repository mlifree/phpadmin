<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "welcome";
$route['404_override'] = '';
$route["admin/*(.*)"] = "admin/phpAdmin";


/* End of file routes.php */
/* Location: ./application/config/routes.php */