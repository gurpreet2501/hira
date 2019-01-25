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

	function od_forms(){
			
			$crud = new grocery_CRUD();
			
			$crud->set_table('od_forms');
			$crud->set_theme('datatables');

			if($crud->getState() == 'edit')
				$crud->edit_fields('template_id','concerned_person_id','od_date');
			else if($crud->getState() == 'add')
				$crud->add_fields('template_id','concerned_person_id','od_date');
			else if($crud->getState() == 'list'){
				$crud->columns('template_id','od_form_approval_status','od_date','approved_by');
				$crud->set_relation('approved_by','tank_auth_users', 'name');
			}else if($crud->getState() == 'success'){
					$crud->columns('template_id','od_form_approval_status','od_date','approved_by');
					$crud->set_relation('approved_by','tank_auth_users', 'name');
			}

			$crud->unset_read();
			$crud->unset_delete();
			$crud->set_relation('template_id','od_templates', 'title');
			$crud->display_as('template_id','Leave Type');
			$crud->display_as('od_form_approval_status','OD Approval');
			$crud->display_as('concerned_person_id','Concerned Person');
			$crud->callback_column('od_form_approval_status', array($this, 'statusDisplayFlags'));
			
			$crud->set_relation('concerned_person_id','od_concerned_person', 'od_person_name');
			
			$crud->field_type('od_form_approval_status','hidden', 0);

			$crud->field_type('od_date','hidden', date('Y-m-d'));
			
			$crud->field_type('created_at','hidden', date('Y-m-d H:i:s'));
			$crud->field_type('updated_at','hidden', date('Y-m-d H:i:s'));
			$crud->field_type('user_id','hidden', user_id());
	    $output = $crud->render();

			$this->_example_output($output);
	}	


	public function bindSuccess($val){
			return '<span class="label label-success">'.$val.'</val>';
	}
	public function bindFailure($val){
		return '<span class="label label-danger">'.$val.'</val>';
	}

	public function statusDisplayFlags($value, $row)
	{	
			if($value)
				return $this->bindSuccess('Approved');
			
			return $this->bindFailure('Pending');
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
