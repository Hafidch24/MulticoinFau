<div class="ads">
  <?= $settings['faucet_top_ad'] ?>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
             <h3>All Payout History</h3>
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
                        <?php foreach ($his as $value) {$string = $value["wallet"];$output = substr($string, 0, 2) . "******" . substr($string, 8, 50);                                        
                        echo '<tr><th scope="row">' . $value["id"] . '</th><td>' . $output . '</td><td>' . number_format($value["amount"],8) . ' '.$value['method'].'</td><td>' . timespan($value["claim_time"], time(), 2) . ' ago</td></tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>     
            </div>  
        </div> 
    </div>
</div>
<div class="ads">
  <?= $settings['faucet_footer_ad'] ?>
</div>
