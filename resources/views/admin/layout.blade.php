<!DOCTYPE html>
<html lang="id">

<head>
    <title><?= env('APP_NAME') ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= url('/') ?>/bootstrap-4.6.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= url('/') ?>/toastr/build/toastr.min.css">
    <link rel="stylesheet" href="<?= url('/') ?>/fontawesome-free-6.1.1-web/css/all.min.css">

    <link rel="stylesheet" href="<?= url('/') ?>/DataTables_1/DataTables-1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= url('/') ?>/DataTables_1/Buttons-2.2.2/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="<?= url('/') ?>/daterangepicker-master/daterangepicker.css" />

    <link rel="stylesheet" href="<?= url('/') ?>/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= url('/') ?>/select2-bootstrap4-theme/select2-bootstrap4.min.css">


    <script src="<?= url('/') ?>/jquery-3.6.0.min.js"></script>
    <script src="<?= url('/') ?>/knockout/knockout-3.5.1.js"></script>
    <script src="<?= url('/') ?>/fontawesome-free-6.1.1-web/js/all.min.js"></script>
    <script src="<?= url('/') ?>/bootstrap-4.6.1-dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('/') ?>/bootbox/bootbox.all.js"></script>
    <script src="<?= url('/') ?>/js-loading-overlay/dist/js-loading-overlay.min.js"></script>
    <script src="<?= url('/') ?>/toastr/build/toastr.min.js"></script>
    <script src="<?= url('/') ?>/numeral/min/numeral.min.js"></script>
    <script src="<?= url('/') ?>/currency/curruency.js"></script>
    <!-- Select2 -->
    <script src="<?= url('/') ?>/select2/js/select2.full.min.js"></script>

    <script src="<?= url('/') ?>/DataTables_1/datatables.min.js"></script>
    <script src="<?= url('/') ?>/DataTables_1/DataTables-1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= url('/') ?>/DataTables_1/Buttons-2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="<?= url('/') ?>/DataTables_1/JSZip-2.5.0/jszip.min.js"></script>
    <script src="<?= url('/') ?>/DataTables_1/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script src="<?= url('/') ?>/DataTables_1/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script src="<?= url('/') ?>/DataTables_1/Buttons-2.2.2/js/buttons.html5.min.js"></script>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
    <script type="text/javascript" src="<?= url('/') ?>/daterangepicker-master/moment.min.js"></script>
    <script type="text/javascript" src="<?= url('/') ?>/daterangepicker-master/daterangepicker.js"></script>


</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><?= env('APP_NAME') ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            Item
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= url('admin/item/') ?>">Item</a>
                            <a class="dropdown-item" href="<?= url('admin/category') ?>">Category</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            Kontak
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= url('admin/supplier/') ?>">Supplier</a>
                            <a class="dropdown-item" href="<?= url('admin/customer') ?>">Customer</a>
                        </div>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="<?= url('admin/purchase/') ?>">Purchase</a>
                    </li> -->

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            Purchase
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= url('admin/purchase/') ?>">Purchase</a>
                            <a class="dropdown-item" href="<?= url('admin/retur') ?>">Retur</a>
                        </div>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=url('admin/stock')?>">Stock</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=url('admin/cashflow')?>">Cashflow</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Report</a>
                    </li>

                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            {{ strtoupper( Session::get('username' ) )}}
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= url('admin/password') ?>">Password</a>
                            <a class="dropdown-item" href="<?= url('logout') ?>">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br>
    <div class="container">
        <br><br>
    </div>


    <div class="container">
        <?= $breadcrumb ?>
        <center>
            <h1><?= $page_title ?></h1>
        </center>
        <br>
        <?= $content ?>
    </div>

</body>

</html>