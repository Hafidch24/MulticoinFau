<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function index()
    {
        $skip = (isset($_GET['per_page']) && is_numeric($_GET['per_page'])) ? $_GET['per_page'] : 0;

        $this->load->library('pagination');
        $this->load->config('pagination');
        $config = $this->config->item('pagination_config');

        $this->data['page'] = 'Users';

        $this->data['users'] = $this->m_admin->get_users($skip);
        $config['base_url'] = site_url('admin/users/');
        $config['total_rows'] = $this->m_admin->count_all_users();

        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        $this->render('users', $this->data);
    }
    public function find()
    {
        $this->data['page'] = 'Users';
        $user = $this->db->escape_str($this->input->post('user'));
        $type = $this->input->post('type');
        if ($this->input->get('ip') != NULL) {
            $type = 'ip';
            $user = $this->input->get('ip');
        }

        switch ($type) {
            case 'id':
                $this->data['users'] = $this->m_admin->getUserFromId($user);
                break;
            case 'email':
                $this->data['users'] = $this->m_admin->getUserFromEmail($user);
                break;
            case 'ip':
                $this->data['users'] = $this->m_admin->getUserFromIp($user);
                break;
            default:
                $this->data['users'] = $this->m_admin->getUserFromId($user);
        };
        $this->data['pagination'] = '';
        $this->render('users', $this->data);
    }

    public function same_ip()
    {
        $skip = (isset($_GET['per_page']) && is_numeric($_GET['per_page'])) ? $_GET['per_page'] : 0;
        $this->load->library('pagination');
        $this->load->config('pagination');
        $config = $this->config->item('pagination_config');

        $this->data['page'] = 'Users have same IP Address';
        $this->data['fraudContent'] = 'IP Address';

        $this->data['users'] = [];
        $ipAddresses = $this->m_admin->getSameIpAddress($skip);
        foreach ($ipAddresses as $ipAddress) {
            $this->data['users'][$ipAddress['ip_address']] = $this->m_admin->getUsersByIpAddress($ipAddress['ip_address']);
        }
        $config['base_url'] = site_url('admin/users/same_ip');
        $config['total_rows'] = $this->m_admin->countSameIpAddress();

        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        $this->render('frauds', $this->data);
    }

    public function ban($id)
    {
        $id = $this->db->escape_str($id);
        $reason = $this->input->post('reason');
        if ($reason == NULL) {
            $reason = 'Banned by admin!';
        }
        $this->m_core->insertCheatLog($id, $reason, 0, 'Not available');
        $this->session->set_flashdata('message', faucet_alert('success', 'Banned'));
        redirect(site_url('admin/users/banned'));
    }
    public function unban($id)
    {
        $id = $this->db->escape_str($id);
        $this->db->delete('cheat_logs', array('user_id' => $id));
        $this->session->set_flashdata('message', faucet_alert('success', 'Unbanned'));
        redirect(site_url('admin/users/'));
    }

    public function banned()
    {
        $skip = (isset($_GET['per_page']) && is_numeric($_GET['per_page'])) ? $_GET['per_page'] : 0;

        $this->load->library('pagination');
        $this->load->config('pagination');
        $config = $this->config->item('pagination_config');
        $config['base_url'] = site_url('admin/users/banned');
        $config['total_rows'] = $this->m_admin->count_banned_users();
        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['page'] = 'Banned Users';
        $this->data['users'] = $this->m_admin->get_banned_users($skip);
        $this->render('users', $this->data);
    }

}
