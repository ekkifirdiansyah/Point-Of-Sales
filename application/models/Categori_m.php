<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categori_m extends CI_Model {

    public function get($id = null)
    {
        $this->db->from('categori');
        if($id != null) {
            $this->db->where('categori_id', $id);
        }
        $query = $this->db->get();
        return $query;
    }

    public function add($post)
    {
        $params = [
            'name' => $post['categori_name']
        ];
        $this->db->insert('categori', $params);
    }

    public function edit($post)
    {
        $params = [
            'name' => $post['categori_name'],
            'updated' => date('Y-m-d H:i:s')
        ];
        $this->db->where('categori_id', $post['id']);
        $this->db->update('categori', $params);
    }

    public function del($id)
    {
        $this->db->where('categori_id', $id);
        $this->db->delete('categori');
    }

}