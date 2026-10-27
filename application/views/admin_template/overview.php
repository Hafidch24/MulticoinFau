<a href="<?= site_url('admin/overview/clear_history') ?>" class="btn btn-danger bttn-lg mb-2">Clear History</a>
<div class="row">
    <div class="col-md-4 mb-3 mb-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="media">
                    <div class="media-body">
                        <p class="lh-1 mb-1 font-weight-bold"><?= $info['totalUser'] ?></p>
                        <p class="mb-0">users</p>
                    </div>
                    <div class="align-self-center">
                            <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3 mb-xl-3">
        <div class="card">
            <div class="card-body">        
                <div class="media">
                    <div class="media-body">
                        <p class="lh-1 mb-1 font-weight-bold"><?= $info['activeToday'] ?></p>
                        <p class="mb-0">users active today</p>
                    </div>
                    <div class="align-self-center">
                            <i class="fas fa-user-check text-primary fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>                
    </div>
    <div class="col-md-4 mb-3 mb-xl-3">
        <div class="card">
            <div class="card-body">        
                <div class="media">
                    <div class="media-body">
                        <p class="lh-1 mb-1 font-weight-bold"><?= $info['registerToday'] ?></p>
                        <p class="mb-0">new users today</p>
                    </div>

                    <div class="align-self-center">
                            <i class="fas fa-user-plus fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>                
    </div>
</div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <!-- begin general -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">General Settings</h3>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('admin/update/overview') ?>" method="post">
                        <div class="form-group">
                            <label for="status">Website Status</label>
                            <select class="form-control" name="status">
                                <option value="on" <?= ($settings['status'] == 'on') ? 'selected' : '' ?>>On</option>
                                <option value="off" <?= ($settings['status'] == 'off') ? 'selected' : '' ?>>Off</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Faucet Name</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="name" value="<?= $settings['name'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Faucet Description</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-newspaper"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="description" value="<?= $settings['description'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Admin Username</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="admin_username" value="<?= $settings['admin_username'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Referral Comission (%)</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                                    </div>
                                    <input type="number" class="form-control" name="referral" value="<?= $settings['referral'] ?>">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>" />
                        <button class="btn btn-success btn-lg btn-block" name="overview"><i class="fas fa-check"></i> Update</button>
                    </form>
                </div>
            </div>
            <!-- end general -->


        </div>
        <div class="col-md-6 mb-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Security Settings</h3>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('admin/update/overview') ?>" method="post">
                        <div class="form-group">
                        <label for="antibot">Antibot</label>
                            <select class="form-control form-control-sm" name="antibotlinks" id="antibotlinks">
                                <option value="on" <?= ($settings['antibotlinks'] == 'on') ? 'selected' : '' ?>>On</option>
                                <option value="off" <?= ($settings['antibotlinks'] == 'off') ? 'selected' : '' ?>>Off</option>
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="firewall"><i class="fas fa-shield-alt"></i> Firewall</label>
                            <select class="form-control" name="firewall">
                                <option value="on" <?= ($settings['firewall'] == 'on') ? 'selected' : '' ?>>On</option>
                                <option value="off" <?= ($settings['firewall'] == 'off') ? 'selected' : '' ?>>Off</option>
                            </select>
                        </div>
                        <small>User has to solve a random captcha after 15-25 minutes!</small>
                        <hr>
                        <div class="form-group">
                            <label for="anti_proxy"><i class="fas fa-shield-alt"></i> Anti Proxy</label>
                            <select class="form-control" name="anti_proxy">
                                <option value="on" <?= ($settings['anti_proxy'] == 'on') ? 'selected' : '' ?>>On</option>
                                <option value="off" <?= ($settings['anti_proxy'] == 'off') ? 'selected' : '' ?>>Off</option>
                            </select>
                        </div>
                        <small>Anti Proxy doesn't need API, and unlimited use! <b>Note: don't use with cloudflare</b></small>
                        <hr>
                        <div class="form-group">
                            <label class="control-label">Captcha fail limit</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-robot"></i></span>
                                    </div>
                                    <input type="number" class="form-control" name="captcha_fail_limit" value="<?= $settings['captcha_fail_limit'] ?>" required>
                                </div>
                            </div>
                        </div>
                        <small>Users are banned if they make too many captcha fails in a row.</small>
                        <hr>
                        <p>Availabe captcha systems: <code>recaptchav3</code>|<code>recaptchav2</code>|<code>solvemedia</code>|<code>hcaptcha</code>, each captcha seperated by <code>|</code></p>

                        <div class="form-group">
                            <label class="control-label">Faucet Captcha</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="faucet_captcha" value="<?= $settings['faucet_captcha'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Recaptcha V3 Site Key</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="recaptcha_v3_site_key" value="<?= $settings['recaptcha_v3_site_key'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Recaptcha V3 Secret Key</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="recaptcha_v3_secret_key" value="<?= $settings['recaptcha_v3_secret_key'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Recaptcha V2 Site Key (Don't work along with Hcaptcha)</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="recaptcha_v2_site_key" value="<?= $settings['recaptcha_v2_site_key'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Recaptcha V2 Secret Key</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="recaptcha_v2_secret_key" value="<?= $settings['recaptcha_v2_secret_key'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Hcaptcha Site Key (Don't work along with Recaptcha V2)</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="hcaptcha_site_key" value="<?= $settings['hcaptcha_site_key'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Hcaptcha Secret Key</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="hcaptcha_secret_key" value="<?= $settings['hcaptcha_secret_key'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Solvemedia C Key</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="c_key" value="<?= $settings['c_key'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Solvemedia V Key</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="v_key" value="<?= $settings['v_key'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Solvemedia H Key</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="h_key" value="<?= $settings['h_key'] ?>">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>" />
                        <button class="btn btn-success btn-lg btn-block" name="overview"><i class="fas fa-check"></i> Update</button>
                    </form>
                </div>
            </div>
            <!-- end services -->
            <!-- begin services -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Login</h3>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('admin/update/overview') ?>" method="POST">
                        <div class="form-group">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-user"></i></span>
                                    </div>
                                    <input type="text" name="username" class="form-control" value="<?= $settings['username'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password" class="form-control" value="" required>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>" />
                        <button class="btn btn-success btn-lg btn-block" name="overview"><i class="fas fa-check"></i> Update</button>
                    </form>
                </div>
            </div>
            <!-- end services -->
        </div>

    </div>