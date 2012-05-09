<?php

/**
 * Recipe
 * 
 * @todo indexes need to be added to the recipes table and any other relevant tables
 * In general, we should add an an index on any column that is used in a WHERE or ON clause 
 * 
 * @author galbus
 */
class Recipe_model extends CI_Model {

	/**
	 * @var string
	 */
	public $name = '';
	
	/**
	 * @var string
	 */
	public $description = '';
	
	/**
	 * @var string
	 */
	public $ingredients = '';
	
	/**
	 * @var string
	 */
	public $method = '';

	/**
	 * @var string
	 */
	public $cooking_time = '';
	
	/**
	 * @var string
	 */
	public $prep_time = '';
	
	/**
	 * @var string
	 */
	public $yield = '';
	
	/**
	 * @var string
	 */
	public $author = '';
	
	/**
	 * @var string
	 */
	public $date_added = null;
	
	/**
	 * Simply calls the Model constructor
	 * 
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Returns a recipe from a required database ID
	 * 
	 * @return CI_DB_result
	 */
	public function get_recipe($id) {
		// using parameterised query to prevent mysql injection
		$sql = "SELECT * FROM recipe WHERE id = ? ";
		$query = $this->db->query($sql, array($id));
		return $query->result();
	}

	/**
	 * Returns the winning recipe.
	 * Winning formula is determined by the following rules:
	 * 
	 * - The recipe with the most votes wins
	 * - A user can vote for their own recipe
	 * - Each user can vote for as many recipes as they like
	 * - Additional votes for the same recipe from the same user will be rejected
	 *   - The database structure would not allow this as the unique Primary Key 
	 *     covers 2 columns
	 *   - ERROR 1062: Duplicate entry 'X-X' for key 'PRIMARY' would be encountered
	 * - If two recipes have the same amount of votes, the recipe that was added earlier wins
	 * 
	 * @return CI_DB_result
	 */
	public function get_winner() {
		$sql = "SELECT    r.*,
		                  u.username,
		                  COUNT(r.id) as votes
		        FROM      recipe AS r
		        LEFT JOIN vote AS v
		        ON        r.id = v.recipe_id
		        LEFT JOIN user AS u
		        ON        r.author = u.id
		        GROUP BY  r.id
		        ORDER BY  votes DESC,
		                  r.date_added ASC
		        LIMIT     1";
		$query = $this->db->query($sql);
		return $query->result();
	}

	/**
	 * Returns all recipes
	 * 
	 * For the purposes of this project returning recipes for ~50 users
	 * is acceptable without a limit and page. To scale this $limit 
	 * and $page parameters could be passed to the method...
	 * 
	 * Or even better than this, a $since_id param could be passed,
	 * as Twitter do. This allows for the pagination to work during
	 * heavy insert() periods.
	 * 
	 * @return CI_DB_result
	 */
	public function get_recipes() {
		$query = $this->db->get('recipe');
		return $query->result();
	}

	/**
	 * Adds a new recipe into the database
	 * 
	 * @todo we should have validation in our model here
	 * as mentioned I can't find a clean way to do this in CI yet
	 * and don't want to litter the code with custom PHP if this is possible in CI
	 * 
	 * Validation should be based on
	 * - required
	 * - max length
	 * 
	 * We could also be extra safe and add a condition to check that the user ID is
	 * valid by checking the user table
	 * 
	 * @return void
	 */
	public function insert_entry($data) {
		
		$this->name         = $data['name']; // Accept HTML
		$this->description  = $data['description']; // Accept HTML
		$this->ingredients  = $data['ingredients']; // Accept HTML
		$this->method       = $data['method']; // Accept HTML
		$this->cooking_time = $data['cooking_time']; // HH:MM:SS
		$this->prep_time    = $data['prep_time']; // HH:MM:SS
		$this->yield        = $data['yield']; // free text so that we can write things like "Serves 4 as a main or 6 as a starter"
		$this->author       = $data['author']; // ID
		$this->date_added   = null; // CURRENT_TIMESTAMP is in place
		
		return $this->db->insert('recipe', $this);
		
	}

	/**
	 * Updates a recipe in the database
	 * 
	 * @return void
	 */
	public function update_entry($id, $data) {
		/**
		 * Stub for recipe update()
		 */
//   	$this->db->update('recipe', $data, array('id' => $id));
	}

}
