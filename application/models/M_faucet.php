<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_Faucet extends CI_Model
{

	public function check_history($method, $id)
	{
		$find = $this->db->get_where('faucet_history', array('method' => $method, 'user_id' => $id));
		return ($find->num_rows() > 0);
	}

	public function get_claim_history($method, $id)
	{
		$claim_time = $this->db->get_where('faucet_history', array('method' => $method, 'user_id' => $id));
        if ($claim_time->num_rows() == 0) {
            return false;
        }		
		return $claim_time->result_array()[0];
	}

	public function get_withdrawals_history($method, $type)
	{
		$this->db->order_by('id', "desc")->limit(20);
		return $this->db->get_where('withdraw_history', array('method' => $method, 'type' => $type))->result_array();
	}

	public function countHistory($method, $id)
	{
		$today_midnight = strtotime('today midnight');
		$ip_address = $this->input->ip_address();
	
	$array = array('method' => $method, 'user_id' => $id, 'type' => 1, 'claim_time >' => $today_midnight);
	    $this->db->from('withdraw_history');
		$this->db->where($array);
		$count = $this->db->count_all_results();
		return $count;
	}

	public function check_limit($method, $limit, $id)
	{
		$count_claim = $this->countHistory($method, $id);
		return $count_claim < $limit;
	}

	public function reduce_energy($id)
	{
		$this->db->where('id', $id);
		$this->db->set('energy', 'energy-10', FALSE);
		$this->db->update('users');
	}

    public function insert_history($uid, $method, $wallet, $amount, $type)
    {
        $insert = array(
            'method' => $method,
            'wallet' => $wallet,
            'user_id' => $uid,
            'type' => $type,
            'ip_address' => $this->input->ip_address(),
            'amount' => $amount,
            'claim_time' => time()
        );
        $this->db->insert('withdraw_history', $insert);
    }
    
    public function insert_faucet_history($uid, $method, $amount)
    {
        $insert = array(
            'method' => $method,
            'user_id' => $uid,
            'ip_address' => $this->input->ip_address(),
            'amount' => $amount,
            'claim_time' => time()
        );
        $this->db->insert('faucet_history', $insert);
    }
    
    public function update_faucet_history($uid, $method, $amount)
    {
	$array = array('method' => $method, 'user_id' => $uid);
	
		$this->db->where($array);
		$this->db->set('ip_address', $this->input->ip_address());
		$this->db->set('amount', $amount);
		$this->db->set('claim_time', time());
		$this->db->update('faucet_history');
    }        
    
	public function update_user($id)
	{
		$this->db->where('id', $id);
		$this->db->set('ip_address', $this->input->ip_address());
		$this->db->set('last_claim', time());
		$this->db->set('last_active', time());
		$this->db->set('token', random_string('alnum', 30));
		$this->db->update('users');
	}
	
	public function update_active($id)
	{
		$this->db->where('id', $id);
		$this->db->set('ip_address', $this->input->ip_address());
		$this->db->set('last_active', time());
		$this->db->set('token', random_string('alnum', 30));
		$this->db->update('users');
	}	

	public function findCheaters($userId)
	{
		$past = time() - 86400;
		$ip = $this->input->ip_address();
		$find = $this->db->query("SELECT COUNT(*) as cnt FROM faucet_history WHERE claim_time>" . $past . " AND ip_address = '" . $ip . "' AND user_id<>" . $userId)->result_array()[0]['cnt'];
		if ($find > 0) {
			$this->db->query("UPDATE users SET status='Multiple Accounts on Faucet' WHERE id IN (SELECT user_id FROM faucet_history WHERE claim_time>" . $past . " AND ip_address = '" . $ip . "')");
		}
	}
    public function get_ref($id)
    {
        return $this->db->get_where('users', array('referred_by' => $id))->num_rows();
    }	
    
	public function countWithdrawals($met)
	{
return $this->db->get_where('withdraw_history', array('method' => $met))->num_rows();
	}
	
	
}
