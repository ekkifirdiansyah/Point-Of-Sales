<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Item_m extends CI_Model {

    // start datatables
    var $column_order = array(null, 'barcode', 'item.name', 'categori_name', 'unit_name', 'price', 'stock', 'image'); //set column field database for datatable orderable
    var $column_search = array('barcode', 'item.name', 'price'); //set column field database for datatable searchable
    var $order = array('item_id' => 'asc'); // default order 
 
    private function _get_datatables_query() {
        $this->db->select('item.*, categori.name as categori_name, unit.name as unit_name');
        $this->db->from('item');
        $this->db->join('categori', 'item.categori_id = categori.categori_id');
        $this->db->join('unit', 'item.unit_id = unit.unit_id');
        $i = 0;
        foreach ($this->column_search as $item) { // loop column 
            if(@$_POST['search']['value']) { // if datatable send POST for search
                if($i===0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }  else if(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables() {
        $this->_get_datatables_query();
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all() {
        $this->db->from('item');
        return $this->db->count_all_results();
    }
    // end datatables

    public function get($id = null)
    {
        $this->db->select('item.*, categori.name as categori_name, unit.name as unit_name');
        $this->db->from('item');
        $this->db->join('categori', 'categori.categori_id = item.categori_id');
        $this->db->join('unit', 'unit.unit_id = item.unit_id');
        if($id != null) {
            $this->db->where('item_id', $id);
        }
        $this->db->order_by('barcode', 'asc');
        $query = $this->db->get();
        return $query;
    }

    public function add($post)
    {
        $params = [
            'barcode' => $post['barcode'],
            'name' => $post['product_name'],
            'categori_id' => $post['categori'],
            'unit_id' => $post['unit'],
            'price' => $post['price'],
            'image' => $post['image'],
        ];
        $this->db->insert('item', $params);
    }

    public function edit($post)
    {
        $params = [
            'barcode' => $post['barcode'],
            'name' => $post['product_name'],
            'categori_id' => $post['categori'],
            'unit_id' => $post['unit'],
            'price' => $post['price'],
            'updated' => date('Y-m-d H:i:s')
        ];
        if($post['image'] != null) {
            $params['image'] = $post['image'];
        }
        $this->db->where('item_id', $post['id']);
        $this->db->update('item', $params);
    }

    function check_barcode($code, $id = null) {
        $this->db->from('item');
        $this->db->where('barcode', $code);
        if($id != null) {
            $this->db->where('item_id !=', $id);
        }
        $query = $this->db->get();
        return $query;
    }

    public function del($id)
    {
        $this->db->where('item_id', $id);
        $this->db->delete('item');
    }

}