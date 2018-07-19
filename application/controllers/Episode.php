<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Episode extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('anime_model');
        $this->load->model('episode_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
       // $this->global['pageTitle'] = 'CodeInsect : Dashboard';
        
       // $this->loadViews("dashboard", $this->global, NULL , NULL);
       if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {        
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->episode_model->get_episodes_count($searchText);

			$returns = $this->paginationCompress ( "episodes/", $count, 15 );
            
            $data['episodeRecords'] = $this->episode_model->get_episodes($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'CodeInsect : Anime Listing';
            
            $this->loadViews("episode/episodes", $this->global, $data, NULL);
        }
    }
    
    

    /**
     * This function is used to load the add new form
     */
    function new()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('episode_model');
            
            $this->global['pageTitle'] = 'CodeInsect : Add New Episode';
            
            $data['animeRecords'] = $this->anime_model->get_animes_normal();
            
            $this->loadViews("episode/addNewEpisode", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function submit()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('title','Title','trim|required|max_length[128]');
            $this->form_validation->set_rules('episodenumber','Episode Number','trim|required|max_length[128]');
            $this->form_validation->set_rules('anime','Anime','required');
            $this->form_validation->set_rules('thumbnail','Thumbnail','trim|required|max_length[128]');

            if($this->form_validation->run() == FALSE)
            {
                $this->new();
            }
            else
            {
                $title = $this->security->xss_clean($this->input->post('title'));
                $episodenumber = $this->security->xss_clean($this->input->post('episodenumber'));
                $anime = $this->security->xss_clean($this->input->post('anime'));
                $thumbnail = $this->security->xss_clean($this->input->post('thumbnail'));

                $episodeinfo = array(
                    'title'=>$title,
                    'episodenumber'=>$episodenumber,
                    'anime'=>$anime,
                    'thumbnail'=>$thumbnail
                );
                
                $this->load->model('episode_model');
                $result = $this->episode_model->add_new_episode($episodeinfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Episode created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Episode creation failed');
                }
                redirect('episode');
                
            }
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editAnime($animeid = NULL)
    {
        if($this->isAdmin() == TRUE || $animeid == 1)
        {
            $this->loadThis();
        }
        else
        {
            if($animeid == null)
            {
                redirect('animes');
            }
            
            $data['animeInfo'] = $this->anime_model->get_anime_info($animeid);
            
            $this->global['pageTitle'] = 'CodeInsect : Edit Anime';
            
            $this->loadViews("editAnime", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editAnim()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $animeid = $this->input->post('animeid');
            
            $this->form_validation->set_rules('title','Title','trim|required|max_length[128]');
            $this->form_validation->set_rules('sinopsis','Sinopsis','required|max_length[200]');
            $this->form_validation->set_rules('image','Image','required|max_length[200]');
            $this->form_validation->set_rules('imgbackground','Image Background','required|max_length[200]');
            $this->form_validation->set_rules('view','View','required|min_length[1]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editAnime($animeid);
            }
            else
            {
                $title = ucwords(strtolower($this->security->xss_clean($this->input->post('title'))));
                $view = $this->security->xss_clean($this->input->post('view'));
                $sinopsis = $this->security->xss_clean($this->input->post('sinopsis'));
                $status = $this->input->post('status');
                $image = $this->security->xss_clean($this->input->post('image'));
                $imgbackground = $this->security->xss_clean($this->input->post('imgbackground'));
                $statusnum = 0;
                if($status == NULL)
                {
                    $statusnum = 0;
                }
                else
                {
                    $statusnum = 1;
                }    
                $animeinfo = array();
                
                $animeinfo = array('title'=>$title, 'sinopsis'=>$sinopsis, 'status'=>$statusnum, 'image'=>$image, 'imgbackground'=>$imgbackground, 'view'=>$view);
                
                $result = $this->anime_model->edit_anime($animeinfo, $animeid);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Anime updated successfully');
                    redirect('animes');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Anime updation failed');
                }
                
                
                
            }
        }
    }


    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteEpisode()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $episodeId = $this->input->post('episodeId');
            
            $result = $this->episode_model->delete_episode($episodeId);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }
    
    /**
     * This function is used to load the change password screen
     */
    function loadChangePass()
    {
        $this->global['pageTitle'] = 'CodeInsect : Change Password';
        
        $this->loadViews("changePassword", $this->global, NULL, NULL);
    }
    
    
    /**
     * This function is used to change the password of the user
     */
    function changePassword()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('oldPassword','Old password','required|max_length[20]');
        $this->form_validation->set_rules('newPassword','New password','required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword','Confirm new password','required|matches[newPassword]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->loadChangePass();
        }
        else
        {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');
            
            $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);
            
            if(empty($resultPas))
            {
                $this->session->set_flashdata('nomatch', 'Your old password not correct');
                redirect('loadChangePass');
            }
            else
            {
                $usersData = array('password'=>getHashedPassword($newPassword), 'updatedBy'=>$this->vendorId,
                                'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->user_model->changePassword($this->vendorId, $usersData);
                
                if($result > 0) { $this->session->set_flashdata('success', 'Password updation successful'); }
                else { $this->session->set_flashdata('error', 'Password updation failed'); }
                
                redirect('loadChangePass');
            }
        }
    }

    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }

    /**
     * This function used to show login history
     * @param number $userId : This is user id
     */
    function loginHistoy($userId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $userId = ($userId == NULL ? $this->session->userdata("userId") : $userId);

            $searchText = $this->input->post('searchText');
            $fromDate = $this->input->post('fromDate');
            $toDate = $this->input->post('toDate');

            $data["userInfo"] = $this->user_model->getUserInfoById($userId);

            $data['searchText'] = $searchText;
            $data['fromDate'] = $fromDate;
            $data['toDate'] = $toDate;
            
            $this->load->library('pagination');
            
            $count = $this->user_model->loginHistoryCount($userId, $searchText, $fromDate, $toDate);

            $returns = $this->paginationCompress ( "login-history/".$userId."/", $count, 5, 3);

            $data['userRecords'] = $this->user_model->loginHistory($userId, $searchText, $fromDate, $toDate, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'CodeInsect : User Login History';
            
            $this->loadViews("loginHistory", $this->global, $data, NULL);
        }        
    }
}

?>