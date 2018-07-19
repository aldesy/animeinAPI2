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
        $this->load->model('episode_model');
     //   $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function GetAllAnimes()
    {
        $data = $this->anime_model->api_get_animes();
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

    public function GetAllAnimesMinim()
    {
        $data = $this->anime_model->api_get_animes_minim();
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

    public function GetAnimeByID($animeid = NULL)
    {
        $animes = $this->anime_model->get_anime_info($animeid);
        foreach($animes as $anime)
        {
            $anime->episodes = $this->episode_model->api_get_all_episodes($animeid);;
        }
        $response = array(
            'success' => true,
            'data' => $animes);
      
        $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($response, JSON_PRETTY_PRINT))
        ->_display();
        exit;
    }
    

    //EPISODE
    public function GetAllEpisodes($animeid)
    {
        $data = $this->episode_model->api_get_all_episodes($animeid);
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