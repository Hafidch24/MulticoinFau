<div class="row">  
    <div class="col-lg-12 text-center">          
    <?php if (isset($current) != '') { ?>  
        <?php if ($limit) { ?>
        <h2><a href="<?= base_url() ?>" alt="Back"><span class="text-white"><i class="fas fa-arrow-alt-circle-left"></i></span></a> Claim <?= $this_page['name'] ?></h2>          
        <div class="alert alert-danger text-center">Daily claim limit for this coin reached, please comeback again tomorrow.</div>
        <div class="alert alert-success text-center">Invite your friends and earn <?= $settings['referral'] ?>% of their earning. Your referral links is: <code><?= site_url('/?r=' . $user['id']) ?></code></div>
        <p class="text-muted font-weight-medium">Referrals: <?= $referralCount ?></p>
        <?php } else if ($wait) { ?>
                <div class="media">
                    <div class="media-body text-center">
                        <?php
                        if ($wait) { ?>
                            <h4 class="next-button lh-1 mb-1 font-weight-bold"><i class="fas fa-sync fa-spin"></i> Please wait <b id="minute"><?= floor($wait / 60) ?></b>:<b id="second"><?= $wait % 60 ?></b></h4>
                        <?php } else { ?>
                            <h4 class="lh-1 mb-1 font-weight-bold">READY</h4>
                        <?php } ?>
                    </div>
                </div>                                
            <script type="text/javascript">
                var wait = <?= $wait ?> - 1;
            </script>
        <div class="alert alert-success text-center">Invite your friends and earn <?= $settings['referral'] ?>% of their earning. Your referral links is: <code><?= site_url('/?r=' . $user['id']) ?></code></div>
        <p class="text-muted font-weight-medium">Referrals: <?= $referralCount ?></p>                        
        <?php } ?>
        <?php if (!$limit && !$wait) { ?>                     
<h6><img class="currency-dashboard" src="<?= site_url('BSassets/images/currencies/' . strtolower($this_page['code']) . '.png') ?>" /> Balance Status:
<?php
	$param = array(
		'api_key' => $this_page['api'],
		'currency' => $this_page['code']
	);
	$url = 'https://faucetpay.io/api/v1/getbalance';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, count($param));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	$faucetpay_url = json_decode(curl_exec($ch), true);
	curl_close($ch);
$str = number_format($this_page['reward']/$this_page['price'], 8);
$int = filter_var($str, FILTER_SANITIZE_NUMBER_INT);
	if ($faucetpay_url['status'] == 200 && $faucetpay_url['balance'] > $int) {
        echo ' <span class="badge badge-success">Ready</span>';
    }else if ($faucetpay_url['status'] == 200 && $faucetpay_url['balance'] <= $int) {
        echo ' <span class="badge badge-danger">Empty</span>';
    }else{
        echo ' <span class="text-warning">'.$faucetpay_url['message'].'</span>';
    }
?>
</h6>
<?php
    $a = $this_page['last_price'];
    $b = $this_page['price'];
function percent($a, $b)
{
    $result = 0;
    $result = (($b - $a) * 100) / $a;
    return $result;
}
if($this_page['price'] > $this_page['last_price']) {
echo '<p>Yesterday Price <span class="text-info"><i class="fas fa-dollar-sign"></i>'.format_money($this_page['last_price']).'</span><br>';
echo 'Current Price <span class="text-success"><i class="fas fa-dollar-sign"></i>'.format_money($this_page['price']); 
echo ' <i class="fas fa-caret-up"></i>+'.number_format(percent($a, $b),2) . "%</span></p>";
}else if($this_page['price'] < $this_page['last_price']) {
echo '<p>Yesterday Price <span class="text-info"><i class="fas fa-dollar-sign"></i>'.format_money($this_page['last_price']).'</span><br>';
echo 'Current Price <span class="text-danger"><i class="fas fa-dollar-sign"></i>'.format_money($this_page['price']); 
echo ' <i class="fas fa-caret-down"></i>'.number_format(percent($a, $b),2) . "%</span></p>";
}else if($this_page['price'] == $this_page['last_price']) {
echo '<p>Yesterday Price <span class="text-info"><i class="fas fa-dollar-sign"></i>'.format_money($this_page['last_price']).'</span><br>';
echo 'Current Price <span class="text-info"><i class="fas fa-dollar-sign"></i>'.format_money($this_page['price']); 
echo ' <i class="fas fa-sort"></i>'.number_format(percent($a, $b),2) . "%</span></p>";
}else{
echo 'price calculation error!';
}
?>              
                <div class="row">
                    <div class="text-info col">
                        <p class="lh-1 mb-1 font-weight-bold"><?= number_format($this_page['reward']/$this_page['price'],8) ?> <?= $this_page['code'] ?></p>
                        <?php if($this_page['timer'] >= 60 && $this_page['timer'] < 120) { ?>
                        <p class="lh-1 mb-1 font-weight-bold"><?= floor($this_page['timer'] / 60) ?> Minute</p> 
                        <?php }else if($this_page['timer'] >= 120) { ?>
                        <p class="lh-1 mb-1 font-weight-bold"><?= floor($this_page['timer'] / 60) ?> Minutes</p>                           
                        <?php }else if($this_page['timer'] < 60 && $this_page['timer'] > 1) { ?>
                        <p class="lh-1 mb-1 font-weight-bold"><?= $this_page['timer'] ?> Seconds</p>
                        <?php }else if($this_page['timer'] < 2) { ?>
                        <p class="lh-1 mb-1 font-weight-bold"><?= $this_page['timer'] ?> Second</p>      
                        <?php } ?>                                                              
                    </div>                                    
                    <div class="text-warning col">
                        <p class="lh-1 mb-1 font-weight-bold"><?= $countHistory ?>/<?= $this_page['limit_claim'] ?></p>
                        <p class="lh-1 mb-1 font-weight-bold">claims left</p>
                    </div>
                </div>
             <?php } ?>
<?php }else if (isset($current2) != '') { ?>
<h6>
<img class="currency-dashboard" src="<?= site_url('BSassets/images/currencies/' . strtolower($this_page['code']) . '.png') ?>" /> Balance Status:
<?php
        if ($availableLinks) {
    foreach (array_slice($availableLinks, 0, 1) as $link_data) {
            $linkreward = $link_data['reward'];
    }
        }else{
            $linkreward = 0;
        }
	$param = array(
		'api_key' => $this_page['api'],
		'currency' => $this_page['code']
	);
	$url = 'https://faucetpay.io/api/v1/getbalance';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, count($param));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	$faucetpay_url = json_decode(curl_exec($ch), true);
	curl_close($ch);
$str = number_format($linkreward/$this_page['price'], 8);
$int = filter_var($str, FILTER_SANITIZE_NUMBER_INT);
	
    if ($faucetpay_url['status'] == 200 && $faucetpay_url['balance'] > $int) {
         echo ' <span class="badge badge-success">Ready</span>';
    }else if ($faucetpay_url['status'] == 200 && $faucetpay_url['balance'] <= $int) {
        echo ' <span class="badge badge-danger">Empty</span>';
    }else{
        echo ' <span class="text-warning">'.$faucetpay_url['message'].'</span>';
    }
?>
</h6>
<?php
    $a = $this_page['last_price'];
    $b = $this_page['price'];
function percent($a, $b)
{
    $result = 0;
    $result = (($b - $a) * 100) / $a;
    return $result;
}
if($this_page['price'] > $this_page['last_price']) {
echo '<p>Yesterday Price <span class="text-info"><i class="fas fa-dollar-sign"></i>'.format_money($this_page['last_price']).'</span><br>';
echo 'Current Price <span class="text-success"><i class="fas fa-dollar-sign"></i>'.format_money($this_page['price']); 
echo ' <i class="fas fa-caret-up"></i>+'.number_format(percent($a, $b),2) . "%</span></p>";
}else if($this_page['price'] < $this_page['last_price']) {
echo '<p>Yesterday Price <span class="text-info"><i class="fas fa-dollar-sign"></i>'.format_money($this_page['last_price']).'</span><br>';
echo 'Current Price <span class="text-danger"><i class="fas fa-dollar-sign"></i>'.format_money($this_page['price']); 
echo ' <i class="fas fa-caret-down"></i>'.number_format(percent($a, $b),2) . "%</span></p>";
}else if($this_page['price'] == $this_page['last_price']) {
echo '<p>Yesterday Price <span class="text-info"><i class="fas fa-dollar-sign"></i>'.format_money($this_page['last_price']).'</span><br>';
echo 'Current Price <span class="text-info"><i class="fas fa-dollar-sign"></i>'.format_money($this_page['price']); 
echo ' <i class="fas fa-sort"></i>'.number_format(percent($a, $b),2) . "%</span></p>";
}else{
echo 'price calculation error!';
}
?>
                <div class="row">
                    <div class="text-info col">
                        <p class="lh-1 mb-1 font-weight-bold"><?= number_format($totalReward/$this_page['price'],8) ?> <?= $this_page['code'] ?></p>
                        <p class="lh-1 mb-1 font-weight-bold">Total</p>
                    </div>                                    
                    <div class="text-warning col">
                        <p class="lh-1 mb-1 font-weight-bold"><?= $countAvailableLinks ?> Links</p>
                        <p class="lh-1 mb-1 font-weight-bold">Available</p>
                    </div>
                </div>
<?php }else if (isset($check) == true) { ?>
<?php }else{ ?>
    <h4>Currencies Supported</h4>
        <div class="text-center">
            <?php foreach($methods as $pages) { ?>
                <img width="50" height="50" src="<?= site_url('BSassets/images/currencies/' . strtolower($pages['code']) . '.png') ?>">
            <?php } ?>
        </div>
<?php } ?>
    </div>
</div>