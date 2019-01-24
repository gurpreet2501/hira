<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use App\Libs\ApiClient;

class CustomerDashboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		auth_force();
		$this->load->helper('url');
		$this->load->library('tank_auth');
		// if(user_role() != 'ADMIN')
    //   	redirect('auth/logout');
	}

	function index()
	{
		$this->load->view('customer-dashboard');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
