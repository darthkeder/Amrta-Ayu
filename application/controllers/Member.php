<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller {
    
    
    public function list_member()
    {
        $data['mainmenu'] = $this->load->view('vMainMenu', '', TRUE);
        $this->load->view('member/vMember', $data);
    }
    
    
    public function ajax_get_member()
    {
        $data = $this->amrta_model->select_query('tblcustomer');
        
        print json_encode($data);
    }
    
    
    public function ajax_select_member()
    {
        $json = file_get_contents('php://input');
        $ajax = json_decode($json, true);
        
        $data = $this->amrta_model->find_member($ajax['findby'], $ajax['findvalue']);
        print json_encode($data);
    }
    
    
    public function ajax_delete_member()
    {
        $json = file_get_contents('php://input');
        $ajax = json_decode($json, true);
        
        $this->amrta_model->delete_query('tblcustomer', array('customer_phone' => $ajax['customer_phone']));
        $data = $this->amrta_model->select_query('tblcustomer');
        
        print json_encode($data);
    }
    
    
    public function ajax_add_member()
    {
        $json = file_get_contents('php://input');
        $ajax = json_decode($json, true);
        
        
        $data = array(
                'customer_phone'    => $ajax['customer_phone'],
                'customer_name'     => $ajax['customer_name'],
                'DOB'               => $ajax['DOB']
            );
        
        $this->amrta_model->insert_query('tblcustomer', $ajax);
        $data = $this->amrta_model->select_query('tblcustomer');
        
        print json_encode($data);
    }
    
    public function add_member()
    {
        $data['mainmenu'] = $this->load->view('vMainMenu', '', TRUE);
        if($this->input->post('customer_phone') != ''){
            $data = array(
                'customer_phone'    => $this->input->post('customer_phone'),
                'customer_name'     => $this->input->post('customer_name'),
                'DOB'               => $this->input->post('DOB')
            );
            $this->amrta_model->insert_query('tblcustomer', $data);
            
            $this->session->set_flashdata('success_message', 'Data member berhasil ditambahkan');
            header('Location: '.site_url().'/cashier');
            
        } else {
            $this->load->view('member/vMemberAdd', $data);    
        }
        
    }
    
    
    public function add_member_order()
    {
        if($this->input->post('customer_phone') != ''){
            $data = array(
                'customer_phone'    => $this->input->post('customer_phone'),
                'customer_name'     => $this->input->post('customer_name'),
                'DOB'               => $this->input->post('DOB')
            );
            $this->amrta_model->insert_query('tblcustomer', $data);
            
            $this->session->set_flashdata('success_message', 'Data member berhasil ditambahkan');
            header('Location: '.site_url().'/cashier/order');
            
        } else {
            $this->load->view('member/vMemberOrderAdd');    
        }
        
    }
    
    
    public function find_member()
    {
        $member = $this->amrta_model->select_query('tblcustomer', array('customer_phone' => $this->input->post('customer_phone')), 'SINGLE');
        $cookie = array(
            'name'      => 'member_name',
            'value'     => $member['customer_name'],
            'expire'    => 300
        );
        $this->input->set_cookie($cookie);
        $cookie = array(
            'name'      => 'member_hp',
            'value'     => $member['customer_phone'],
            'expire'    => 300
        );
        $this->input->set_cookie($cookie);
        
        header('Location: '.site_url().'/cashier');
    }
    
    
    public function find_member_order()
    {
        $member = $this->amrta_model->select_query('tblcustomer', array('customer_phone' => $this->input->post('customer_phone')), 'SINGLE');
        $cookie = array(
            'name'      => 'member_name',
            'value'     => $member['customer_name'],
            'expire'    => 300
        );
        $this->input->set_cookie($cookie);
        $cookie = array(
            'name'      => 'member_hp',
            'value'     => $member['customer_phone'],
            'expire'    => 300
        );
        $this->input->set_cookie($cookie);
        
        header('Location: '.site_url().'/cashier/order  ');
    }
    
    
    
}