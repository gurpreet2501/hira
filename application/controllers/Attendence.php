<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use App\Libs\ApiClient;
use App\Libs\AttendenceHandler;


class Attendence extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('tank_auth');
		// if(user_role() != 'ADMIN')
  //   	redirect('auth/logout');
	}

	function save()
	{
		if(empty($_GET))
			return 404;
	
		$obj =  new AttendenceHandler();
		$resp = $obj->processing($_GET);
		echo json_encode($resp,JSON_PRETTY_PRINT);
		
	}

	//FUNCTION for View Attebdance
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
