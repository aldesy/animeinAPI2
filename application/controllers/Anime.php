<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Anime extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('anime_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the user list
     */
    function animeListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {        
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->anime_model->get_animes_count($searchText);

			$returns = $this->paginationCompress ( "animes/", $count, 10 );
            
            $data['animeRecords'] = $this->anime_model->get_animes($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'CodeInsect : Anime Listing';
            
            $this->loadViews("animes", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('anime_model');
            
            $this->global['pageTitle'] = 'CodeInsect : Add New Anime';
            
            $this->loadViews("anime/addNewAnime", $this->global, NULL, NULL);
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
    function submitNewAnime()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('title','Title','trim|required|max_length[128]');
            $this->form_validation->set_rules('view','Views','trim|required|max_length[128]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNew();
            }
            else
            {
                $title = ucwords(strtolower($this->security->xss_clean($this->input->post('title'))));
                $view = $this->security->xss_clean($this->input->post('view'));
                
                $animeinfo = array('title'=>$title, 'view'=>$view);
                
                $this->load->model('anime_model');
                $result = $this->anime_model->add_new_anime($animeinfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Anime created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Anime creation failed');
                }
                
                redirect('addNewAnime');
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
    function deleteUser()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $userId = $this->input->post('userId');
            $userInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->user_model->deleteUser($userId, $userInfo);
            
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