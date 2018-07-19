<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Anime_model extends CI_Model {

    public $title;
    public $sinopsis;
    public $status;
    public $image;
    public $imgbackground;
    public $view;
    
    public function get_animes($searchText = '', $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_animes');
        if(!empty($searchText)) {
            $likeCriteria = "title  LIKE '%".$searchText."%'";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by("status", "desc");
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_animes_count($searchText = '')
    {
        $this->db->select('*');
        $this->db->from('tbl_animes');
        if(!empty($searchText)) {
            $likeCriteria = "title  LIKE '%".$searchText."%'";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    function get_anime_info($animeid)
    {
        $this->db->select('*');
        $this->db->from('tbl_animes');
        $this->db->where('animeid', $animeid);
        $query = $this->db->get();
        
        return $query->result();
    }

    function edit_anime($animeinfo, $animeid)
    {
        $this->db->where('animeid', $animeid);
        $this->db->update('tbl_animes', $animeinfo);
        
        return TRUE;
    }

    function add_new_anime($animeinfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_animes', $animeinfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }



    //APIGET
    function api_get_animes()
    {
        $this->db->select('*');
        $this->db->from('tbl_animes');
        $this->db->order_by("status", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function api_get_animes_minim()
    {
        $this->db->select('animeid, title, sinopsis, image, view');
        $this->db->from('tbl_animes');
        $this->db->order_by("status", "desc");
        $query = $this->db->get();
        return $query->result();
    }
    
}



