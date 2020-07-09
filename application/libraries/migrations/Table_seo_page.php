<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Table_seo_page {
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
		$schema = $this->CI->schema->create_table('seo_page');
        $schema->increments('seoId', ['type' => 'BIGINT', 'length' => '20']);
        $schema->integer('relatiedId', ['type' => 'BIGINT', 'length' => '30']);
        $schema->string('seoTypePage', ['length' => '25']);
        $schema->text('seoTitle');
        $schema->text('seoDesc');
        $schema->string('seoKeyword', ['length' => '200']);
        $schema->string('seoRobots', ['length' => '20']);
        $schema->run();

        // ADD index
        $schema->index('relatiedId');
        $schema->index('seoTypePage');
	}

	public function seeder(){
		
	}

}

