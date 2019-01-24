<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use App\Libs\RestPaginator;

use Illuminate\Pagination\Paginator;

class HR extends CI_Controller { 

	public function __construct(){
		parent::__construct();
    $this->load->library('grocery_CRUD');
   
    if(user_role() != 'HR')
    	redirect('auth/logout');
	}



	public function _example_output($output = null)
	{
		$this->load->view('crud.php',(array)$output);
	}


 	public function index($lang=false){
 	
  		$this->load->view('dashboard');

  }


		public function add_user()
	{
			
			$crud = new grocery_CRUD();
			$crud->columns('username','email','phone');
			$crud->add_fields('username','email','phone','role','password','activated');
			$crud->edit_fields('username','email','phone','role','password');
			$crud->set_theme('bootstrap');
			$crud->where('role','EMPLOYEE');
			$crud->field_type('activated','hidden', 1);
			$crud->field_type('role','hidden', 'EMPLOYEE');
			$crud->callback_field('password', array($this,'edit_password_callback'));
			$crud->callback_before_update(array($this,'on_update_encrypt_password_callback'));
      $crud->callback_before_insert(array($this,'on_update_encrypt_password_callback'));
			$crud->set_table('tank_auth_users');
			$crud->callback_before_delete(array($this,'check_if_cmr_agency_exists'));
	    $output = $crud->render();
			$this->_example_output($output);

	} 

	function od_templates(){
			$crud = new grocery_CRUD();
			$crud->set_table('od_templates');
			$crud->columns('title','body');
			$crud->set_theme('bootstrap');
		
			if($crud->getState() == 'add')
				$crud->field_type('activated','hidden', 1);
			else
				 $crud->field_type('active', 'dropdown', array(1 => 'Yes', 0 => 'No'));

			$crud->field_type('created_at','hidden', date('Y-m-d H:i:s'));
			$crud->field_type('updated_at','hidden', date('Y-m-d H:i:s'));
			$crud->where('activated', 1);
	    $output = $crud->render();
			$this->_example_output($output);

	}	

	function on_update_encrypt_password_callback($post_array){

		if($post_array['password'] != '__DEFAULT_PASSWORD_'){
      $password=$post_array['password'];
			$hasher = new PasswordHash(
	    		$this->config->item('phpass_hash_strength', 'tank_auth'),
		    	$this->config->item('phpass_hash_portable', 'tank_auth')
			);

			$post_array['password'] = $hasher->HashPassword($password);
			$post_array['activated'] = 1;
			return $post_array;
		}

		unset($post_array['password']);
		return $post_array;
	}


  function edit_password_callback($post_array){
		return '<input type="password" class="form-control" value="__DEFAULT_PASSWORD_" name="password" style="width:462px">';
	}





}



