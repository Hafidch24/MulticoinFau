<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Faucet settings</h4>
                <form action="<?= site_url('admin/update/faucet') ?>" method="POST">
                
                    <div class="form-group row mb-4">
                        <label class="col-sm-3 col-form-label">Faucet Status</label>
                        <div class="col-sm-9">
                            <select class="form-control form-control-sm" name="claim_status" id="claim_status">
                                <option value="on" <?= ($settings['claim_status'] == 'on') ? 'selected' : '' ?>>On</option>
                                <option value="off" <?= ($settings['claim_status'] == 'off') ? 'selected' : '' ?>>Off</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-3 col-form-label">Top ad</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="6" name="faucet_top_ad"><?= $settings['faucet_top_ad'] ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-3 col-form-label">Left ad</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="6" name="faucet_left_ad"><?= $settings['faucet_left_ad'] ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-3 col-form-label">Right ad</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="6" name="faucet_right_ad"><?= $settings['faucet_right_ad'] ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-3 col-form-label">Footer ad</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="6" name="faucet_footer_ad"><?= $settings['faucet_footer_ad'] ?></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">

                    <div class="form-group row justify-content-end">
                        <div class="col-sm-9">
                            <div>
                                <button type="submit" class="btn btn-primary w-md">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>