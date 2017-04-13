<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {


	public function index()
	{
        $data['mainmenu'] = $this->load->view('vMainMenu', '', TRUE);
        if($this->input->post('username') == "") {
            $this->load->view('user/vLogin', $data);    
        } else {
            $data = array(
                'username'  => $this->input->post('username'),
                'password'  => md5($this->input->post('password'))
            );
            $result = $this->amrta_model->select_query('tbluser', $data, 'SINGLE');
            
            if($result['username'] != "") {
                $this->session->set_flashdata('success_message', 'Anda sudah berhasil login');
                $this->session->set_userdata('user', $result['username']);
                header('Location: '.site_url().'/cashier/trans');
            } else {
                $this->session->set_flashdata('error_message', 'Username & password salah');
                header('Location: '.site_url().'/user');
            }
        }
        
        
	}

    
    public function logout()
	{
        $this->session->unset_userdata('user');
        $this->session->set_flashdata('success_message', 'Anda sudah berhasil logout');
        header('Location: '.site_url().'/user');
	}
    
    
}
