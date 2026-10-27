<?php if($check == false) { ?>
<div class="text-center row">
    <div class="col-12 col-md-8 col-lg-6 order-md-2 mb-4 text-center">
            <div class="ads">
<?= $settings['faucet_top_ad'] ?>
            </div>
<div class="row">
    <?php
    foreach ($availableLinks as $link) { ?>
        <div class="col-sm-6">
            <div class="card card-body text-center">
                <h4 class="card-title mt-0"><?= $link['name'] ?></h4>
                <p class="card-text">Earn <?= number_format($link['reward']/$this_page['price'],8) ?> <?= $this_page['code'] ?> </p>
                <a href="<?= base_url() . 'links/check/' . $link['id'] . '/'.$this_page['code'] ?>" class="btn btn-primary waves-effect waves-light" <?= ($link['rmnViews'] <= 0) ? 'disabled' : '' ?>>Claim <span class="badge badge-info"><?= $link['rmnViews'] ?>/<?= $link['view_per_day'] ?></span></a>
            </div>
        </div>
    <?php } ?>
</div>
<?php if (!count($availableLinks)) {
    echo '<div class="alert alert-warning text-center">There is no link left <i class="far fa-sad-cry fa-2x"></i> <i class="far fa-sad-cry fa-2x"></i> <i class="far fa-sad-cry fa-2x"></i></div>';
}
?>
      <div class="alert alert-success text-center">Invite your friends and earn <?= $settings['referral'] ?>% of their earning. Your referral links is: <code><?= site_url('/?r=' . $user['id']) ?></code></div>
      <p class="text-muted font-weight-medium">Referrals: <?= $referralCount ?></p>
    </div>
    <div class="col-6 col-md-2 col-lg-3 order-md-1 p-0 left-ads"><?= $settings['faucet_left_ad'] ?></div>
    <div class="col-6 col-md-2 col-lg-3 order-md-3 p-0 right-ads"><?= $settings['faucet_right_ad'] ?></div>
</div>
<div class="ads">
  <?= $settings['faucet_footer_ad'] ?>
</div> 
<div class="card">
    <div class="card-body">
         <h3>Payout History</h3>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">FaucetPay Email</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($withdrawals_history as $value) {
$string = $value["wallet"];
$output = substr($string, 0, 2) . "******" . substr($string, 8, 50);                                        
                                        echo '<tr><th scope="row">' . $value["id"] . '</th><td>' . $output . '</td><td>' . number_format($value["amount"],8) . ' '.$value['method'].'</td><td>' . timespan($value["claim_time"], time(), 2) . ' ago</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>     
  </div>  
</div>
<?php }else if($check == true){ ?>
<div class="text-center row">
    <div class="col-12 col-md-8 col-lg-6 order-md-2 mb-4 text-center">
            <div class="ads">
                <?= $settings['faucet_top_ad'] ?>
            </div>
    <div class="card">
        <div class="card-body">
            <h5>Complete Captcha to continue</h5>
            <form id="fauform" action="<?= site_url('/links/go/' . $id . '/'.$code) ?>" method="POST">
                <?php if ($settings['antibotlinks'] == 'on') { ?>
                <center><?= $antibot ?></center>
                <?php } ?>
                <input type="hidden" name="<?= $csrf_name ?>" id="token" value="<?= $csrf_hash ?>">
                <input type="hidden" name="token" value="<?= $user['token'] ?>">               
                <center>
                <div style="max-width:300px;">              
                    <?= $captcha_display ?>
                </div>
                </center>
                
<button id="subbutt" class="btn btn-primary">
<i class="far fa-check-circle"></i> Go
</button>                
<script>
$(document).ready(function(){
   $('#subbutt').click(function(){
     $("#fauform").submit();
   });
})
</script>                                        
            </form>
        </div>
    </div>
    </div>
    <div class="col-6 col-md-2 col-lg-3 order-md-1 p-0 left-ads"><?= $settings['faucet_left_ad'] ?></div>
    <div class="col-6 col-md-2 col-lg-3 order-md-3 p-0 right-ads"><?= $settings['faucet_right_ad'] ?></div>
</div>
<div class="ads">
  <?= $settings['faucet_footer_ad'] ?>
</div> 
<?php } ?>