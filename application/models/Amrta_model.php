<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Amrta_model extends CI_Model {

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    
    public function get_categories($cat = FALSE)
    {
        if($cat === FALSE) {
            $query = $this->db->get('vcategory');
            return $query->result_array();    
        }

        $query = $this->db->get_where('vcategory', array('cat_id' => $cat));
        return $query->row_array();
    }
    
    
    public function get_items($itm = FALSE)
    {
        if($itm === FALSE){
            $query = $this->db->get('vitem');
            return $query->result_array();
        }
        
        $query = $this->db->get_where('vitem', array('item_id' => $itm));
        return $query->row_array();
    }
    
    
    public function select_query($tbl, $data = FALSE, $type = FALSE)
    {
        if(!$data){
            $query = $this->db->get($tbl);    
        } else {
            $query = $this->db->get_where($tbl, $data);
        }
        
        
        if(!$type){
            return $query->result_array();    
        } else if ($type = 'SINGLE') {
            return $query->row_array();
        }
    }


    public function insert_query($tbl, $data)
    {
        return $this->db->insert($tbl, $data);
    }
    
    
    public function update_query($tbl, $data, $wr)
    {
        return $this->db->update($tbl, $data, $wr);
    }
    
    
    public function delete_query($tbl, $data)
    {
        return $this->db->delete($tbl, $data);
    }
    
    
    public function del_category($cat)
    {
        $result = FALSE;
        $query = $this->db->get_where('vitem', array('cat_id' => $cat));
        
        if($query->num_rows() == 0){
            $this->db->delete('tblcategory', array('cat_id' => $cat));
            $result = TRUE;       
        }
        
        return $result;
    }
    
    
    public function del_barcode($bcode)
    {
        $result = $this->db->delete('tblbarcode', array('barcode_id' => $bcode));
        return $result;
    }
    
    
    public function del_item($itm)
    {
        $result = FALSE;
        $query = $this->db->get_where('vbarcode', array('item_id' => $itm));
        
        if($query->num_rows() == 0){
            $this->db->delete('tblitem', array('item_id' => $itm));
            $result = TRUE;       
        }
        
        return $result;
    }
    
    
    public function find_item_byname($itm_name)
    {
        $this->db->like('item_name', $itm_name);
        $query = $this->db->get('vitem');
        return $query->result_array();
    }
    
    
    public function empty_table($tbl)
    {
        $this->db->empty_table($tbl);
    }
    
    
    public function find_member($findby, $findvalue)
    {
        $this->db->like($findby, $findvalue);
        $query = $this->db->get('tblcustomer');
        return $query->result_array();
    }
    

}