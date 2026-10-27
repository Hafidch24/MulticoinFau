<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Links extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		if ($this->data['settings']['shortlink_status'] != 'on') {
			return redirect(site_url());
		}
    if (!isset($_SESSION['FID'])) { 
    return redirect(site_url());
    }		
		$this->load->model('m_links');
        $this->load->model("m_faucet");
		$this->load->helper('string');
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
		$this->data['page'] = 'Shortlinks';
            $this->data["this_page"] = $this->m_core->getCurrency($cur);
                if ($this->data["this_page"]) {

		$this->data['availableLinks'] = $this->m_links->availableLinks($this->data['user']['id']);
		$this->data['totalReward'] = 0;
		for ($i = 0; $i < count($this->data['availableLinks']); ++$i) {
			$countHistory = $this->m_links->countHistory($this->data['user']['id'], $this->data['availableLinks'][$i]['id']);
			$this->data['availableLinks'][$i]['rmnViews'] = $this->data['availableLinks'][$i]['view_per_day'] - $countHistory;
			$this->data['totalReward'] += $this->data['availableLinks'][$i]['rmnViews'] * $this->data['availableLinks'][$i]['reward'];
		}
                        $this->data[
                            "referralCount"
                        ] = $this->m_faucet->get_ref(
                            $this->data["user"]["id"]
                        );
                    $this->data[
                        "withdrawals_history"
                    ] = $this->m_faucet->get_withdrawals_history(
                        $this->data["this_page"]["code"],2
                    );
		usort($this->data['availableLinks'], 'sortLinks');
        $this->data['current2'] = $cur;
        $this->data['check'] = false;
        $this->data['countAvailableLinks'] = $this->m_core->countAllLinksView() - $this->m_core->countLinkHistory($this->data['user']['id']);
		$this->render('links', $this->data);
                } else {
                    show_404();
                }
	}

	public function check($link_id = 0, $cur = '')
	{
	    $this->data["this_page"] = $this->m_core->getCurrency($cur);
            if (!$this->data["this_page"]) {
                show_404();
            }
	    $this->data['page'] = 'Shortlinks';
	    $this->data['check'] = true;
	    $this->data['id'] = $link_id;
	    $this->data['code'] = $cur;
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
	    $this->render('links', $this->data);
	}

	public function go($link_id = 0, $cur = '')
	{
        if ($this->data["settings"]["antibotlinks"] == "on") {
        #CHECK ANTIBOTLINKS
        $sting    = $_SESSION['userT'];
        $post_code  = $this->input->post($sting);
        $req    = $this->session->userdata($sting);
        
        if ($post_code != $req) {
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
                    faucet_sweet_alert("error", "Invalid Anti-Bot Links")
                );
                return redirect(site_url("/links/check/$link_id/$cur"));
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
            return redirect(site_url("/links/check/$link_id/$cur"));
        }

		$link_id = $this->db->escape_str($link_id);
		$link_data = $this->m_links->valid_id($link_id, $this->data['user']['id']);
		if (!$link_data) {
			return redirect(site_url('/links/currency/'.$cur));
		}

		$key = random_string('alnum', 20);

		// generate short link
		$url = urlencode(site_url('/links/back/'.$key.'/'.$cur));
		$api_url = str_replace('{url}', $url, $link_data['url']);
		$result = @json_decode(get_data($api_url), TRUE);
		if ($result['status'] == 'success') {
			$this->m_links->insert_link($this->data['user']['id'], $key, $link_id, $result['shortenedUrl']);
			echo '<script> location.href = "' . $result['shortenedUrl'] . '"; </script>';
			return redirect($result['shortenedUrl']);
		}
		$this->session->set_flashdata('message', faucet_alert('danger', 'Failed to generate this link'));
		return redirect(site_url('/links/currency/'.$cur));
	}

	public function verify($key = '', $cur = '')
	{
		if (strlen($key) != 20 || !preg_match("/^[a-zA-Z0-9]+$/", $key)) {
			$this->session->set_flashdata('message', faucet_alert('danger', 'Invalid Keys'));
			return redirect(site_url('/links/currency/'.$cur));
		}

		$key = trim($this->db->escape_str($key));
		$link = $this->m_links->check_key($key, $this->data['user']['id']);
		if (!$link || $link['ip_address'] != $this->input->ip_address()) {
			$this->session->set_flashdata('message', faucet_alert('danger', 'Invalid Keys'));
			return redirect(site_url('/links/currency/'.$cur));
		}

		$link_data = $this->m_links->valid_id($link['link_id'], $this->data['user']['id']);
		if (!$link_data) {
			if ($this->data['user']['fail'] == $this->data['settings']['captcha_fail_limit']) {
				$this->m_core->insertCheatLog($this->data['user']['id'], 'Too many wrong captcha.', 0);
			} else if ($this->data['user']['fail'] < 4) {
				$this->m_core->wrongCaptcha($this->data['user']['id']);
			}
			return redirect(site_url('/links/currency/'.$cur));
		}
        
        $faucet = $this->m_core->getCurrency($cur);
        $api = $faucet["api"];
        $amount = number_format($link_data['reward']/$faucet["price"],8);
        $amount = $amount * 100000000;
        $currency = $faucet["code"];
        $address = $this->data['user']['wallet'];
        $ip_address = $this->input->ip_address();
        $referral = false;

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
		$this->m_links->insert_history($this->data['user']['id'], $link_data['reward'], $link_data['id']);
            $this->m_faucet->insert_history(
                $this->data["user"]["id"],
                $currency,
                $address,
                number_format($amount / 100000000, 8), 2
            );
            
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
		return redirect(site_url('/links/currency/'.$cur));
	}

	public function back($key = '', $cur = '')
	{
		if (strlen($key) == 20) {
			return redirect(site_url('/links/verify/' . $key.'/'.$cur));
		}
	}
}
