<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Stuart McAlpine
 */
class test extends CI_Controller {

	/**
	 * Constructor
	 * 
	 * @return void
	 */
	function __construct() {
		parent::__construct();
		$this->load->library('unit_test');
	}
	
	/**
	 * Run all tests by default
	 * 
	 * @return void
	 */
	function index() {
		$this->all();
	}

	/**
	 * Run all tests
	 * 
	 * @return void
	 */
	function all() {
		$this->get_valid_user();
		$this->get_invalid_user();
		$this->curl_add_recipe();
		$this->curl_add_vote();
	}

	/**
	 * Get a user from a valid ID.
	 * We'll use user ID == 1 for this test as the admin user
	 * should never be deleted from the system.
	 * 
	 * @return void
	 */
	function get_valid_user() {
		
		$this->load->model('User_model');

		$actual_result = $this->User_model->get_user(1);
		$expected_result = array(new stdClass());
		$expected_result[0]->id = '1';
		$expected_result[0]->username = 'admin';
		
		echo $this->unit->run(
			$actual_result, 
			$expected_result,
			'get_valid_user',
			'User_model <i>should return</i> array(1) { [0]=> object(stdClass)#18 (2) { ["id"]=> string(1) "1" ["username"]=> string(5) "admin" } }'
		);
		
	}
	
	/**
	 * Get a user from an invalid ID.
	 * 
	 * We pass the string "foo" instead of a numeric ID to the get_user() method.
	 * 
	 * @return void
	 */
	function get_invalid_user() {
		
		$this->load->model('User_model');
		
		$actual_result = $this->User_model->get_user('foo');

		$expected_result = array();
		
		echo $this->unit->run(
			$actual_result, 
			$expected_result,
			'get_invalid_user',
			'User_model <i>should return</i> array(0) {}'
		);
		
	}
	
	public function curl_add_recipe() {
		
// 		$url = sprintf('http://%s/api/recipe/id/1/format/json', $_SERVER['SERVER_NAME']);
		$url = sprintf('http://%s/api/recipe/format/json', $_SERVER['SERVER_NAME']);

		$this->load->library('curl');

		$this->curl->create($url);

		// @todo secure API using credentials or access key
// 		$username = 'admin';
// 		$password = '1234';
// 		$this->curl->http_login($username, $password);

		// PUT method is used to add new rows
		$this->curl->put(array(
			'name'         => 'Name',
			'description'  => 'Description',
			'ingredients'  => 'Ingredients',
			'method'       => 'Method',
			'cooking_time' => '00:30:00',
			'prep_time'    => '00:40:00',
			'yield'        => 'Yield',
			'author'       => 1,
		));
		
		$actual_result = $this->curl->execute();

// 		$actual_result = json_decode($response);
		
		$expected_result = '{"status":"success","name":"Name","description":"Description","ingredients":"Ingredients","method":"Method","cooking_time":"00:30:00","prep_time":"00:40:00","yield":"Yield","author":"1"}';

		echo $this->unit->run(
			$actual_result,
			$expected_result,
			'curl_add_recipe',
			'Response should be a JSON object reporting "status":"success" for an add into the recipe table'
		);
		
	}

	/**
	 * Add a vote using CURL 
	 * 
	 * @todo this test has recipe_id 1 and user_id 1 hardcoded
	 * So the first time that the test is run it will pass, the second time it
	 * will fail. A parameter for the method would be more appropriate.
	 * 
	 */
	public function curl_add_vote() {
		
		$url = sprintf('http://%s/api/vote/format/json', $_SERVER['SERVER_NAME']);

		$this->load->library('curl');

		$this->curl->create($url);

		// @todo secure API using credentials or access key
// 		$username = 'admin';
// 		$password = '1234';
// 		$this->curl->http_login($username, $password);

		// PUT method is used to add new rows
		$this->curl->put(array(
			'recipe' => 1,
			'user'   => 1,
		));
		
		$actual_result = $this->curl->execute();
		
// 		$actual_result = json_decode($response);
		
		$expected_result = '{"status":"success"}';

		echo $this->unit->run(
			$actual_result,
			$expected_result,
			'curl_add_recipe',
			'Response should be a JSON object reporting "status":"success" for an add into the vote table'
		);
		
	}

}
