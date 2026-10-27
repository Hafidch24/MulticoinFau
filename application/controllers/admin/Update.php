<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Update extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index($from)
    {
        if (!isset($_SESSION["admin"])) {
            return redirect(site_url('admin'));
        }
        
        foreach (array_keys($this->m_core->getSettings()) as $key) {
            $newValue = $this->input->post($key);
            if ($key == 'password' && !empty($newValue)) {
                $newValue = hash("sha256", $this->input->post($key));
            }
            if (isset($_POST[$key]) && $_POST[$key] != $this->data['settings'][$key]) {
                $this->db->set('value', $newValue);
                $this->db->where('name', $key);
                $this->db->update('settings');
            }
        }

        // --- بداية كود تحديث الجلسة لمنع تسجيل الخروج ---
        // جلب الاسم الحالي (سواء تم تغييره أو بقي كما هو)
        $current_username = $this->input->post('username') ? $this->input->post('username') : $this->data['settings']['username'];
        
        // جلب كلمة المرور الحالية (سواء تم تغييرها أو بقي الهاش القديم)
        $current_password_hash = $this->input->post('password') ? hash("sha256", $this->input->post('password')) : $this->data['settings']['password'];
        
        // إنشاء البصمة الجديدة وحفظها في الجلسة
        $new_admin_session = md5($current_username . '-' . $current_password_hash);
        $this->session->set_userdata('admin', $new_admin_session);
        // --- نهاية كود تحديث الجلسة ---

        $this->session->set_flashdata('message', faucet_alert('success', 'Updated'));
        return redirect(site_url('/admin/' . $from));
    }
}
