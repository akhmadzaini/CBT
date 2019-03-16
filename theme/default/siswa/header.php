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
                            <img src="<?=(base_url('assets/') . get_app_config('LOGO_SEKOLAH'))?>?v=<?=string_acak(10)?>">
                        </div>
                        <div style="float: left; margin-left: 10px">
                            <p style="font-size: 125%; margin-bottom: 0; padding-top: 5px">DINAS PENDIDIKAN, PEMUDA DAN OLAHRAGA KOTA PROBOLINGGO</p>
                            <h3 style="margin-top: 0"><?=get_app_config('NAMA_SEKOLAH')?> </h3>
                        </div>
                    </div> <div id="welcome">
                        <div id="avatar">
                        <?php if(file_exists(FCPATH . 'public/foto_siswa/' . $this->session->nis . '.jpg')):?>
                            <img src="<?=base_url('public/foto_siswa/' . $this->session->nis . '.jpg')?>" width="60px" height="60px"
                            style="object-fit: cover">
                        <?php else:?>
                            <img src="<?=base_url('theme/default/avatar.png')?>">
                        <?php endif?>
                        </div>
                        <div id="selamat">
                            <p>Selamat Datang</p>
                            <p><b id="nama_siswa2"><?=$this->session->nama?></b></p>
                            <p>(<b id="userid"><?=$this->session->login?></b>)</p>
                            <p><a href="javascript:void(0)" class="tombol_logout">Logout</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>