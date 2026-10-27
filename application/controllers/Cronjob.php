<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cronjob extends Guess_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['m_admin', 'm_cronjob']);
    }
    public function fiveminutes()
    {
    
        $currencies = $this->m_cronjob->getCurrencyName();
        
        $currencyName = [];
        foreach ($currencies as $currency) {        
            array_push($currencyName, $currency['currency_name']);
        }                  
          
        $query = urlencode(implode(',', $currencyName));
        $apiUrl = 'https://api.coingecko.com/api/v3/simple/price?ids=' . $query . '&vs_currencies=usd';
        $result = @json_decode(get_data($apiUrl), TRUE);

        foreach ($result as $name => $price) {
            $this->m_cronjob->updatePrice($name, $price['usd']);
        }

        echo 'ok 1';
    }
    
        public function daily()
    {
    
        $currencies = $this->m_cronjob->getCurrencyName();
        $lastprices = $this->m_cronjob->getCurrencyPrice();
        
        $currencyName = [];
        foreach ($currencies as $currency) {        
            array_push($currencyName, $currency['currency_name']);
        }
        
        $currencyPrice = [];
        foreach ($lastprices as $price) {        
            array_push($currencyPrice, $price['price']);
        }                        
          
        foreach (array_combine($currencyName, $currencyPrice) as $currency1 => $lastprice1) { 
            $this->m_cronjob->updateLastPrice($currency1, $lastprice1);
        }                  
          
        echo 'ok 2';
    }

}
