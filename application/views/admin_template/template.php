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
    <meta name=”robots” content=”noindex,nofollow”>
    <title><?= $page ?> | <?= $settings['name'] ?></title>
    <!-- Custom fonts for this template-->
    <link href="<?= base_url() ?>BSassets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="<?= base_url() ?>BSassets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>BSassets/css/custom.css" rel="stylesheet">
    <script src='<?= base_url() ?>assets/js/webjs/sweetalert.min.js'></script>
  </head>
  <body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
      <!-- Sidebar -->
      <?php if (isset($_SESSION['admin'])) { ?>
      <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
          <div class="sidebar-brand-icon">
            <img height="50" src="<?= base_url() ?>BSassets/images/logo.png" alt="Logo">
          </div>
          <div class="sidebar-brand-text mx-3">
              <?= $settings['name'] ?>
          </div>
        </a>
        <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
          <a class="nav-link" href="<?= site_url('admin/overview') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Overview</span>
          </a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider">
        <li class="nav-item active">
          <a class="nav-link" href="<?= site_url('admin/currencies') ?>">
            <i class="fas fa-fw fa-dollar-sign"></i>
            <span>Manage Currency</span>
          </a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider">
        <li class="nav-item active">
          <a class="nav-link" href="<?= site_url('admin/faucet') ?>">
            <i class="fas fa-fw fa-faucet"></i>
            <span>Faucet Settings</span>
          </a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider">
        <li class="nav-item active">
          <a class="nav-link" href="<?= site_url('admin/links') ?>">
            <i class="fas fa-fw fa-link"></i>
            <span>Shortlinks Settings</span>
          </a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider">
        <li class="nav-item active">
          <a class="nav-link" href="<?= site_url('admin/users') ?>">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span>
          </a>
        </li>
        <!-- Divider -->
        <li class="nav-item active">
          <a class="nav-link" href="<?= site_url('admin/users/banned') ?>">
            <i class="fas fa-fw fa-users-slash"></i>
            <span>Banned Users</span>
          </a>
        </li>
        <!-- Divider -->
        <li class="nav-item active">
          <a class="nav-link" href="<?= site_url('admin/users/same_ip') ?>">
            <i class="fas fa-fw fa-user-shield"></i>
            <span>Same Ip Users</span>
          </a>
        </li>
      </ul>
      </ul>
      <?php } ?>
      <!-- End of Sidebar -->
      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
          <!-- Topbar -->
          <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
              <i class="fa fa-bars"></i>
            </button>
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
            <?php if (isset($_SESSION['admin'])) { ?>
              <!-- Nav Item - User Information -->
              <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-user-circle fa-2x"></i>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout </a>
                </div>
              </li>
            <?php } ?>
            </ul>
          </nav>
          <!-- End of Topbar -->
          <!-- Begin Page Content -->
          <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800"><?= $page ?></h1>
            </div>
            <!-- Content Row -->
            <?php if (isset($_SESSION['message'])) {echo $_SESSION['message'];}if (isset($_SESSION['sweet_message'])) {echo $_SESSION['sweet_message'];} ?> 
            <?= $contents ?>
          </div>
          <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
        <!-- Footer -->
        <footer class="sticky-footer bg-white">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span id="copyright"> &copy; <script>document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))</script>, <a href="<?= base_url() ?>"> <?php echo $settings['name'] ?></a> | Powered by <a href="https://ourtecads.com" target="_blank">Ourtecads</a>.</span>
            </div>
          </div>
        </footer>
        <!-- End of Footer -->
      </div>
      <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="<?= site_url('auth/logout') ?>">Logout</a>
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
    <script type="text/javascript">
        var site_url = "<?= base_url() ?>";
    </script>
    <script src="<?= base_url() ?>assets/js/webjs/captcha.js"></script>
    <?php if (isset($antibot_js)) { ?>
        <?= $antibot_js ?>
        <script src="<?= base_url() ?>assets/js/webjs/antibotlinks.js"></script>
    <?php } ?>
    <?php if (isset($_COOKIE['captcha'])) { ?>
        <script>
            $('option[value=<?= $_COOKIE['captcha'] ?>]').attr('selected', 'selected');
        </script>
    <?php } ?>    
  </body>
</html>