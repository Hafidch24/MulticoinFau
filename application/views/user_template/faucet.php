<h4 class="text-center mb-4">Faucet</h4>
<div class="text-center row">
    <div class="col-12 col-md-8 col-lg-6 order-md-2 mb-4 text-center">
            <div class="ads">
                <?= $settings['faucet_top_ad'] ?>
            </div>
    <?php if (!$limit && !$wait) { ?>     
    <div class="card">
        <div class="card-body"> 
            <form id="fauform" action="<?= site_url('/faucet/verify/'.strtolower($this_page['code'])) ?>" method="POST">
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
            <input type="hidden" name="wallet" class="form-control" value="<?= $user['wallet'] ?>" readonly>             
<button id="subbutt" class="btn btn-primary">
<i class="far fa-check-circle"></i> Claim Now
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
      <div class="alert alert-success text-center">Invite your friends and earn <?= $settings['referral'] ?>% of their earning. Your referral links is: <code><?= site_url('/?r=' . $user['id']) ?></code></div>
      <p class="text-muted font-weight-medium">Referrals: <?= $referralCount ?></p>       
       <?php } ?>    
    </div>
    <div class="col-6 col-md-2 col-lg-3 order-md-1 p-0 left-ads"><?= $settings['faucet_left_ad'] ?></div>
    <div class="col-6 col-md-2 col-lg-3 order-md-3 p-0 right-ads"><?= $settings['faucet_right_ad'] ?></div>
</div>
<div class="ads">
  <?= $settings['faucet_footer_ad'] ?>
</div> 
<div class="card mt-3">
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
                foreach ($withdrawals_history as $value) {$string = $value["wallet"];$output = substr($string, 0, 2) . "******" . substr($string, 8, 50);                                        
                    echo '<tr><th scope="row">' . $value["id"] . '</th><td>' . $output . '</td><td>' . number_format($value["amount"],8) . ' '.$value['method'].'</td><td>' . timespan($value["claim_time"], time(), 2) . ' ago</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>     
  </div>  
</div>                             
<?php $cur = strtolower($this_page['code']); ?>
<script>
const countDown = setInterval(() => {
  if (typeof wait !== 'undefined') {
    if (wait == 0) {
      clearInterval(countDown);
      $(".next-button").html(
        '<a href="<?= site_url() ?>faucet/currency/<?= $cur ?>" class="btn btn-primary">Go Claim</a>'
      );
    }
    let minutes = Math.floor(wait / 60);
    let seconds = wait % 60;
    $("#minute").text(minutes);
    $("#second").text(seconds);
    wait -= 1;
  }
}, 1000);

$(document).ready(function () {
  let timer = 6;
  const counter = setInterval(() => {
    if (timer == 0) {
      $(".claim-button").html(
        '<i class="far fa-check-circle"></i> Claim Now'
      );
      $(".claim-button").removeAttr("disabled");
      clearInterval(counter);
    } else {
      if (timer === 1) {
        $(".claim-button").text(`Please wait ${timer} second`);
      } else {
        $(".claim-button").text(`Please wait ${timer} seconds`);
      }
      --timer;
    }
  }, 1000);
});
</script>
