<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Computer Based Test CBT</title>
    <!-- Bootstrap Core Css -->
    <link href="<?=base_url('theme/default/css/bootstrap.min.css')?>" rel="stylesheet">
    <!-- Custom css -->
    <link href="<?=base_url('theme/default/css/style.css')?>" rel="stylesheet">
    <!-- Waitme CSS -->
    <link href="<?=base_url('theme/adminbsb/')?>plugins/waitme/waitMe.min.css" rel="stylesheet" />
    <!-- Sweetalert CSS -->
    <link href="<?=base_url('theme/adminbsb/')?>plugins/sweetalert/sweetalert.css" rel="stylesheet" />
    
</head>
<body>
    
    <div id="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div id="logo">
                        <div id="logo-container">
                            <img src="<?=(base_url('assets/') . get_app_config('LOGO_SEKOLAH'))?>">
                        </div>
                        <div style="float: left; margin-left: 10px">
                            <p style="font-size: 125%; margin-bottom: 0; padding-top: 5px">DINAS PENDIDIKAN, PEMUDA DAN OLAHRAGA KOTA PROBOLINGGO</p>
                            <h3 style="margin-top: 0"><?=get_app_config('NAMA_SEKOLAH')?> </h3>
                        </div>
                    </div> <div id="welcome">
                        <!--
                        <div id="avatar">
                            <img src="<?=base_url('theme/default/avatar.png')?>">
                        </div>
                        -->
                        <div id="selamat">
                            <p>Selamat Datang</p>
                            <p style="display:none"><b id="nama_siswa">Akhmad Zaini</b></p>
                            <p><b id="nama_siswa2"><?=$this->session->nama?></b></p>
                            <p>(<b id="userid"><?=$this->session->login?></b>)</p>
                            <p><a href="javascript:void(0)" class="tombol_logout">Logout</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>