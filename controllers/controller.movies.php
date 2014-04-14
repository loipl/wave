<?php

/**
 * Web Service Tutorial <http://www.waveframework.com>
 * Tutorial Movie Controller
 *
 * It is recommended to extend View classes from WWW_Factory in order to 
 * provide various useful functions and API access for the view.
 *
 * @package    Factory
 * @author     Kristo Vaher <kristo@waher.net>
 * @copyright  Copyright (c) 2012, Kristo Vaher
 * @license    Unrestricted
 * @tutorial   /doc/pages/tutorial_webservice.htm
 * @since      1.0.0
 * @version    1.0.0
 */

class WWW_controller_movies extends WWW_Factory {

	/**
	 * Data to be returned is stored in this variable
	 */
	public $returnData=array();
	
	/**
	 * Adding movie data to database
	 *
	 * @param array $input input data array
	 * @input [title] movie title
	 * @input [year] movie year
	 * @return array
	 * @output [success] if adding was a success
	 * @output [error] if error was encountered
	 */
	public function add($input){
		if(!isset($input['title']) || $input['title']==''){
			$this->returnData['error']='Title is missing';
		} else if(!isset($input['year']) || $input['year']==''){
			$this->returnData['error']='Year is missing';
		} else {
			// This loads the model object from the class we created
			$movie=$this->getModel('movie');
			$movie->setTitle($input['title']);
			$movie->setYear($input['year']);
			if($movie->saveMovie()){
				$this->returnData['success']='Movie saved!';
			} else {
				$this->returnData['error']='Could not save movie!';
			}
		}
		return $this->returnData;
	}
        
        /**
	 * Simple example call to edit rows to database
	 *
	 * @param array $input input data sent to controller
	 * @input [key] This key is one of the accepted input values
	 * @return array
	 * @output [key] This is an output value that might exist in the output array
	 * @response [500] Data returned
	 */
	public function edit($input) {

        // ID has to be set when editing
        if (isset($input['id'])) {

            // Data is only edited if no errors were encountered
            // Getting model
            $data = $this->getModel('movie');

            // Data loading has to work based on the provided ID
            if ($data->loadMovie($input['id'])) {
                // Setting the changed input parameters
                // Validating input
                if (isset($input['title']) && trim($input['title']) != '') {
                    $data->title = trim($input['title']);
                }
                if (isset($input['year']) && trim($input['year']) != '') {
                    $data->year = trim($input['year']);
                }

                // Attempting to save
                if ($data->saveMovie()) {
                    $this->returnData['success'] = 'Movie saved!';
                } else {
                    $this->returnData['error'] = 'Failed to edit entry';
                }
            } else {
                $this->returnData['error'] = 'Entry ID not found';
            }
        } else {
            $this->returnData['error'] = 'Entry ID is missing';
        }
        return $this->returnData;
    }
	
	/**
	 * This returns data about a movie based on ID
	 *
	 * @param array $input input data array
	 * @input [id] movie ID
	 * @return array
	 * @output [title] movie title
	 * @output [year] movie year
	 * @output [id] movie ID
	 * @output [error] if ID was incorrect or movie was not found
	 */
	public function get($input){
		if(!isset($input['id']) || $input['id']=='' || $input['id']==0){
			$this->returnData['error']='ID is incorrect!';
		} else {
			$movie=$this->getModel('movie');
			$movie=$movie->loadMovie($input['id']);
			if($movie){
				$this->returnData=$movie;
			} else {
				$this->returnData['error']='Cannot find movie with this ID!';
			}
		}
		return $this->returnData;
	}
	
	/**
	 * This loads all listed movies from database
	 *
	 * @return array
	 * @output [title] movie title
	 * @output [year] movie year
	 * @output [id] movie ID
	 * @output [error] if ID was incorrect or movies were not found
	 */
	public function all(){
		$movies=$this->getModel('movie');
		$movies=$movies->loadAllMovies();
		if($movies){
			$this->returnData=$movies;
		} else {
			$this->returnData['error']='Cannot find movies!';
		}
		return $this->returnData;
	}
	
	/**
	 * This deletes a movie from database
	 *
	 * @param array $input input data array
	 * @input [id] movie ID
	 * @return array
	 * @output [success] if movie was deleted
	 * @output [error] if ID was incorrect or movie was not found
	 */
	public function delete($input){
		if(!isset($input['id']) || $input['id']=='' || $input['id']==0){
			$this->returnData['error']='ID is incorrect!';
		} else {
			$movie=$this->getModel('movie');
			$movie=$movie->deleteMovie($input['id']);
			if($movie){
				$this->returnData['success']='Movie deleted!';
			} else {
				$this->returnData['error']='Cannot find movie with this ID!';
			}
		}
		return $this->returnData;
	}

}
	
?>