<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('string');
		$this->load->model('m_auth');
		$this->load->library('user_agent');
		$this->data = $this->m_core->getSettings();
	}
	public function index()
	{
	}

    public function login()
{
  
    
    // ... باقي الكود
      
	if($this->data['anti_proxy'] == 'on') {
        if ($this->agent->is_browser()){
                $agent = $this->agent->browser().' '.$this->agent->version();
        }elseif ($this->agent->is_robot()){
                $agent = $this->agent->robot();
        }elseif ($this->agent->is_mobile()){
                $agent = $this->agent->mobile();
        }else{
                $agent = 'Unidentified User Agent';
        }
        
        $proxy = check_ip($this->input->ip_address(), $agent);
        if($proxy) {
            $this->session->set_flashdata('message', faucet_alert('danger', 'Proxy Detected!'));
			return redirect(site_url());
        }
	}
        $this->form_validation->set_rules(
            "wallet",
            "Wallet",
            "trim|required|valid_email|max_length[75]"
        );

        $wallet = $this->db->escape_str($this->input->post("wallet"));
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata(
                "sweet_message",
                faucet_sweet_alert("error", "Invalid Email")
            );
            return redirect(site_url());
        }
        
        $api = "90332fc9fc6cd179235a0f1cb1b1bf38179653b6";
        $address = $wallet;
        $ip_address = $this->input->ip_address();

        $param = [
            "api_key" => $api,
            "address" => $address
        ];
        $url = "https://faucetpay.io/api/v1/checkaddress";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, count($param));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);                

        if ($result["status"] != 200) {
            $this->session->set_flashdata(
                'sweet_message',
                faucet_sweet_alert('error', 'Your email is not registered to FaucetPay.<br><a href="https://faucetpay.io/?r=3732157" target="_blank">Register to FaucetPay Here</a>')
            );
            return redirect(site_url());        
        }        
        $user1 = $this->m_core->get_user_from_address($wallet);

        if (!$user1) {
            $multiple = $this->m_auth->multiple_account($ip_address);
            if ($multiple > 0) {
                $this->session->set_flashdata(
                    "sweet_message",
                    faucet_sweet_alert(
                        "error",
                        "You are not allowed to create multiple accounts!"
                    )
                );
                return redirect(site_url());
            }

            $isocode = "N/A";
            $country = "N/A";
            if (!empty($this->data["proxycheck"])) {
                $check = proxycheck($this->data, $this->input->ip_address());
                if ($check["status"] == 1) {
                    $this->session->set_flashdata(
                        "sweet_message",
                        faucet_sweet_alert(
                            "error",
                            "VPN/ Proxy is not allowed!"
                        )
                    );
                    return redirect(site_url());
                }
                $isocode = isset($check["isocode"]) ? $check["isocode"] : "N/A";
                $country = isset($check["country"]) ? $check["country"] : "N/A";
            } elseif (!empty($this->data["iphub"])) {
                $check = iphub($this->data, $this->input->ip_address());
                if ($check["status"] == 1) {
                    $this->session->set_flashdata(
                        "sweet_message",
                        faucet_sweet_alert(
                            "error",
                            "VPN/ Proxy is not allowed!"
                        )
                    );
                    return redirect(site_url());
                }
                $isocode = isset($check["isocode"]) ? $check["isocode"] : "N/A";
                $country = isset($check["country"]) ? $check["country"] : "N/A";
            }

            // referral
            if (!empty($_SESSION["referral"])) {
                $referral = $_SESSION["referral"];
                if (!is_numeric($referral)) {
                    $referral = 0;
                } else {
                    $referral = $this->db->escape_str($referral);
                    if (!$this->m_auth->valid_referral($referral)) {
                        $referral = 0;
                    }
                    unset($_SESSION["referral"]);
                }
            } else {
                $referral = 0;
            }

            $referralSource = "direct";
            if (
                isset($_COOKIE["Referral_Source"]) &&
                filter_var($_COOKIE["Referral_Source"], FILTER_VALIDATE_URL)
            ) {
                $referralSource = $this->db->escape_str(
                    $_COOKIE["Referral_Source"]
                );
            }

            $this->m_auth->register(
                $wallet,
                $isocode,
                $country,
                $referral,
                $referralSource
            );
            $sameIp = $this->m_auth->isCheater();
            $firstId = false;
            if ($sameIp) {
                foreach ($sameIp as $cheater) {
                    if (!$firstId) {
                        $firstId = $this->m_core->insertCheatLog(
                            $cheater["id"],
                            "Multiple account.",
                            0
                        );
                    } else {
                        $this->m_core->insertCheatLog(
                            $cheater["id"],
                            "Multiple account.",
                            $firstId
                        );
                    }
                }
            }

            $user = $this->m_core->get_user_from_address($wallet);

// --- كود تتبع ---
echo "<h2>فحص البيانات قبل حفظ الجلسة:</h2>";
echo "البريد الإلكتروني المدخل: " . $wallet . "<br>";
echo "بيانات المستخدم من قاعدة البيانات: ";
echo "<pre>";
print_r($user);
echo "</pre>";
die("توقفنا هنا قبل حفظ الجلسة.");
// ----------------

$this->session->set_userdata("FID", $user["id"]);

            if ($this->m_core->newIpUser($user["id"])) {
                $this->m_core->insertNewIp($user["id"]);
            } else {
                $this->m_core->updateIpLastUse($user["id"]);
            }
            $this->session->set_flashdata(
                "sweet_message",
                faucet_sweet_alert("success", "New Account Registered")
            );
        } else {
            $this->session->set_userdata("FID", $user1["id"]);
            if ($this->m_core->newIpUser($user1["id"])) {
                $this->m_core->insertNewIp($user1["id"]);
            } else {
                $this->m_core->updateIpLastUse($user1["id"]);
            }
            $this->session->set_flashdata(
                "sweet_message",
                faucet_sweet_alert("success", "Login Success")
            );
        }

        return redirect(site_url());
    }
    

	public function logout()
	{
		session_destroy();
		return redirect(site_url());
	}
}
