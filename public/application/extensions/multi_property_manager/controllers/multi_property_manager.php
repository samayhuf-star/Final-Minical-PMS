<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Multi_property_manager extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Company_model');
	}

	public function index()
	{
		$user_id = $this->session->userdata('user_id');
		$companies = $this->Company_model->get_my_companies($user_id);
		$data = array('companies' => $companies);
		$this->load->view('multi_property_manager/index', $data);
	}

	public function switch_property($company_id)
	{
		if (!$company_id) {
			show_404();
		}
		$this->session->set_userdata('current_company_id', $company_id);
		redirect('/');
	}
}


