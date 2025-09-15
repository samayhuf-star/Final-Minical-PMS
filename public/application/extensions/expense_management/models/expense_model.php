<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expense_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_expenses($company_id, $limit = null, $offset = null)
	{
		$this->db->select('e.*, u.first_name, u.last_name');
		$this->db->from('expenses e');
		$this->db->join('user u', 'u.user_id = e.created_by', 'left');
		$this->db->where('e.company_id', $company_id);
		$this->db->where('e.is_deleted', 0);
		$this->db->order_by('e.expense_date', 'DESC');
		$this->db->order_by('e.created_at', 'DESC');

		if ($limit) {
			$this->db->limit($limit, $offset);
		}

		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_expense($expense_id, $company_id)
	{
		$this->db->select('e.*, u.first_name, u.last_name');
		$this->db->from('expenses e');
		$this->db->join('user u', 'u.user_id = e.created_by', 'left');
		$this->db->where('e.expense_id', $expense_id);
		$this->db->where('e.company_id', $company_id);
		$this->db->where('e.is_deleted', 0);

		$query = $this->db->get();
		return $query->row_array();
	}

	public function create_expense($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert('expenses', $data);

		if ($this->db->_error_message()) {
			show_error($this->db->_error_message());
		}

		$query = $this->db->query('select LAST_INSERT_ID() AS last_id');
		$result = $query->result_array();
		return isset($result[0]) ? $result[0]['last_id'] : null;
	}

	public function update_expense($expense_id, $data, $company_id)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('expense_id', $expense_id);
		$this->db->where('company_id', $company_id);
		$this->db->update('expenses', $data);

		if ($this->db->_error_message()) {
			show_error($this->db->_error_message());
		}

		return $this->db->affected_rows() > 0;
	}

	public function delete_expense($expense_id, $company_id)
	{
		$this->db->where('expense_id', $expense_id);
		$this->db->where('company_id', $company_id);
		$this->db->update('expenses', array('is_deleted' => 1, 'updated_at' => date('Y-m-d H:i:s')));

		return $this->db->affected_rows() > 0;
	}

	public function get_expenses_by_category($company_id, $start_date = null, $end_date = null)
	{
		$this->db->select('expense_category, SUM(amount) as total_amount, COUNT(*) as count');
		$this->db->from('expenses');
		$this->db->where('company_id', $company_id);
		$this->db->where('is_deleted', 0);

		if ($start_date) {
			$this->db->where('expense_date >=', $start_date);
		}
		if ($end_date) {
			$this->db->where('expense_date <=', $end_date);
		}

		$this->db->group_by('expense_category');
		$this->db->order_by('total_amount', 'DESC');

		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_total_expenses($company_id, $start_date = null, $end_date = null)
	{
		$this->db->select('SUM(amount) as total');
		$this->db->from('expenses');
		$this->db->where('company_id', $company_id);
		$this->db->where('is_deleted', 0);

		if ($start_date) {
			$this->db->where('expense_date >=', $start_date);
		}
		if ($end_date) {
			$this->db->where('expense_date <=', $end_date);
		}

		$query = $this->db->get();
		$result = $query->row_array();
		return $result['total'] ? $result['total'] : 0;
	}

	public function get_expense_categories($company_id)
	{
		$this->db->select('DISTINCT expense_category');
		$this->db->from('expenses');
		$this->db->where('company_id', $company_id);
		$this->db->where('is_deleted', 0);
		$this->db->order_by('expense_category');

		$query = $this->db->get();
		$categories = array();
		foreach ($query->result_array() as $row) {
			$categories[] = $row['expense_category'];
		}
		return $categories;
	}
}
