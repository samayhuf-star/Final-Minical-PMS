<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_booking_kind extends CI_Migration {

	public function up()
	{
		// Add booking_kind column to bookings table
		$fields = array(
			'booking_kind' => array(
				'type' => 'VARCHAR',
				'constraint' => 10,
				'null' => TRUE,
				'default' => NULL,
			),
		);
		if ($this->db->field_exists('booking_kind', 'bookings') === FALSE) {
			$this->dbforge->add_column('bookings', $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('booking_kind', 'bookings')) {
			$this->dbforge->drop_column('bookings', 'booking_kind');
		}
	}
}


