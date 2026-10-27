<div class="row">
    <div class="col-lg-12 mb-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4 text-center">Currency</h4>
                <?php
                if (isset($_SESSION['message'])) {
                    echo $_SESSION['message'];
                }
                ?>
                <form action="<?= site_url('admin/currencies/add') ?>" method="POST" autocomplete="off">
                    <input type="hidden" name="<?= $csrf_name ?>" id="token" value="<?= $csrf_hash ?>">
                    <div class="form-group">
                        <label for="currency_name">Currency Name</label>
                        <select class="form-control" id="currency_name" name="currency_name" onchange="changecode(event)">
                            <option disabled selected>Choose Currency</option>
                            <option data-foo="BNB" value="binancecoin">Binance coin</option>
                            <option data-foo="BTC" value="bitcoin">Bitcoin</option>
                            <option data-foo="BCH" value="bitcoin-cash">Bitcoin cash</option>
                            <option data-foo="DASH" value="dash">Dash</option>
                            <option data-foo="DOGE" value="dogecoin">Dogecoin</option>
                            <option data-foo="DGB" value="digibyte">Digibyte</option>
                            <option data-foo="ETH" value="ethereum">Ethereum</option>
                            <option data-foo="FEY" value="feyorra">Feyorra</option>
                            <option data-foo="LTC" value="litecoin">Litecoin</option>
                            <option data-foo="SOL" value="solana">Solana</option>
                            <option data-foo="TRX" value="tron">Tron</option>
                            <option data-foo="USDT" value="tether">USD Tether</option>
                            <option data-foo="ZEC" value="zcash">Zcash</option>
                        </select>
                    </div>
<script>
function changecode(e) {
var sel = document.getElementById('currency_name');
var selected = sel.options[sel.selectedIndex];
var code = selected.getAttribute('data-foo');
    document.getElementById("code").value = code
}
</script>
                    <div class="form-group">
                        <label for="code">Currency Code</label>
                        <input type="text" class="form-control" id="code" name="code" value="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="api">Faucetpay API</label>
                        <input type="text" class="form-control" id="api" name="api" placeholder="API for this currency">
                    </div>
                    <div class="form-group">
                        <label for="reward">Reward (in USD)</label>
                        <input type="text" class="form-control" id="reward" name="reward" placeholder="Reward">
                    </div>
                    <div class="form-group">
                        <label for="account_number">Timer (in Second)</label>
                        <input type="number" class="form-control" id="timer" name="timer" placeholder="Timer">
                    </div>
                    <div class="form-group">
                        <label for="wallet">Wallet</label>
                        <select class="form-control" id="wallet" name="wallet">
                            <option value="faucetpay">Faucetpay</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="minimum_withdrawal">Limit</label>
                        <input type="number" class="form-control" id="limit" name="limit" placeholder="Limit" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg btn-block">Add</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title mb-4">Currencies</h4>
                <?php foreach ($currencies as $currency) { ?>
                    <form action="<?= site_url('admin/currencies/update/' . $currency['id']) ?>" method="POST">
                        <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
                        <div class="form-row mb-4">
                            <div class="col">
                                <div class="form-group">
                                    <label for="currency_name">Currency Name</label>
                                    <input type="text" class="form-control" name="currency_name" value="<?= $currency['currency_name'] ?>" readonly>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="code">Code</label>
                                    <input type="text" class="form-control" name="code" value="<?= $currency['code'] ?>" readonly>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="api">API</label>
                                    <input type="text" class="form-control" name="api" value="<?= $currency['api'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="col">
                                <div class="form-group">
                                    <label for="api_id">Reward (in USD)</label>
                                    <input type="text" class="form-control" name="reward" value="<?= $currency['reward'] ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="account_number">Timer (in Second)</label>
                                    <input type="number" class="form-control" name="timer" value="<?= $currency['timer'] ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="wallet">Wallet</label>
                                    <select class="form-control" id="wallet" name="wallet">
                                        <option value="faucetpay" <?= ($currency['wallet'] == 'faucetpay') ? 'selected' : '' ?>>Faucetpay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="minimum_withdrawal">Limit</label>
                                    <input type="number" class="form-control" name="limit" value="<?= $currency['limit_claim'] ?>">
                                </div>
                            </div>
                             <div class="col">
                                <div class="form-group">
                                    <label for="minimum_withdrawal">Price</label>
                                    <input type="number" class="form-control" value="<?= $currency['price'] ?>" readonly>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="wallet">Action</label>
                                    <div>
                                        <a type="submit" class="btn btn-danger" href="<?= site_url('admin/currencies/delete/' . $currency['id']) ?>">Delete</a>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-secondary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                <?php } ?>
            </div>
        </div>
    </div>
</div>