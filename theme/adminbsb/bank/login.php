
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sign In | Ujian Daring</title>
    <!-- Favicon-->
    <link rel="icon" href="<?=base_url('theme/adminbsb/')?>favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="<?=base_url('theme/adminbsb/')?>css/roboto.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url('theme/adminbsb/')?>css/icon.css" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?=base_url('theme/adminbsb/')?>plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?=base_url('theme/adminbsb/')?>plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?=base_url('theme/adminbsb/')?>plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?=base_url('theme/adminbsb/')?>css/style.css" rel="stylesheet">
</head>

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Login<b>BANK SOAL</b></a>
            <small>Sistem Ujian Dalam Jaringan</small>
        </div>
          <?php if($this->session->pesan !== null): ?>
            <?php if($this->session->pesan == 'login_gagal'): ?>
              <div class="alert alert-danger">Login gagal (login atau password salah)</div>
            <?php endif?>
            <?php if($this->session->pesan == 'logout_sukses'): ?>
              <div class="alert alert-success">Anda telah keluar dari sistem ujian.</div>
            <?php endif?>
            <?php if($this->session->pesan == 'harus_login'): ?>
              <div class="alert alert-danger">Anda harus login terlebih dahulu.</div>
            <?php endif?>
          <?php endif?>
        <div class="card">
            <div class="body">
                <form  method="post" action="<?=site_url('?c=login&m=submit_login_bank')?>">
                    <div class="msg">Silahkan login untuk memulai</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="login" placeholder="Login" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN IN</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="<?=base_url('theme/adminbsb/')?>plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?=base_url('theme/adminbsb/')?>plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?=base_url('theme/adminbsb/')?>plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="<?=base_url('theme/adminbsb/')?>plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="<?=base_url('theme/adminbsb/')?>js/admin.js"></script>
    <script src="<?=base_url('theme/adminbsb/')?>js/pages/examples/sign-in.js"></script>
</body>

</html>