<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expense_management extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('expense_model');
		$this->load->library('form_validation');
		$this->load->helper('url');
	}

	public function index()
	{
		$company_id = $this->session->userdata('company_id');
		$expenses = $this->expense_model->get_expenses($company_id);
		$total_expenses = $this->expense_model->get_total_expenses($company_id);
		$expenses_by_category = $this->expense_model->get_expenses_by_category($company_id);

		$data = array(
			'page_title' => 'Expense Management',
			'expenses' => $expenses,
			'total_expenses' => $total_expenses,
			'expenses_by_category' => $expenses_by_category
		);

		$this->load->view('expense_management/index', $data);
	}

	public function add()
	{
		if ($this->input->post()) {
			$this->_set_expense_validation_rules();

			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'page_title' => 'Add Expense',
					'errors' => validation_errors()
				);
				$this->load->view('expense_management/add', $data);
			} else {
				$expense_data = array(
					'company_id' => $this->session->userdata('company_id'),
					'expense_category' => $this->input->post('expense_category'),
					'expense_description' => $this->input->post('expense_description'),
					'amount' => $this->input->post('amount'),
					'currency' => $this->input->post('currency'),
					'expense_date' => $this->input->post('expense_date'),
					'payment_method' => $this->input->post('payment_method'),
					'receipt_number' => $this->input->post('receipt_number'),
					'vendor_name' => $this->input->post('vendor_name'),
					'created_by' => $this->session->userdata('user_id')
				);

				$expense_id = $this->expense_model->create_expense($expense_data);

				if ($expense_id) {
					$this->session->set_flashdata('success', 'Expense added successfully');
					redirect('extensions/expense_management');
				} else {
					$data = array(
						'page_title' => 'Add Expense',
						'errors' => 'Failed to add expense'
					);
					$this->load->view('expense_management/add', $data);
				}
			}
		} else {
			$data = array('page_title' => 'Add Expense');
			$this->load->view('expense_management/add', $data);
		}
	}

	public function edit($expense_id)
	{
		$company_id = $this->session->userdata('company_id');
		$expense = $this->expense_model->get_expense($expense_id, $company_id);

		if (!$expense) {
			show_404();
			return;
		}

		if ($this->input->post()) {
			$this->_set_expense_validation_rules();

			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'page_title' => 'Edit Expense',
					'expense' => $expense,
					'errors' => validation_errors()
				);
				$this->load->view('expense_management/edit', $data);
			} else {
				$expense_data = array(
					'expense_category' => $this->input->post('expense_category'),
					'expense_description' => $this->input->post('expense_description'),
					'amount' => $this->input->post('amount'),
					'currency' => $this->input->post('currency'),
					'expense_date' => $this->input->post('expense_date'),
					'payment_method' => $this->input->post('payment_method'),
					'receipt_number' => $this->input->post('receipt_number'),
					'vendor_name' => $this->input->post('vendor_name')
				);

				$success = $this->expense_model->update_expense($expense_id, $expense_data, $company_id);

				if ($success) {
					$this->session->set_flashdata('success', 'Expense updated successfully');
					redirect('extensions/expense_management');
				} else {
					$data = array(
						'page_title' => 'Edit Expense',
						'expense' => $expense,
						'errors' => 'Failed to update expense'
					);
					$this->load->view('expense_management/edit', $data);
				}
			}
		} else {
			$data = array(
				'page_title' => 'Edit Expense',
				'expense' => $expense
			);
			$this->load->view('expense_management/edit', $data);
		}
	}

	public function view($expense_id)
	{
		$company_id = $this->session->userdata('company_id');
		$expense = $this->expense_model->get_expense($expense_id, $company_id);

		if (!$expense) {
			show_404();
			return;
		}

		$data = array(
			'page_title' => 'View Expense',
			'expense' => $expense
		);

		$this->load->view('expense_management/view', $data);
	}

	public function delete($expense_id)
	{
		$company_id = $this->session->userdata('company_id');
		$success = $this->expense_model->delete_expense($expense_id, $company_id);

		if ($success) {
			$this->session->set_flashdata('success', 'Expense deleted successfully');
		} else {
			$this->session->set_flashdata('error', 'Failed to delete expense');
		}

		redirect('extensions/expense_management');
	}

	private function _set_expense_validation_rules()
	{
		$this->form_validation->set_rules('expense_category', 'Expense Category', 'required|trim');
		$this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('currency', 'Currency', 'required|trim');
		$this->form_validation->set_rules('expense_date', 'Expense Date', 'required');
		$this->form_validation->set_rules('expense_description', 'Description', 'trim');
		$this->form_validation->set_rules('payment_method', 'Payment Method', 'trim');
		$this->form_validation->set_rules('receipt_number', 'Receipt Number', 'trim');
		$this->form_validation->set_rules('vendor_name', 'Vendor Name', 'trim');
	}
}
