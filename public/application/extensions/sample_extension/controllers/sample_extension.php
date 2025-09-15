<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sample_extension extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = array();
		$data['page_title'] = 'Sample Extension';
		$this->load->view('sample_extension/index', $data);
	}
}


