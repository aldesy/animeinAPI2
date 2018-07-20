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
        $this->db->select('Anime.*, IFNULL(SUM(Episode.view),0) as view');
        $this->db->from('tbl_animes as Anime');
        $this->db->join('tbl_episodes as Episode', 'Anime.animeid = Episode.anime','left');
        if(!empty($searchText)) {
            $this->db->like('Anime.title', $searchText, 'both'); 
        }
        $this->db->group_by('Anime.animeid'); 
        $this->db->order_by("Anime.status", "desc");
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        return $query->result();
    }
    


    public function get_animes_normal()
    {
        $this->db->select('*');
        $this->db->from('tbl_animes');
        $this->db->order_by("title", "desc");
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
     //   $array = array('name' => $name, 'title' => $title, 'status' => $status);
        
        $this->db->select('Anime.animeid, Anime.title, Anime.status, Anime.sinopsis, Anime.image, IFNULL(SUM(Episode.view),0) as view, IFNULL(AVG(Rate.rate),0) as rating');
        $this->db->from('tbl_animes as Anime');
        $this->db->join('tbl_episodes as Episode', 'Anime.animeid = Episode.anime','left');
        $this->db->join('tbl_rates as Rate', 'Anime.animeid = Rate.animeid','left');
        $this->db->group_by('Anime.animeid'); 
        $this->db->order_by("Anime.status", "desc");
        $query = $this->db->get();
        return $query->result_array();

        // $query = $this->db->query("select * from tbl_animes");
        // return $query->result_array();
    }

    function api_get_anime_by_id($animeid)
    {

        $this->db->select('Anime.animeid, Anime.title, Anime.status, Anime.sinopsis, Anime.image, Anime.imgbackground, IFNULL(AVG(Rate.rate),0) as rating, IFNULL(COUNT(Rate.rate),0) as ratecount, IFNULL(SUM(Episode.view),0) as view');
        $this->db->from('tbl_animes as Anime');
        $this->db->join('tbl_episodes as Episode', 'Anime.animeid = Episode.anime','left');
        $this->db->join('tbl_rates as Rate', 'Anime.animeid = Rate.animeid','left');
        $this->db->where('Anime.animeid', $animeid);
        $this->db->group_by('Anime.animeid'); 
        $this->db->order_by("Anime.status", "desc");
        $query = $this->db->get();
        return $query->result();
    }

}



