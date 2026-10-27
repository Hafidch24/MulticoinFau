<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Currencies extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function index()
    {
    if (!isset($_SESSION["admin"])) {
        return redirect(site_url('admin'));
    }
        $this->data['page'] = 'Currency';
        $this->data['currencies'] = $this->m_admin->getCurrency();
        $this->render('currency', $this->data);
    }
    public function add()
    {
        $currencyName = $this->db->escape_str($this->input->post('currency_name'));
        $code = $this->db->escape_str($this->input->post('code'));
        $api = $this->db->escape_str($this->input->post('api'));
        $apiUrl = 'https://api.coingecko.com/api/v3/simple/price?ids=' . urlencode($currencyName) . '&vs_currencies=usd';
        $result = @json_decode(get_data($apiUrl), TRUE);
        if (!$result) {
        $this->session->set_flashdata('sweet_message', game_sweet_alert('error', 'Currency name not valid!'));
        redirect(site_url('admin/currencies'));
        }
        foreach ($result as $priceS) {
            $price = $priceS['usd'];
        }
        $wallet = $this->db->escape_str($this->input->post('wallet'));
        $reward = $this->db->escape_str($this->input->post('reward'));
        $timer = $this->db->escape_str($this->input->post('timer'));
        $limit = $this->db->escape_str($this->input->post('limit'));
        $this->m_admin->addCurrency($code, $currencyName, $code, $api, $wallet, $reward, $timer, $limit, $price);
        redirect(site_url('admin/currencies'));
    }
    public function delete($id = 0)
    {
        $this->db->delete('currencies', array('id' => $id));
        redirect(site_url('admin/currencies'));
    }
    public function update($id = 0)
    {
        $currencyName = $this->db->escape_str($this->input->post('currency_name'));
        $code = $this->db->escape_str($this->input->post('code'));
        $api = $this->db->escape_str($this->input->post('api'));
        $wallet = $this->db->escape_str($this->input->post('wallet'));
        $reward = $this->db->escape_str($this->input->post('reward'));
        $timer = $this->db->escape_str($this->input->post('timer'));
        $limit = $this->db->escape_str($this->input->post('limit'));
        $this->m_admin->updateCurrency($id, $code, $currencyName, $code, $api, $wallet, $reward, $timer, $limit);
        redirect(site_url('admin/currencies'));
    }
}
