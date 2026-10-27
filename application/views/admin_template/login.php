<?php 
$admin = $this->db->escape_str($this->session->userdata('admin'));
if ($admin) {
return redirect(site_url('admin/overview'));
} ?>
            <div class="row justify-content-center mb-5">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="card-body pt-0">
                            <div class="p-2">
  <?php if($this->session->flashdata('message')): ?>
    <div class="alert alert-danger">
        <?= $this->session->flashdata('message'); ?>
    </div>
<?php endif; ?>
  
                                <form class="form-horizontal" action="<?= site_url('admin/auth/login') ?>" method="POST">

                                    <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">

                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" class="form-control" id="username" placeholder="Enter username">
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter password">
                                    </div>
                                    <?php if (!empty($settings['authenticator_code'])) { ?>
                                        <div class="form-group">
                                            <label for="auth_code">Authenticator Code</label>
                                            <input type="text" name="auth_code" class="form-control" id="auth_code" required>
                                        </div>
                                    <?php } ?>

                                    <center>
                                        <?= $captcha_display ?>
                                    </center>

                                    <div class="mt-3">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Log In</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>