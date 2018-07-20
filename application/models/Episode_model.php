<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Episode_model extends CI_Model {

    //WEB
    public function get_episodes($searchText = '', $page, $segment)
    {
        $this->db->select('Episode.*, Anime.title as animetitle');
        $this->db->from('tbl_episodes as Episode');
        $this->db->join('tbl_animes as Anime', 'Episode.anime = Anime.animeid','left');
        if(!empty($searchText)) {
            $likeCriteria = "Anime.title  LIKE '%".$searchText."%'";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by("animetitle asc, episodenumber asc");
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

    function edit_episode($episodeinfo, $episodeid)
    {
        $this->db->where('episodeid', $episodeid);
        $this->db->update('tbl_episodes', $episodeinfo);
        
        return TRUE;
    }

    function get_episode_info($episodeid)
    {
        $this->db->select('*');
        $this->db->from('tbl_episodes');
        $this->db->where('episodeid', $episodeid);
        $query = $this->db->get();
        
        return $query->result();
    }

    function add_new_episode($episodeinfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_episodes', $episodeinfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function delete_episode($episodeId)
    {
        $this->db->where('episodeid', $episodeId);
        $this->db->delete('tbl_episodes'); 
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



