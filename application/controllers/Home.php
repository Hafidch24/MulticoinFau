<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Home extends Member_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("string");
        $this->load->model("m_faucet");
        $this->load->library("form_validation");
    }
    public function index()
{
    if($this->data['settings']['status'] == 'off') {
        return redirect(site_url('maintenance'));
    }

    $this->data["page"] = "MultiCoin Faucet";

    // التحقق: إذا لم تكن هناك جلسة مستخدم (لم يسجل الدخول)
    if (!isset($_SESSION['FID'])) {
        
        // 1. إنشاء بريد وهمي (أو استخدام مستخدم موجود مسبقاً)
        $fake_email = "guest_user@localhost.com";
        $user = $this->m_core->get_user_from_address($fake_email);

        // 2. إذا لم يكن المستخدم موجوداً في قاعدة البيانات، قم بإنشائه
        if (!$user) {
            $this->load->model('m_auth');
            // هنا نقوم بتسجيله مباشرة بدون أي تحقق من FaucetPay
            $this->m_auth->register($fake_email, "N/A", "N/A", 0, "direct");
            $user = $this->m_core->get_user_from_address($fake_email);
        }

        // 3. حفظ الجلسة (تسجيل الدخول التلقائي)
        if ($user) {
            $this->session->set_userdata("FID", $user["id"]);
            $this->m_core->insertNewIp($user["id"]); // تسجيل الآي بي
        }
        
        // 4. إعادة التوجيه للصفحة الرئيسية (لكي يقرأ الجلسة الجديدة ويدخل)
        return redirect(site_url());

    } else {
        // إذا كان مسجلاً بالفعل، اعرض واجهة المستخدم (الـ Faucet)
        $this->data["his"] = $this->m_core->get_withdrawals_history();
        $this->render("home", $this->data);  
    }
}
}