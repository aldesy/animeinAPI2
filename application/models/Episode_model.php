<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Episode_model extends CI_Model {

    //WEB
    public function get_episodes($searchText = '', $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_episodes');
        if(!empty($searchText)) {
            $likeCriteria = "title  LIKE '%".$searchText."%'";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by("episodenumber", "desc");
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_episodes_count($searchText = '')
    {
        $this->db->select('*');
        $this->db->from('tbl_episodes');
        if(!empty($searchText)) {
            $likeCriteria = "title  LIKE '%".$searchText."%'";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    function add_new_episode($episodeinfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_episodes', $episodeinfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    //APIGET
    function api_get_all_episodes($animeid)
    {
        $this->db->select('*');
        $this->db->from('tbl_episodes');
        $this->db->where('anime', $animeid);
        $this->db->order_by("episodenumber", "desc");
        $query = $this->db->get();
        return $query->result();
    }
    
}



