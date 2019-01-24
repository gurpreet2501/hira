<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use App\Libs\ApiClient;

class Employee extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		auth_force();
		$this->load->helper('url');
		$this->load->library('tank_auth');
	}


	public function _example_output($output = null)
	{
		$this->load->view('crud.php',(array)$output);
	}

	function od_templates(){
			$crud = new grocery_CRUD();
			$crud->set_table('od_templates');
			$crud->columns('title','body');
			$crud->set_theme('bootstrap');
			$crud->field_type('activated','hidden', 1);
			$crud->field_type('created_at','hidden', date('Y-m-d H:i:s'));
			$crud->field_type('updated_at','hidden', date('Y-m-d H:i:s'));
			$crud->where('activated', 1);
	    $output = $crud->render();
			$this->_example_output($output);

	}	

	function index()
	{
		// $data['dashboard_data'] = dashboard_data();
	  $data['css_files'] = [base_url('assets/css/app-style.css')];
	  $data['js_files'] = [
	  	base_url('assets/plugins/simplebar/js/simplebar.js'),
			base_url('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js'),
			base_url('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js'),
			base_url('assets/plugins/sparkline-charts/jquery.sparkline.min.js'),
			base_url('assets/plugins/Chart.js/Chart.min.js'),
			base_url('assets/plugins/notifications/js/lobibox.min.js'),
			base_url('assets/plugins/notifications/js/notifications.min.js'),
			base_url('assets/plugins/index.js?v=4') 
	  ];

		$this->load->view('employee-dashboard',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
