<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Table_unit_weight {
	/**
	 * !!! CAUTION !!!
	 * 
	 * Don't change the table name and class name because to important to seeder system
	 * 
	 * if you want to change the table name, copy your script code in this file
	 * remove this file with this bash 
	 * 
	 * php index.php Migration remove {table name}
	 * 
	 * then create new database with migration bash and paste you code before
	 */

	private $CI;

	public function __construct(){
		$this->CI =& get_instance();

        $this->CI->load->model('mc');
        $this->CI->load->library('Schema');
	}

	public function migrate(){
		$schema = $this->CI->schema->create_table('unit_weight');
        $schema->increments('weightId', ['length' => '11']);
        $schema->string('weightTitle', ['length' => '35']);
        $schema->string('weightUnit', ['length' => '5']);
        $schema->decimal('weightValue', ['length' => '15,8']);
        $schema->enum('weightDefault', ['y', 'n']);
        $schema->run();
	}

	public function seeder(){
		
	}

}

