<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {
    private $data = array();
    
    public function __construct()
    {
        parent::__construct();
        $this->data['mainmenu'] = $this->load->view('vMainMenu', '', TRUE);
    }
    
    public function index()
    {
        
        if($this->session->userdata('user') != ""){
            $this->data['orders'] = $this->amrta_model->select_query('vorder');
            $this->load->view('order/vOrderMain', $this->data);
            
        } else {
            $this->session->set_flashdata('error_message', 'Login untuk akses page ini');
            header('Location: '.site_url().'/user');
        }
    }
    
    
    public function select_customer()
    {    
        if($this->session->userdata('user') != ""){
            $$this->data['customers'] = $this->amrta_model->select_query('tblcustomer');
            $this->load->view('order/vOrderAdd', $this->data);
            
        } else {
            $this->session->set_flashdata('error_message', 'Login untuk akses page ini');
            header('Location: '.site_url().'/user');
        }
    }
    
    
    public function list_member()
    {   
        if($this->input->post('findvalue') == "") {
            $this->data['members'] = $this->amrta_model->select_query('tblcustomer');
            $this->load->view('member/vMember', $this->data);
        } else {
            $findby = $this->input->post('findby');
            $findvalue = $this->input->post('findvalue');
            
            $this->data['members'] = $this->amrta_model->find_member($findby, $findvalue);
            $this->load->view('member/vMember', $this->data);
        }
    }
    
    
    public function add_item()
    {
        $this->load->view('order/vOrderAdd', $this->data);
    }
    
    
    public function test_ajax()
    {
        $data['nama']   = "bimo";
        $data['istri']  = "mamang";
        echo json_encode($this->amrta_model->select_query('vorder'));
    }
}