<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class API extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('anime_model');
     //   $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function GetAllAnimes()
    {

        $response = array(
            'success' => true,
            'data' => $this->anime_model->api_get_animes());
      
        $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($response, JSON_PRETTY_PRINT))
        ->_display();
        exit;
    }

    public function GetAllAnimesMinim()
    {

        $response = array(
            'success' => true,
            'data' => $this->anime_model->api_get_animes_minim());
      
        $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($response, JSON_PRETTY_PRINT))
        ->_display();
        exit;
    }

    public function GetAnimeByID($animeid = NULL)
    {
        $data = $this->anime_model->get_anime_info($animeid);
        $response = array(
            'success' => true,
            'data' => $data);
      
        $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($response, JSON_PRETTY_PRINT))
        ->_display();
        exit;
    }
    
    
}

?>