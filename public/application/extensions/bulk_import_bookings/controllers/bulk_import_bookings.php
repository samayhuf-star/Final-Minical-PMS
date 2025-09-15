<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bulk_import_bookings extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Booking_model');
		$this->load->model('Customer_model');
		$this->load->model('Room_model');
		$this->load->model('Company_model');
		$this->load->library('upload');
		$this->load->helper('file');
		$this->load->helper('csv');
	}

	public function index()
	{
		$data = array();
		$data['page_title'] = 'Bulk Import Bookings';
		$this->load->view('bulk_import_bookings/index', $data);
	}

	public function upload()
	{
		if ($this->input->post()) {
			$config['upload_path'] = './uploads/temp/';
			$config['allowed_types'] = 'csv|xlsx|xls';
			$config['max_size'] = 10240; // 10MB
			$config['file_name'] = 'bulk_import_' . time();

			if (!is_dir($config['upload_path'])) {
				mkdir($config['upload_path'], 0755, true);
			}

			$this->upload->initialize($config);

			if ($this->upload->do_upload('file')) {
				$upload_data = $this->upload->data();
				$file_path = $upload_data['full_path'];
				
				// Parse the file and get headers
				$headers = $this->_parse_file_headers($file_path);
				
				// Store file info in session
				$this->session->set_userdata('bulk_import_file', array(
					'file_path' => $file_path,
					'file_name' => $upload_data['file_name'],
					'headers' => $headers
				));

				$data = array(
					'status' => 'success',
					'message' => 'File uploaded successfully',
					'headers' => $headers,
					'file_name' => $upload_data['file_name']
				);
			} else {
				$data = array(
					'status' => 'error',
					'message' => $this->upload->display_errors()
				);
			}

			echo json_encode($data);
		} else {
			show_404();
		}
	}

	public function mapping()
	{
		$file_data = $this->session->userdata('bulk_import_file');
		
		if (!$file_data) {
			redirect('extensions/bulk_import_bookings');
			return;
		}

		// Define available booking fields for mapping
		$booking_fields = array(
			'customer_name' => 'Customer Name',
			'email' => 'Customer Email',
			'phone' => 'Customer Phone',
			'check_in_date' => 'Check-in Date',
			'check_out_date' => 'Check-out Date',
			'room_type' => 'Room Type',
			'adult_count' => 'Adults',
			'children_count' => 'Children',
			'rate' => 'Rate',
			'booking_kind' => 'Booking Type',
			'booking_notes' => 'Booking Notes',
			'address' => 'Customer Address',
			'city' => 'Customer City',
			'country' => 'Customer Country'
		);

		$data = array(
			'page_title' => 'Field Mapping',
			'headers' => $file_data['headers'],
			'booking_fields' => $booking_fields
		);

		$this->load->view('bulk_import_bookings/mapping', $data);
	}

	public function preview()
	{
		if ($this->input->post()) {
			$mapping = $this->input->post('mapping');
			$file_data = $this->session->userdata('bulk_import_file');
			
			if (!$file_data) {
				echo json_encode(array('status' => 'error', 'message' => 'No file data found'));
				return;
			}

			// Parse file with mapping
			$preview_data = $this->_parse_file_with_mapping($file_data['file_path'], $mapping);
			
			// Store mapping in session
			$this->session->set_userdata('bulk_import_mapping', $mapping);

			echo json_encode(array(
				'status' => 'success',
				'preview_data' => $preview_data,
				'total_rows' => count($preview_data)
			));
		} else {
			show_404();
		}
	}

	public function import()
	{
		if ($this->input->post()) {
			$file_data = $this->session->userdata('bulk_import_file');
			$mapping = $this->session->userdata('bulk_import_mapping');
			
			if (!$file_data || !$mapping) {
				echo json_encode(array('status' => 'error', 'message' => 'Missing file or mapping data'));
				return;
			}

			// Parse and import data
			$import_result = $this->_import_bookings($file_data['file_path'], $mapping);
			
			// Clean up session data
			$this->session->unset_userdata('bulk_import_file');
			$this->session->unset_userdata('bulk_import_mapping');
			
			// Clean up temp file
			if (file_exists($file_data['file_path'])) {
				unlink($file_data['file_path']);
			}

			echo json_encode($import_result);
		} else {
			show_404();
		}
	}

	private function _parse_file_headers($file_path)
	{
		$extension = pathinfo($file_path, PATHINFO_EXTENSION);
		$headers = array();

		if ($extension === 'csv') {
			$handle = fopen($file_path, 'r');
			if ($handle !== false) {
				$headers = fgetcsv($handle);
				fclose($handle);
			}
		} elseif (in_array($extension, array('xlsx', 'xls'))) {
			// For Excel files, we'll use a simple approach
			// In a real implementation, you'd use a library like PhpSpreadsheet
			$headers = array('Column A', 'Column B', 'Column C', 'Column D', 'Column E');
		}

		return $headers;
	}

	private function _parse_file_with_mapping($file_path, $mapping)
	{
		$extension = pathinfo($file_path, PATHINFO_EXTENSION);
		$data = array();

		if ($extension === 'csv') {
			$handle = fopen($file_path, 'r');
			if ($handle !== false) {
				$headers = fgetcsv($handle);
				$row_count = 0;
				
				while (($row = fgetcsv($handle)) !== false && $row_count < 10) { // Preview first 10 rows
					$mapped_row = array();
					foreach ($mapping as $field => $column_index) {
						if ($column_index !== '' && isset($row[$column_index])) {
							$mapped_row[$field] = $row[$column_index];
						} else {
							$mapped_row[$field] = '';
						}
					}
					$data[] = $mapped_row;
					$row_count++;
				}
				fclose($handle);
			}
		}

		return $data;
	}

	private function _import_bookings($file_path, $mapping)
	{
		$extension = pathinfo($file_path, PATHINFO_EXTENSION);
		$imported_count = 0;
		$error_count = 0;
		$errors = array();

		if ($extension === 'csv') {
			$handle = fopen($file_path, 'r');
			if ($handle !== false) {
				$headers = fgetcsv($handle); // Skip header row
				
				while (($row = fgetcsv($handle)) !== false) {
					$booking_data = array();
					$customer_data = array();
					
					// Map data according to mapping
					foreach ($mapping as $field => $column_index) {
						if ($column_index !== '' && isset($row[$column_index])) {
							$value = trim($row[$column_index]);
							
							// Categorize fields
							if (in_array($field, array('customer_name', 'email', 'phone', 'address', 'city', 'country'))) {
								$customer_data[$field] = $value;
							} else {
								$booking_data[$field] = $value;
							}
						}
					}

					// Validate required fields
					if (empty($booking_data['check_in_date']) || empty($booking_data['check_out_date'])) {
						$error_count++;
						$errors[] = "Row " . ($imported_count + $error_count) . ": Missing check-in or check-out date";
						continue;
					}

					try {
						// Create customer if needed
						$customer_id = null;
						if (!empty($customer_data['customer_name'])) {
							$customer_data['company_id'] = $this->session->userdata('company_id');
							$customer_id = $this->Customer_model->create_customer($customer_data);
						}

						// Create booking
						$booking_data['booking_customer_id'] = $customer_id;
						$booking_data['company_id'] = $this->session->userdata('company_id');
						$booking_data['booked_by'] = $this->session->userdata('user_id');
						$booking_data['state'] = 0; // Default state
						$booking_data['balance'] = isset($booking_data['rate']) ? $booking_data['rate'] : 0;
						$booking_data['balance_without_forecast'] = $booking_data['balance'];

						$booking_id = $this->Booking_model->create_booking($booking_data);

						if ($booking_id) {
							$imported_count++;
						} else {
							$error_count++;
							$errors[] = "Row " . ($imported_count + $error_count) . ": Failed to create booking";
						}
					} catch (Exception $e) {
						$error_count++;
						$errors[] = "Row " . ($imported_count + $error_count) . ": " . $e->getMessage();
					}
				}
				fclose($handle);
			}
		}

		return array(
			'status' => 'success',
			'imported_count' => $imported_count,
			'error_count' => $error_count,
			'errors' => $errors
		);
	}
}
