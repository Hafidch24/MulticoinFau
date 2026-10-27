<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url() ?>BSassets/images/favicon.png">
    <link rel="icon" type="image/png" href="<?= base_url() ?>BSassets/images/favicon.png">
    <meta name="description" content="<?= $settings['description'] ?>">
    <meta name="author" content="MrqRama - Ourtecads">
    <title><?= $page ?> | <?= $settings['name'] ?></title>
    <!-- Custom fonts for this template-->
    <link href="<?= base_url() ?>BSassets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="<?= base_url() ?>BSassets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>BSassets/css/custom.css" rel="stylesheet">
  </head>
  <body class="bg-gradient-primary">
    <div class="container">
      <!-- Outer Row -->
        <div class="ads">
            <?= $settings['faucet_top_ad'] ?>
        </div>
      <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
          <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
              <!-- Nested Row within Card Body -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="p-5">
                    <div class="text-center">
                      <h1 class="h4 text-gray-900 mb-4">Login With Your FaucetPay Email</h1>
                    </div>
                    <?php
                        if (isset($_SESSION['message'])) {
                            echo $_SESSION['message'];
                        }
                    ?>
                    <form class="user" action="<?= site_url('/auth/login') ?>" method="POST">
                      <div class="form-group">
                        <input type="email" name="wallet" class="form-control" placeholder="FaucetPay Email Address" class="form-control form-control-user" id="InputEmail" aria-describedby="emailHelp">
                      </div>
                      <input type="hidden" name="<?= $csrf_name ?>" id="token" value="<?= $csrf_hash ?>">
                      <button type="submit" class="btn btn-primary btn-user btn-block"> Login </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url() ?>BSassets/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url() ?>BSassets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="<?= base_url() ?>BSassets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="<?= base_url() ?>BSassets/js/sb-admin-2.min.js"></script>
  </body>
</html>