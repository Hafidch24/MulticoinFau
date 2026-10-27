<div class="bootstrap-wrapper row mt-6 intro-y">
    <div class="col-lg-12">
        <div class="box">
            <div class="p-5">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col"><?= $fraudContent ?></th>
                                <th scope="col">ID</th>
                                <th scope="col">Email</th>
                                <th scope="col">IP Address</th>
                                <th scope="col">Country</th>
                                <th scope="col">Referred By</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $indx = 0;
                            foreach ($users as $fraudContent => $users) {
                                foreach ($users as $ind => $user) {
                                    $country = ($user['isocode'] != 'N/A') ?  $user['country']  : 'N/A';
                                    if ($ind == 0) {
                                        echo '<tr>
                                        <th class="align-middle" rowspan="' . count($users) . '" scope="row">' . $fraudContent . '</th>
                                        <td>' . $user['id'] . '</td>
                                        <td>' . $user['wallet'] . '</td>
                                        <td><a href="' . site_url('admin/users/find?ip=' . $user['ip_address']) . '">' . $user['ip_address'] . ' <i class="fas fa-search"></i></a></td>
                                        <td>' . $country . '</td>
                                        <td>' . $user["referred_by"] . '</td>
                                        <td><a href="' . site_url("/admin/users/ban/" . $user['id']) . '" class="btn btn-success btn-sm" title="Unban"><i class="fas fa-check"></i></a><a href="' . site_url("/admin/users/ban/" . $user['id']) . '" class="btn btn-danger btn-sm" title="Ban"><i class="fas fa-ban"></i></a></td>
                                        </tr>';
                                    } else {
                                        echo '<tr>
                                        <td>' . $user['id'] . '</td>
                                        <td>' . $user['wallet'] . '</td>
                                        <td><a href="' . site_url('admin/users/find?ip=' . $user['ip_address']) . '">' . $user['ip_address'] . ' <i class="fas fa-search"></i></a></td>
                                        <td>' . $country . '</td>
                                        <td>' . $user["referred_by"] . '</td>
                                        <td><a href="' . site_url("/admin/users/ban/" . $user['id']) . '" class="btn btn-success btn-sm" title="Unban"><i class="fas fa-check"></i></a><a href="' . site_url("/admin/users/ban/" . $user['id']) . '" class="btn btn-danger btn-sm" title="Ban"><i class="fas fa-ban"></i></a></td>
                                        </tr>';
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?= $pagination ?>
            </div>
        </div>
    </div>
</div>