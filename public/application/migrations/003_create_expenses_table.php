<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_expenses_table extends CI_Migration {

	public function up()
	{
		// Create expenses table
		$this->dbforge->add_field(array(
			'expense_id' => array(
				'type' => 'BIGINT',
				'constraint' => 20,
				'unsigned' => TRUE,
				'null' => FALSE,
				'auto_increment' => TRUE
			),
			'company_id' => array(
				'type' => 'BIGINT',
				'constraint' => 20,
				'unsigned' => TRUE,
				'null' => FALSE
			),
			'expense_category' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => FALSE
			),
			'expense_description' => array(
				'type' => 'TEXT',
				'null' => TRUE
			),
			'amount' => array(
				'type' => 'DECIMAL',
				'constraint' => '10,2',
				'null' => FALSE
			),
			'currency' => array(
				'type' => 'VARCHAR',
				'constraint' => 3,
				'null' => FALSE,
				'default' => 'USD'
			),
			'expense_date' => array(
				'type' => 'DATE',
				'null' => FALSE
			),
			'payment_method' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'null' => TRUE
			),
			'receipt_number' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => TRUE
			),
			'vendor_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 200,
				'null' => TRUE
			),
			'created_by' => array(
				'type' => 'BIGINT',
				'constraint' => 20,
				'null' => FALSE
			),
			'created_at' => array(
				'type' => 'DATETIME',
				'null' => FALSE
			),
			'updated_at' => array(
				'type' => 'DATETIME',
				'null' => TRUE
			),
			'is_deleted' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'null' => FALSE,
				'default' => '0'
			)
		));
		
		$this->dbforge->add_key("expense_id", true);
		$this->dbforge->create_table("expenses", TRUE);
		$this->db->query('ALTER TABLE `expenses` ENGINE = InnoDB');
		
		// Add foreign key constraints
		$this->db->query('ALTER TABLE `expenses` ADD CONSTRAINT `fk_expenses_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE CASCADE');
		$this->db->query('ALTER TABLE `expenses` ADD CONSTRAINT `fk_expenses_user` FOREIGN KEY (`created_by`) REFERENCES `user` (`user_id`) ON DELETE RESTRICT');
	}

	public function down()
	{
		$this->dbforge->drop_table('expenses');
	}
}
