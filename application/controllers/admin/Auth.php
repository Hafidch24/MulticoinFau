<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->data['page'] = 'Login';
        #Captcha
        $this->data['captcha_display'] = get_captcha($this->data['settings'], base_url(), 'login_captcha');
        $this->render('login', $this->data);
    }
    
    public function login()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[1]|max_length[100]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[1]|max_length[100]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', faucet_alert('danger', 'Login Failed'));
            return redirect(site_url('/admin'));
        }
        
        $username = $this->db->escape_str((string) $this->input->post('username'));
        $password = hash("sha256", $this->input->post('password'));
        $admin = md5($username . '-' . $password);
        
        if ($username != $this->data['settings']['username'] || $password != $this->data['settings']['password']) {
            $this->session->set_flashdata('message', faucet_alert('danger', 'Login Failed'));
            return redirect(site_url('/admin'));
        } else {
            $this->session->set_userdata('admin', $admin);
        }

        redirect(site_url('/admin/overview'));
    }
}
