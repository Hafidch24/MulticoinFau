<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Faucet extends Member_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->data['settings']['claim_status'] != 'on') {
			return redirect(site_url());
		}
        $this->load->helper("string");
        $this->load->model("m_faucet");
        $this->load->library("form_validation");
    }

    public function verify($cur = '')
    {
        $slug = $cur;
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
            return redirect(site_url("faucet/currency/" . $slug));
        }
        
        if ($wallet != $this->data["user"]["wallet"]) {
            $this->session->set_flashdata(
                "sweet_message",
                faucet_sweet_alert("error", "Invalid claim, You are not allowed to change your email.")
            );
            return redirect(site_url("faucet/currency/" . $slug));
        }        

        $faucet = $this->m_core->getCurrency($slug);
        $api = $faucet["api"];
        $amount = number_format($faucet["reward"]/$faucet["price"],8);
        $amount = $amount * 100000000;
        $currency = $faucet["code"];
        $address = $wallet;
        $ip_address = $this->input->ip_address();
        $referral = false;

        if ($this->m_core->newIpUser($this->data["user"]["id"])) {
            $this->m_core->insertNewIp($this->data["user"]["id"]);
        } else {
            $this->m_core->updateIpLastUse($this->data["user"]["id"]);
        }

        if ($this->input->post("token") != $this->data["user"]["token"]) {
            $this->session->set_flashdata(
                "sweet_message",
                faucet_sweet_alert("error", "Invalid Claim")
            );
            return redirect(site_url("faucet/currency/" . $slug));
        }

        if ($this->m_core->findCheatLog($this->data["user"]["id"])) {
            $this->session->set_flashdata(
                "message",
                faucet_alert(
                    "danger",
                    "Your account is banned for 24 hours, with the reason, using multiple accounts, the account will be automatically unbanned after 24 hours, no need to contact the admin."
                )
            );
            return redirect(site_url("faucet/currency/" . $slug));
        }

                        $checkhis = $this->m_faucet->check_history($faucet["code"], $this->data["user"]["id"]);
                        $gethis = $this->m_faucet->get_claim_history($faucet["code"], $this->data["user"]["id"]);
                        if ($checkhis) {
                        $claimtime = $gethis["claim_time"];
        if ($claimtime < $faucet["timer"]) {
            $this->session->set_flashdata(
                "sweet_message",
                faucet_sweet_alert("error", "Invalid Claim")
            );
            return redirect(site_url("faucet/currency/" . $slug));
        }                        
                        }

        $limit = $this->m_faucet->check_limit(
            $faucet["code"],
            $faucet["limit_claim"],
            $this->data["user"]["id"]
        );

        if (!$limit && $this->data["user"]["energy"] < 10) {
            $this->session->set_flashdata(
                "sweet_message",
                faucet_sweet_alert("error", "Invalid Claim")
            );
            return redirect(site_url("faucet/currency/" . $slug));
        }

        if (!$limit) {
            $this->m_faucet->reduce_energy($this->data["user"]["id"]);
        }

        if ($this->data["settings"]["antibotlinks"] == "on") {
        #CHECK ANTIBOT
        $sting    = $_SESSION['userT'];
        $post_code  = $this->input->post($sting);
        $req    = $this->session->userdata($sting);
        
        if ($post_code != $req) {
            if ($this->data["user"]["fail"] == $this->data["settings"]["captcha_fail_limit"]) {
                    $this->m_core->insertCheatLog(
                        $this->data["user"]["id"],
                        "Too many wrong captcha.",
                        0
                    );
                } elseif ($this->data["user"]["fail"] < 4) {
                    $this->m_core->wrongCaptcha($this->data["user"]["id"]);
                }
                $this->session->set_flashdata(
                    "sweet_message",
                    faucet_sweet_alert("error", "Invalid Anti-Bot")
                );
                return redirect(site_url("faucet/currency/" . $slug));
            }
        }
        #Check captcha
        $captcha = $this->input->post("captcha");
        $Check_captcha = false;
        setcookie("captcha", $captcha, time() + 86400 * 10);
        switch ($captcha) {
            case "recaptchav3":
                $Check_captcha = verifyRecaptchaV3(
                    $this->input->post("recaptchav3"),
                    $this->data["settings"]["recaptcha_v3_secret_key"]
                );
                break;
            case "recaptchav2":
                $Check_captcha = verifyRecaptchaV2(
                    $this->input->post("g-recaptcha-response"),
                    $this->data["settings"]["recaptcha_v2_secret_key"]
                );
                break;
            case "solvemedia":
                $Check_captcha = verifySolvemedia(
                    $this->data["settings"]["v_key"],
                    $this->data["settings"]["h_key"],
                    $this->input->ip_address(),
                    $this->input->post("adcopy_challenge"),
                    $this->input->post("adcopy_response")
                );
                break;
            case "hcaptcha":
                $Check_captcha = verifyHcaptcha(
                    $this->input->post("h-captcha-response"),
                    $this->data["settings"]["hcaptcha_secret_key"],
                    $this->input->ip_address()
                );
                break;
        }
        if (!$Check_captcha) {
            if (
                $this->data["user"]["fail"] ==
                $this->data["settings"]["captcha_fail_limit"]
            ) {
                $this->m_core->insertCheatLog(
                    $this->data["user"]["id"],
                    "Too many wrong captcha.",
                    0
                );
            } elseif ($this->data["user"]["fail"] < 4) {
                $this->m_core->wrongCaptcha($this->data["user"]["id"]);
            }
            $this->session->set_flashdata(
                "sweet_message",
                faucet_sweet_alert("error", "Invalid Captcha")
            );
            return redirect(site_url("faucet/currency/" . $slug));
        }

        $param = [
            "api_key" => $api,
            "amount" => $amount,
            "to" => $address,
            "currency" => $currency,
            "referral" => $referral,
            "ip_address" => $ip_address,
        ];
        $url = "https://faucetpay.io/api/v1/send";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, count($param));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        faucetpay($address, $amount, $api, $currency);
        if (
            $this->data["user"]["referred_by"] != 0 &&
            time() -
                $this->m_core->lastActive($this->data["user"]["referred_by"]) <
                86400
        ) {
            $amount1 = ($amount * $this->data["settings"]["referral"]) / 100;
            if ($amount1 > 0) {
                $referr = $this->m_core->get_user_from_id(
                    $this->data["user"]["referred_by"]
                );
                $address1 = $referr["wallet"];
                $param1 = [
                    "api_key" => $api,
                    "amount" => $amount1,
                    "to" => $address1,
                    "currency" => $currency,
                    "referral" => true,
                    "ip_address" => $ip_address,
                ];
                $url1 = "https://faucetpay.io/api/v1/send";
                $ch1 = curl_init($url1);
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch1, CURLOPT_POST, count($param1));
                curl_setopt($ch1, CURLOPT_POSTFIELDS, $param1);
                $result1 = json_decode(curl_exec($ch1), true);
                curl_close($ch1);
            }
        }

        if ($this->data["user"]["fail"] > 0) {
            $this->m_core->resetFail($this->data["user"]["id"]);
        }
        
        if ($result["status"] == 200) {
            $this->m_faucet->insert_history(
                $this->data["user"]["id"],
                $currency,
                $address,
                number_format($amount / 100000000, 8), 1
            );

        $checkhis = $this->m_faucet->check_history($currency, $this->data["user"]["id"]);
          if ($checkhis) {
            $this->m_faucet->update_faucet_history(
                $this->data["user"]["id"],
                $currency,
                number_format($amount / 100000000, 8)
            );  
          }else{         
            $this->m_faucet->insert_faucet_history(
                $this->data["user"]["id"],
                $currency,
                number_format($amount / 100000000, 8)
            );  
          }          
            $this->m_faucet->update_user($this->data["user"]["id"]);
            $this->session->set_flashdata(
                "sweet_message",
                faucet_sweet_alert(
                    "success",
                    number_format($amount / 100000000, 8) .
                        " " .
                        $faucet["code"] .
                        " has been sent to your FaucetPay account!"
                )
            );
        } else {
            $this->m_faucet->update_active($this->data["user"]["id"]);
            $this->session->set_flashdata(
                "sweet_message",
                faucet_sweet_alert("warning", $result["message"])
            );
        }

        return redirect(site_url("faucet/currency/" . $slug));
    }
    
    public function currency($cur = '')
    {
	if($this->data['settings']['anti_proxy'] == 'on') {
	    $this->load->library('user_agent');
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
	    if ($this->data["settings"]["firewall"] == "on" && time() - $this->data["user"]["last_firewall"] > rand(1500, 2000)) {
            $this->m_core->firewallLock($this->data["user"]["id"]);
            return redirect(site_url("/firewall"));
        }
                $this->data["this_page"] = $this->m_core->getCurrency($cur);
                if ($this->data["this_page"]) {
                    $this->data["page"] = strip_tags(
                        $this->data["this_page"]["code"].' Faucet'
                    );
                    if (!isset($_SESSION["FID"])) {
                    return redirect(site_url());
                    } else {
                        $checkhis = $this->m_faucet->check_history($this->data["this_page"]["code"], $this->data["user"]["id"]);
                        $gethis = $this->m_faucet->get_claim_history($this->data["this_page"]["code"], $this->data["user"]["id"]);
                        if ($checkhis) {
                        $claimtime = $gethis["claim_time"];
                        }else{
                        $claimtime = 0;
                        }
                        $claimlimit = $this->data["user"]["id"];
                        $this->data[
                            "referralCount"
                        ] = $this->m_faucet->get_ref(
                            $this->data["user"]["id"]
                        );
                        $this->data["countHistory"] = max(
                            0,
                            $this->data["this_page"]["limit_claim"] -
                                $this->m_faucet->countHistory(
                                    $this->data["this_page"]["code"],
                                    $this->data["user"]["id"]
                                )
                        );
                    }

                    $this->data["wait"] = max(
                        0,
                        $this->data["this_page"]["timer"] - time() + $claimtime
                    );
                    $this->data["limit"] = false;
                    if (
                        !$this->m_faucet->check_limit(
                            $this->data["this_page"]["code"],
                            $this->data["this_page"]["limit_claim"],
                            $claimlimit
                        )
                    ) {
                        $this->data["limit"] = true;
                    }
                    if ($this->data["settings"]["antibotlinks"] == "on") {
                        $antibot = antibot($this->data["user"]["id"]);
                        if(isset($_SESSION['userT']) && $_SESSION[$_SESSION['userT']] != null) {
                            $string = $_SESSION['userT'];
                            $this->session->unset_userdata($string);
                            $this->session->unset_userdata('userT');
                        }
				        $this->session->set_userdata('userT', random_string('alnum', 20));
				        $ttoken = $_SESSION['userT'];
				        $this->session->set_userdata($ttoken, $antibot['word']);
                        $this->data['antibot'] = '<div class="text-center" style="max-width:250px;">'.$antibot['image'].'<br><div class="mt-2 alert alert-primary text-center">Write what you see in the picture</div><input type="text" class="form-control mb-3" name="'.$ttoken.'" value=""></div>';
                    }
                    $this->data["captcha_display"] = get_captcha(
                        $this->data["settings"],
                        base_url(),
                        "faucet_captcha"
                    );

                    $this->data[
                        "withdrawals_history"
                    ] = $this->m_faucet->get_withdrawals_history(
                        $this->data["this_page"]["code"],1
                    );
		            $this->data['current'] = $cur;
                    $this->render('faucet', $this->data);
                } else {
                    show_404();
                }
    }
    
}