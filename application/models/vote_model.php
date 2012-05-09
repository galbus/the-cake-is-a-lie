<?php 

/**
 * @author galbus
 */
class Vote_model extends CI_Model {

	/**
	 * @var int
	 */
	public $recipe_id = null;
	
	/**
	 * @var int
	 */
	public $user_id = null;
	
	/**
	 * Simply calls the Model constructor
	 * 
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Adds a new vote into the database
	 * 
	 * @return void
	 */
	public function insert_vote($data) {
		$this->recipe_id  = $data['recipe'];
		$this->user_id    = $data['user'];
		$this->date_voted = null; // CURRENT_TIMESTAMP is in place
		return $this->db->insert('vote', $this);
	}

}