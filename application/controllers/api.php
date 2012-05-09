<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

/**
 * This class sets up the REST API
 * 
 * Considering the size of the project, methods for recipes, users 
 * and votes have all been put in here. These could be sent out into their
 * own unit test classes for a larger project.
 * 
 * @author galbus
 * 
 * @todo image upoad functionality would be nice
 * 
 * This could be achieved by using the same method as Amazon:
 * 
 * file_get_contents() BEFORE sending to us
 * file_put_contents() AFTER we receive it
 * 
 * Restrictions should be forced for file upload size
 * 
 */
class Api extends REST_Controller
{
	
	/**
	 * GET method to return a single user 
	 */
	public function user_get() {
		
		// 400 if we don't have an ID supplied
		if(!$this->get('id')) {
			$this->response(NULL, 400);
		}
		
		// load & query model
		$this->load->model('User_model');
		$user = $this->User_model->get_user($this->get('id'));
		 
		if ($user) {
			$this->response($user, 200); // 200 being the HTTP response code
		} else {
			$this->response(array('error' => 'User could not be found'), 404);
		}
		
	}
	
	/**
	 * POST method to update a user
	 * 
	 * @return void
	 */
	public function user_post() {
		// This is a stub - isn't required at this stage
		$this->response(null, 400);
	}
	
	/**
	 * DELETE method to remove a user
	 * 
	 * @return void
	 */
	public function user_delete() {
		// This is a stub - isn't required at this stage
		$this->response(null, 400);
	}
	
	/**
	 * GET method to return all users
	 * 
	 * @return void
	 */
	public function users_get() {
		
		// load & query model
		$this->load->model('User_model');
		$users = $this->User_model->get_users();
		
		if ($users) {
			$this->response($users, 200); // 200 being the HTTP response code
		} else {
			$this->response(array('error' => "Couldn't find any users"), 404);
		}
		
	}
	
	/**
	 * GET method to return a single recipe
	 * 
	 * @return void
	 */
	public function recipe_get() {
		
		// 400 if we don't have an ID supplied
		if (!$this->get('id')) {
			$this->response(NULL, 400);
		}
		
		// load & query model
		$this->load->model('Recipe_model');
		$recipe = $this->Recipe_model->get_recipe($this->get('id'));
		 
		if ($recipe) {
			$this->response($recipe, 200); // 200 being the HTTP response code
		} else {
			$this->response(array('error' => 'Recipe could not be found'), 404);
		}
		
	}
	
	/**
	 * POST method to update a recipe
	 */
	public function recipe_post() {
		
		// load & query model
		$this->load->model('Recipe_model');
		$result = $this->Recipe_model->update_entry($this->post('id'), array(  
            'name'         => $this->post('name'), // Accept HTML
			'description'  => $this->post('description'), // Accept HTML
			'ingredients'  => $this->post('ingredients'), // Accept HTML
			'method'       => $this->post('method'), // Accept HTML
			'cooking_time' => $this->post('cooking_time'), // HH:MM:SS
			'prep_time'    => $this->post('prep_time'), // HH:MM:SS
			'yield'        => $this->post('yield'), // free text so that we can write things like "Serves 4 as a main or 6 as a starter"
			'author'       => $this->post('author'), // ID
			'date_added'   => null, // defaults to CURRENT_TIMESTAMP
        ));  
  
        if ($result === false) {  
            $this->response(array('status' => 'failed'));  
        } else {  
            $this->response(array('status' => 'success'));  
        }
	}
	
	/**
	 * PUT method to add a recipe
	 */
	public function recipe_put() {
		
		// load & query model
 		$this->load->model('Recipe_model');
 		
 		/*
 		 * @todo validate user input here
 		 * 
 		 * I was looking at using the CI form_validation class here, though
 		 * I wanted to keep the controllers slim and perform validation in the
 		 * model. Seems this is an area of discussion in CI - I found a couple of
 		 * approaches/classes but this requires more investigation
 		 * 
 		 * Without using CI I would have done something akin to:
 		 * 
 		 * $name = $this->put('name');
 		 * if (strlen($name) > 0 && (strlen($name) < 50)) {
 		 * 	$valid['name'] = $name;
 		 * }
 		 * 
 		 * Then check the $valid array to determine if we should be inserting.
 		 * 
 		 * Though I'd expect this to be part of CI...
 		 * 
 		 */ 
 		
 		// added second parameter to $this->put for XSS filtering
 		$result = $this->Recipe_model->insert_entry($this->put(null, true));

 		if ($result === false) {
 			$message = array(
 				'status' => 'failed',
 	 		);
 			$code = 500;
 		} else {
 			// added second parameter to $this->put for XSS filtering
 			$message = array(
 				'status'       => 'success',
				// return useful data back to the user
 				// @todo return values should be from the model, not put(), as they could have changed on db insert
 				'name'         => $this->put('name', true),
 				'description'  => $this->put('description', true),
 				'ingredients'  => $this->put('ingredients', true),
 				'method'       => $this->put('method', true),
 				'cooking_time' => $this->put('cooking_time', true),
 				'prep_time'    => $this->put('prep_time', true),
 				'yield'        => $this->put('yield', true),
 				// @todo we should accept the username as well as the ID to make the API easier to use
 				'author'       => $this->put('author', true),
 			);
 			$code = 200;
 		}
 		$this->response($message, $code);
	}
	
	/**
	 * DELETE method to remove a recipe
	 * 
	 * Stub as not yet required
	 */
 	public function recipe_delete() {
 		$this->response(null, 400);
 	}

	/**
	 * GET method to return all recipes
	 */
	public function recipes_get() {
		
		$this->load->model('Recipe_model');
		$recipes = $this->Recipe_model->get_recipes();

		if ($recipes) {
			$this->response($recipes, 200); // 200 being the HTTP response code
		} else {
			$this->response(array('error' => "Couldn't find any recipes"), 404);
		}
		
	}
	
	/**
	 * GET method to return the winning recipe
	 */
	public function winner_get() {
		
		$this->load->model('Recipe_model');
		$result = $this->Recipe_model->get_winner();
		
		if ($result) {
			$this->response($result, 200); // 200 being the HTTP response code
		} else {
			$this->response(array('error' => "Couldn't find a winning recipe"), 404);
		}
	}

	/**
	 * PUT method to register a vote
	 */
	public function vote_put() {
		
		$this->load->model('Vote_model');
		// added second parameter to $this->put for XSS filtering
		$result = $this->Vote_model->insert_vote($this->put(null, true));
		
 		if ($result === false) {
  			$message = array('status' => 'failed');
  			$code = 500;
  		} else {
  			$message = array('status' => 'success');
  			$code = 200;
  		}
 		
  		$this->response($message, $code);
	}

}
