<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods extends CI_Controller {
    
   


	public function category($cat = FALSE)
	{
        $data['mainmenu'] = $this->load->view('vMainMenu', '', TRUE);
        
        if($this->session->userdata('user') != ""){
        
            if($cat == FALSE) {
                $data['cats'] = $this->amrta_model->get_categories();
                $this->load->view('goods/vCategory', $data);    
            } else if($cat == "add_item") {
                $this->load->view('goods/vCategoryAdd', $data);
            } else {
                $data['cats'] = $this->amrta_model->get_categories($cat);
                $this->load->view('goods/vCategoryEdit', $data);
            }
        } else {
            $this->session->set_flashdata('error_message', "<a href='".site_url()."/user'>Login</a> untuk akses menu update harga");
            header('Location: '.site_url().'/cashier/trans');
        }
        
	}
    
    
    public function ajax_get_all_category()
    {
        $cats = $this->amrta_model->get_categories();
        print json_encode($cats);
    }
    
    
    public function ajax_save_category()
    {
        $json = file_get_contents('php://input');
        $ajax = json_decode($json, true);
        $data = array('cat_name' => $ajax['cat_name']);
        $wr = array('cat_id' => $ajax['cat_id']);
        $res = $this->amrta_model->update_query('tblcategory', $data, $wr);
        
        print json_encode($res);
    }
    
    
    public function add_category($tp = FALSE)
    {
        $data['mainmenu'] = $this->load->view('vMainMenu', '', TRUE);
        if($this->session->userdata('user') != ""){
            if($this->input->post('cat_name') != "") {
                $data = array(
                    'cat_name'  => $this->input->post('cat_name')
                );

                if($tp == "update") {
                    $wr = array(
                        'cat_id'    => $this->input->post('cat_id')
                    );
                    $result = $this->amrta_model->update_query('tblcategory', $data, $wr);
                } else {
                    $result = $this->amrta_model->insert_query('tblcategory', $data);
                }
            }

            header('Location: '.site_url().'/goods/category');
        } else {
            $this->session->set_flashdata('error_message', "<a href='".site_url()."/user'>Login</a> untuk akses menu update harga");
            header('Location: '.site_url().'/cashier/trans');
        }
    }   
    
    
    public function add_item($tp = FALSE)
    {
        $data['mainmenu'] = $this->load->view('vMainMenu', '', TRUE);
        if($this->session->userdata('user') != ""){
            if($this->input->post('item_name') != "")
            {
                $data = array(
                    'cat_id'        => $this->input->post('cat_id'),
                    'item_name'     => $this->input->post('item_name'),
                    'item_ori_price'=> $this->input->post('item_ori_price'),
                    'item_price'    => $this->input->post('item_price')
                );

                if($tp == "update") {
                    $wr = array(
                        'item_id'   => $this->input->post('item_id')
                    );
                    $result = $this->amrta_model->update_query('tblitem', $data, $wr);
                    header('Location: '.site_url().'/goods/item');
                } else {
                    $result = $this->amrta_model->insert_query('tblitem', $data);
                    $this->session->set_flashdata('item_id', $this->db->insert_id());
                    header('Location: '.site_url().'/goods/barcode');
                }
            } else {
                header('Location: '.site_url().'/goods/item');
            }

        } else {
            $this->session->set_flashdata('error_message', "<a href='".site_url()."/user'>Login</a> untuk akses menu update harga");
            header('Location: '.site_url().'/cashier/trans');
        }
    }   
    
    
    public function item($itm = FALSE)
    {
        $data['mainmenu'] = $this->load->view('vMainMenu', '', TRUE);
        if($this->session->userdata('user') != ""){
            if($itm == FALSE){

                if($this->input->post('item_name')){
                    $data['items'] = $this->amrta_model->find_item_byname($this->input->post('item_name'));
                    $this->load->view('goods/vItem', $data);
                } else {
                    $data['items'] = $this->amrta_model->get_items();
                    $this->load->view('goods/vItem', $data);
                }
            } else if($itm == "add_item"){
                $data['cats'] = $this->amrta_model->get_categories();
                $this->load->view('goods/vItemAdd', $data);
            } else {
                $data['item'] = $this->amrta_model->get_items($itm);
                $data['cats'] = $this->amrta_model->get_categories();
                $data['codes'] = $this->amrta_model->select_query('vbarcode', array('item_id' => $itm, 'SOLD' => 'N'));

                $this->session->set_flashdata('item_id', $itm);

                $this->load->view('goods/vItemEdit', $data);
            }
        } else {
            $this->session->set_flashdata('error_message', "<a href='".site_url()."/user'>Login</a> untuk akses menu update harga");
            header('Location: '.site_url().'/cashier/trans');
        }
    }
    
    
    public function barcode()
    {
        if($this->session->userdata('user') != ""){
            $data['mainmenu'] = $this->load->view('vMainMenu', '', TRUE);
            $itm = $this->session->flashdata('item_id');
            if($itm == "") {
                header('Location: '.site_url().'/goods/item');
            }
            $this->session->set_flashdata('item_id', $itm);

            $data['info'] = $this->amrta_model->select_query('vitem', array('item_id' => $itm), 'SINGLE');
            $data['codes'] = $this->amrta_model->select_query('vbarcode', array('item_id' => $itm));
            $this->load->view('goods/vBarcodeEdit', $data);
        } else {
            $this->session->set_flashdata('error_message', "<a href='".site_url()."/user'>Login</a> untuk akses menu update harga");
            header('Location: '.site_url().'/cashier/trans');
        }
    }
    
    
    public function add_barcode()
    {
        if($this->session->userdata('user') != ""){
            if($this->session->flashdata('item_id') != "" and $this->input->post('barcode_id') != ""){
                $itm = $this->session->flashdata('item_id');
                $this->session->set_flashdata('item_id', $itm);

                $data = array(
                    'barcode_id'    => $this->input->post('barcode_id'),
                    'item_id'       => $itm
                );


                //check row exist
                $result = $this->amrta_model->select_query('tblbarcode', array('barcode_id' => $this->input->post('barcode_id')));
                if(count($result) > 0){
                    $this->session->set_flashdata('error_message', "Barcode sudah terdaftar!");
                    $this->session->set_flashdata('success_message', "");
                } else {
                    $this->amrta_model->insert_query('tblbarcode', $data);
                    $this->session->set_flashdata('success_message', "Barcode berhasil ditambahkan!");
                    $this->session->set_flashdata('error_message', "");
                }

            } else {
                $this->session->set_flashdata('error_message', "Barcode harus diisi!");
            }

            header('Location: '.site_url().'/goods/barcode');
        } else {
            $this->session->set_flashdata('error_message', "<a href='".site_url()."/user'>Login</a> untuk akses menu update harga");
            header('Location: '.site_url().'/cashier/trans');
        }
    }
    
    
    public function del_barcode($bcode, $link = FALSE)
    {
        if($this->session->userdata('user') != ""){
            $result = $this->amrta_model->del_barcode($bcode);
            $this->session->set_flashdata('success_message', 'Barcode berhasil dihapus');

            $itm = $this->session->flashdata('item_id');
            $this->session->set_flashdata('item_id', $itm);

            $data['info'] = $this->amrta_model->select_query('vitem', array('item_id' => $itm), 'SINGLE');
            $data['codes'] = $this->amrta_model->select_query('vbarcode', array('item_id' => $itm));

            if(!$link){
                header('Location: '.site_url().'/goods/barcode');  
            } else if($link == "FIND") {
                $this->session->set_flashdata('success_message', 'Barcode berhasil dihapus');
                header('Location: '.site_url().'/goods/find_barcode');
            }
        } else {
            $this->session->set_flashdata('error_message', "<a href='".site_url()."/user'>Login</a> untuk akses menu update harga");
            header('Location: '.site_url().'/cashier/trans');
        }
        
    }
    
    
    function ajax_del_barcode()
    {
        if($this->session->userdata('user') != ""){
            
        } else {
            print "<a href='".site_url()."/user'>Login</a> untuk menghapus barcode";
        }
    }
    
    
    public function find_barcode()
    {
        $data['mainmenu'] = $this->load->view('vMainMenu', '', TRUE);
        $this->load->view('goods/vBarcodeFind', $data);
    }
    
    
    public function ajax_find_barcode()
    {
        $json = file_get_contents('php://input');
        $ajax = json_decode($json, true);
        
        $data = $this->amrta_model->select_query('vbarcode', $ajax);
        print json_encode($data);
    }
    
    
    public function del_category($cat)
    {
        if($this->session->userdata('user') != ""){
            if($cat != ""){
                //check whether this category has available item/barcode
                $result = $this->amrta_model->del_category($cat);
                if($result){
                    $this->session->set_flashdata('success_message', 'Kategori berhasil dihapus');
                    $this->session->set_flashdata('error_message', '');
                } else {
                    $this->session->set_flashdata('error_message', 'Kategori tidak berhasil dihapus, masih ada barang tersedia di kategori ini');
                    $this->session->set_flashdata('success_message', '');
                }
            } 

            header('Location: '.site_url().'/goods/category');
        } else {
            $this->session->set_flashdata('error_message', "<a href='".site_url()."/user'>Login</a> untuk akses menu update harga");
            header('Location: '.site_url().'/cashier/trans');
        }
    }
    
    
    public function del_item($itm)
    {
        if($this->session->userdata('user') != ""){
            if($itm != ""){
                //check whether this category has available barcode
                $result = $this->amrta_model->del_item($itm);
                if($result){
                    $this->session->set_flashdata('success_message', 'Barang berhasil dihapus');
                    $this->session->set_flashdata('error_message', '');
                } else {
                    $this->session->set_flashdata('error_message', 'Barang tidak berhasil dihapus, masih ada barang tersedia');
                    $this->session->set_flashdata('success_message', '');
                }
            }

            header('Location: '.site_url().'/goods/item');
        } else {
            $this->session->set_flashdata('error_message', "<a href='".site_url()."/user'>Login</a> untuk akses menu update harga");
            header('Location: '.site_url().'/cashier/trans');
        }
    }
    
    
    // testing angularjs
    public function angular()
    {
        $result = $this->amrta_model->select_query("tblItem");
        echo json_encode($result);
    }
}

