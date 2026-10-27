<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_Admin extends CI_Model
{
	public function getStats()
	{
		$this->db->order_by('date', 'desc');
		$date = date("Y-m-d", time() - 86400 * 15);
		$today = date("Y-m-d", time());
		return $this->db->get_where('faucet_stats', ['date>' => $date, 'date<' => $today])->result_array();
	}

	public function countActiveUsers($today)
	{
		return $this->db->query("SELECT COUNT(*) AS cnt FROM users WHERE last_active >= " . $today)->result_array()[0]['cnt'];
	}

	public function countNewUsers($today)
	{
		return $this->db->query("SELECT COUNT(*) AS cnt FROM users WHERE joined >= " . $today)->result_array()[0]['cnt'];
	}

	public function faucetStat($today)
	{
		return $this->db->query("SELECT COUNT(*) AS cnt, IFNULL(SUM(amount), 0) AS amount FROM withdraw_history WHERE claim_time >= " . $today)->result_array()[0];
	}


	public function info()
	{
		$result['totalUser'] = $this->db->query('SELECT COUNT(id) AS cnt FROM users WHERE 1')->result_array()[0]['cnt'];
		$today = strtotime('today midnight');
		$yesterday = strtotime('yesterday midnight');
		$result['activeToday'] = $this->db->query('SELECT COUNT(id) AS cnt FROM users WHERE last_active > ' . $today)->result_array()[0]['cnt'];
		$result['NotactiveToday'] = $this->db->query('SELECT COUNT(id) AS cnt FROM users WHERE last_active < ' . $today)->result_array()[0]['cnt'];
		
		$result['registerToday'] = $this->db->query('SELECT COUNT(id) AS cnt FROM users WHERE joined > ' . $today)->result_array()[0]['cnt'];
		return $result;
	}

	public function clear_history()
	{
		$date = date("Y-m-d", time() - 86400 * 15);
		$this->db->delete('faucet_stats', array('date<' => $date));
		$this->db->delete('faucet_history', array('claim_time<' => time() - 86400 * 2));
		$this->db->delete('withdraw_history', array('claim_time<' => time() - 86400 * 30));
		$this->db->delete('ip_addresses', array('last_use<' => time() - 86400 * 2));
	}
	public function clear_banned()
	{
		$past = time() - 86400;
		$user = $this->db->delete('cheat_logs', array('create_time <=' => $past));	
	}
	public function count_all_users()
	{
		return $this->db->query("SELECT COUNT(*) AS cnt FROM users")->result_array()[0]['cnt'];
	}

	public function get_users($skip)
	{
		$past = time() - 86400;
		$now = strtotime("now");
		$this->db->order_by('id', "desc")->limit(50, $skip);
		return $this->db->get_where('users', array('last_active <' => $past))->result_array();
	}
	
	public function get_users_off()
	{
		$yesterday = strtotime('yesterday midnight');
		$user = $this->db->get_where('users', array('last_active <' => $yesterday));
		if ($user->num_rows() == 0) {
			return false;
		}
		return $user->result_array()[0];
	}	

	public function get_referrals($id)
	{
		$this->db->order_by('id', "desc");
		return $this->db->get_where('users', array('referred_by' => $id))->result_array();
	}

	public function get_withdrawal($id)
	{
		$result = $this->db->get_where('withdraw_history', array('id' => $id));
		if (!$result->num_rows()) {
			return false;
		}
		return $result->result_array()[0];
	}
	public function active($id)
	{
		$this->db->where('id', $id);
		$this->db->set('verified', '1');
		$this->db->update('users');
	}

	public function get_banned_users($skip)
	{
		return $this->db->query("SELECT * FROM users WHERE id IN (SELECT user_id FROM cheat_logs) LIMIT 50 OFFSET " . $skip)->result_array();
	}

	public function count_banned_users()
	{
		return $this->db->query("SELECT * FROM users WHERE id IN (SELECT user_id FROM cheat_logs) ")->num_rows();
	}

	public function getCurrency()
	{
		return $this->db->get('currencies')->result_array();
	}

	public function addCurrency($name, $currencyName, $code, $api, $wallet, $reward, $timer, $limit, $price)
	{
		$insert = array(
			'name' => $name,
			'currency_name' => $currencyName,
			'code' => $code,
			'api' => $api,
			'wallet' => $wallet,
			'reward' => $reward,
			'timer' => $timer,
			'price' => $price,
			'last_price' => $price,
			'limit_claim' => $limit
		);
		$this->db->insert('currencies', $insert);
	}
	public function updateCurrency($id, $name, $currencyName, $code, $api, $wallet, $reward, $timer, $limit)
	{
		$this->db->where('id', $id);
		$this->db->set('name', $name);
		$this->db->set('currency_name', $currencyName);
		$this->db->set('code', $code);
		$this->db->set('api', $api);
		$this->db->set('wallet', $wallet);
		$this->db->set('reward', $reward);
		$this->db->set('timer', $timer);
		$this->db->set('limit_claim', $limit);
		$this->db->update('currencies');
	}

	public function get_user($id)
	{
		return $this->db->get_where('users', array('id' => $id))->result_array()[0];
	}

	public function getSameIpAddress($skip)
	{
		return $this->db->query("SELECT ip_address FROM ip_addresses GROUP BY ip_address HAVING COUNT(ip_address) > 1 ORDER BY id DESC LIMIT 50 OFFSET " . $skip)->result_array();
	}
	public function countSameIpAddress()
	{
		return $this->db->query("SELECT ip_address FROM ip_addresses GROUP BY ip_address HAVING COUNT(ip_address) > 1")->num_rows();
	}
	public function getUsersByIpAddress($ipAddress)
	{
		return $this->db->query("SELECT id, wallet, ip_address, referred_by, isocode, country FROM users WHERE id IN (SELECT user_id FROM ip_addresses WHERE ip_address = '" . $ipAddress . "')")->result_array();
	}

	public function getUserFromId($id)
	{
		$user = $this->db->get_where('users', array('id' => $id));
		return $user->result_array();
	}
	public function getUserFromEmail($email)
	{
		return $this->db->query("SELECT * FROM users WHERE wallet LIKE '%" . $email . "%'")->result_array();
	}

	public function getUserFromIp($ipAddress)
	{
		return $this->db->query(
			"SELECT * FROM users WHERE ip_address = '" . $ipAddress . "' OR id IN
		(
			SELECT user_id FROM ip_addresses WHERE ip_address = '" . $ipAddress . "') ORDER BY id DESC"
		)->result_array();
	}

	public function addAuthenticator($secret)
	{
		$this->db->set('value', $secret);
		$this->db->where('name', 'authenticator_code');
		$this->db->update('settings');
	}
	
	public function add_link($name, $url, $reward, $view)
	{
		$insert = array(
			'name' => $name,
			'url' => $url,
			'reward' => $reward,
			'view_per_day' => $view
		);
		$this->db->insert('links', $insert);
	}
	public function update_link($id, $name, $url, $reward, $view)
	{
		$this->db->where('id', $id);
		$this->db->set('name', $name);
		$this->db->set('url', $url);
		$this->db->set('reward', $reward);
		$this->db->set('view_per_day', $view);
		$this->db->update('links');
	}

}
