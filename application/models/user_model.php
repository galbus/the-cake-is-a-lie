<?php 

/**
 * @author galbus
 */
class User_model extends CI_Model {

	/**
	 * @var string
	 */
	public $username   = '';

	/**
	 * Simply calls the Model constructor
	 * 
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Returns a user from a supplied database ID
	 * 
	 * @return CI_DB_result
	 */
	public function get_user($id) {
		$sql = "SELECT * FROM user WHERE id = ? ";
		$query = $this->db->query($sql, array($id));
		return $query->result();
	}

	/**
	 * Returns all users
	 * 
	 * For the purposes of this project returning all ~50 users
	 * is acceptable without a limit and page. To scale this $limit 
	 * and $page parameters could be passed to the method...
	 * 
	 * Or even better than this, a $since_id param could be passed,
	 * as Twitter do. This allows for the pagination to work during
	 * heavy insert() periods.
	 * 
	 * @return CI_DB_result
	 */
	public function get_users() {
		$query = $this->db->get('user', $limit);
		return $query->result();
	}

	/**
	 * Adds a new user into the database
	 * 
	 * @todo we should have validation in our model here
	 * as mentioned I can't find a clean way to do this in CI yet
	 * and don't want to litter the code with custom PHP if this is possible in CI
	 * 
	 * Validation should be based on
	 * - required
	 * - max length
	 * 
	 * @return void
	 */
	public function insert_entry() {
		/**
		 * Stub for database update()
		 * 
		 * We'll assume that the users can easily be prepopulated, as they 
		 * are staff members. This also stops other users from 'guessing'
		 * how to add a new user from a URL and influencing votes
		 */
// 		$this->db->insert('user', $this);
	}

	/**
	 * Updates a user in the database
	 * 
	 * @return void
	 */
	public function update_entry() {
		/**
		 * Stub for database update()
		 * 
		 * We'll assume that user details will not be updated over the course
		 * of the competition - if they do then we can manually edit them in the db
		 */
// 		$this->db->update('user', $this, array('id' => $_POST['id']));
	}

}
