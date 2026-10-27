<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Overview extends Admin_Controller
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
        $this->data['page'] = 'Overview';

        $todayTime = strtotime('today midnight');

        $todayStat = [];
        $todayStat[0]['date'] = 'Today';
        $todayStat[0]['active_users'] = $this->m_admin->countActiveUsers($todayTime);        
        $todayStat[0]['new_users'] = $this->m_admin->countNewUsers($todayTime);

        $faucetStat = $this->m_admin->faucetStat($todayTime);
        $todayStat[0]['faucet_count'] = $faucetStat['cnt'];
        $todayStat[0]['faucet_amount'] = $faucetStat['amount'];

        $stats = $this->m_admin->getStats();
        $this->data['info'] = $this->m_admin->info();

        $stats = array_reverse(array_merge($todayStat, $stats));

        $this->data['stats'] = [];
        foreach ($stats[0] as $key => $value) {
            $this->data['stats'][$key] = [];
        }
        $this->data['stats']['total'] = [];

        foreach ($stats as $day) {
            $total = 0;
            foreach ($day as $type => $value) {
                if (!in_array($type, ['id', 'is_done', 'withdraw_count'])) {
                    array_push($this->data['stats'][$type], $value);
                    if (strpos($type, 'amount')) {
                        $total += $value;
                    }
                }
            }
            array_push($this->data['stats']['total'], $total);
        }

        $this->render('overview', $this->data);
    }
    public function clear_history()
    {
        $this->m_admin->clear_banned();
        redirect(site_url('/admin/overview'));
    }
}
