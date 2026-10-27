<div class="bootstrap-wrapper row mt-6 intro-y">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="text-lg mb-4">Find user</h4>
                <form action="<?= site_url('admin/users/find') ?>" method="POST" autocomplete="off">
                    <div class="form-group row mb-4">
                        <label class="col-sm-3 col-form-label">Value</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="user" placeholder="Id, Email, Ip" required>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-3 col-form-label">Type</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="type">
                                <option value="id">ID</option>
                                <option value="email">Email</option>
                                <option value="ip">Ip Address</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">

                    <div class="form-group row justify-content-end">
                        <div class="col-sm-9">
                            <div>
                                <button type="submit" class="btn btn-primary w-md">Find</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="text-lg mb-4">Users</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Email</th>
                                <th scope="col">Ip Address</th>
                                <th scope="col">Referred By</th>
                                <th scope="col">Last Active</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) { ?>
                                <tr>
                                    <th scope="row"><?= $user['id'] ?></th>
                                    <td><?= $user['wallet'] ?></td>
                                    <td class="admin-ip-address"><?= $user['ip_address'] ?></td>
                                    <td><?= $user['referred_by'] ?></td>
                                    <td><?= timespan($user["last_active"], time(), 2) ?> ago</td>
                                    <?= '<td><a href="' . site_url("/admin/users/ban/" . $user['id']) . '" class="btn btn-success btn-sm" title="Unban"><i class="fas fa-check"></i></a><a href="' . site_url("/admin/users/ban/" . $user['id']) . '" class="btn btn-danger btn-sm" title="Ban"><i class="fas fa-ban"></i></a></td>' ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?= $pagination ?>
            </div>
        </div>
    </div>
</div>