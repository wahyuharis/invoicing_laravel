<!DOCTYPE html>
<html lang="id">

<head>
    <title><?= env('APP_NAME') ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= url('/') ?>/bootstrap-4.6.1-dist/css/bootstrap.min.css">
    <script src="<?= url('/') ?>/jquery-3.6.0.min.js"></script>
    <script src="<?= url('/') ?>/bootstrap-4.6.1-dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container">


        <div class="row">
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
                <br><br>
                <h2>Login <?= env('APP_NAME') ?></h2>

                <form method="post" action="<?=url('login_submit')?>">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="" placeholder="Enter email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" value="" placeholder="Enter password" name="pswd">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="col-md-4">

            </div>
        </div>


    </div>

</body>

</html>