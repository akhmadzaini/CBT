<?php
define ('THEME_COLOR', get_app_config('THEME_COLOR'));
function is_active($th){
    if($th == THEME_COLOR){
        return 'active';
    }else{
        return '';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <?php if(isset($refresh_time)):?>
        <meta http-equiv="refresh" content = "<?=$refresh_time?>" />
    <?php endif?>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sistem Ujian DARING</title>
    <!-- Favicon-->
    <link rel="icon" href="<?=base_url('theme/adminbsb/')?>favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="<?=base_url('theme/adminbsb/')?>css/roboto.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url('theme/adminbsb/')?>css/icon.css" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?=base_url('theme/adminbsb/')?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?=base_url('theme/adminbsb/')?>plugins/node-waves/waves.min.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?=base_url('theme/adminbsb/')?>plugins/animate-css/animate.min.css" rel="stylesheet" />

    <!-- Waitme CSS -->
    <link href="<?=base_url('theme/adminbsb/')?>plugins/waitme/waitMe.min.css" rel="stylesheet" />
    
    <!-- Sweetalert CSS -->
    <link href="<?=base_url('theme/adminbsb/')?>plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- Bootstrap Select Css -->
    <link href="<?=base_url('theme/adminbsb/')?>plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?=base_url('theme/adminbsb/')?>css/style.min.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?=base_url('theme/adminbsb/')?>css/themes/all-themes.min.css" rel="stylesheet" />

    
</head>

<body class="<?=THEME_COLOR?>">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="javascript:void(0)"><?=get_app_config('NAMA_SEKOLAH')?> </a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">                   
                    <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <!--<img src="<?=base_url('theme/adminbsb/')?>images/user.png" width="48" height="48" alt="User" />-->
                    <img src="<?=(base_url('assets/') . get_app_config('LOGO_SEKOLAH'))?>?v=<?=string_acak(10)?>" alt="Logo" width="100" height="100">
                </div>
                <div class="info-container">
                    <div class="name">
                        Hai
                    </div>
                    <div class="email" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?=$this->session->nama?>
                    </div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="<?=site_url('?d=proktor&c=profil&m=edit')?>"><i class="material-icons">person</i>Profile</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#" class="tombol_logout"><i class="material-icons">input</i>Selesai</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">NAVIGASI UTAMA</li>
                    <li class="<?=css_class_active('dashboard')?>">
                        <a href="<?=site_url('?d=proktor&c=dashboard')?>" class="waves-effect waves-block">
                            <i class="material-icons">home</i>
                            <span>Dashboard</span>
                        </a>
                    </li>                     
                    <li class="<?=css_class_active('config')?>">
                        <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                            <i class="material-icons">settings</i>
                            <span>Konfigurasi</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="<?=css_class_active('sekolah', 'm')?>">
                                <a href="<?=site_url('?d=proktor&c=config&m=sekolah')?>" class=" waves-effect waves-block">Sekolah</a>
                            </li>   
                            <li class="<?=css_class_active('token', 'm')?>">
                                <a href="<?=site_url('?d=proktor&c=config&m=token')?>" class=" waves-effect waves-block">Token</a>
                            </li>                          
                        </ul>
                    </li>                        
                    <li class="<?=css_class_active('monitor')?>">
                        <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                            <i class="material-icons">tv</i>
                            <span>Monitoring</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="<?=css_class_active('ujian', 'm')?>">
                                <a href="<?=site_url('?d=proktor&c=monitor&m=ujian')?>" class=" waves-effect waves-block">Ujian</a>
                            </li>   
                            <li class="<?=css_class_active('peserta', 'm')?>">
                                <a href="<?=site_url('?d=proktor&c=monitor&m=peserta')?>" class=" waves-effect waves-block">Peserta</a>
                            </li>                          
                        </ul>
                    </li>                       
                    <li class="<?=css_class_active('sinkron')?>">
                        <a href="javascript:void(0)" class="menu-toggle waves-effect waves-block">
                            <i class="material-icons">restore_page</i>
                            <span>Sinkronisasi</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="<?=css_class_active('tarik', 'm')?>">
                                <a href="<?=site_url('?d=proktor&c=sinkron&m=tarik')?>" class="waves-effect waves-block">Tarik</a>
                            </li>   
                            <li class="<?=css_class_active('kirim', 'm')?>">
                                <a href="<?=site_url('?d=proktor&c=sinkron&m=kirim')?>" class="waves-effect waves-block">Kirim</a>
                            </li>   
                        </ul>
                    </li>                   
                    <li class="<?=css_class_active('cetak')?>">
                        <a href="<?=site_url('?d=proktor&c=cetak')?>" class="waves-effect waves-block">
                            <i class="material-icons">print</i>
                            <span>Cetak</span>
                        </a>
                    </li>     
                    <li class="<?=css_class_active('alat')?>">
                        <a href="javascript:void(0)" class="menu-toggle waves-effect waves-block">
                            <i class="material-icons">build</i>
                            <span>Alat</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="<?=css_class_active('reset', 'm')?>">
                              <a href="<?=site_url('?d=proktor&c=alat&m=reset')?>" class=" waves-effect waves-block">Reset</a>
                            </li>   
                            <li class="<?=css_class_active('backup', 'm')?>">
                              <a href="<?=site_url('?d=proktor&c=alat&m=backup')?>" class=" waves-effect waves-block">Backup</a>
                            </li>   
                            <li class="<?=css_class_active('restore', 'm')?>">
                              <a href="<?=site_url('?d=proktor&c=alat&m=restore')?>" class=" waves-effect waves-block">Restore</a>
                            </li>
                            <li class="<?=css_class_active('unduh_template', 'm')?>">
                              <a href="<?=site_url('?d=proktor&c=alat&m=unduh_template')?>" class=" waves-effect waves-block">Unduh Template</a>
                            </li>
                            <!-- <li class="<?=css_class_active('optimize', 'm')?>">
                              <a href="<?=site_url('?d=proktor&c=alat&m=optimize')?>" class=" waves-effect waves-block">Optimasi Penyimpan</a>
                            </li>                        -->
                        </ul>
                    </li>                    
                    <li class="<?=css_class_active('profil')?>">
                        <a href="<?=site_url('?d=proktor&c=profil&m=edit')?>" class="waves-effect waves-block">
                            <i class="material-icons">person</i>
                            <span>Edit Profil</span>
                        </a>
                    </li>                        
                    <li class="header">LABELS</li>
                    <li>
                        <a href="#" class="tombol_logout" class="waves-effect waves-block">
                            <i class="material-icons col-red">input</i>
                            <span>Selesai</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; <script>document.write(new Date().getFullYear())</script> <a href="javascript:void(0);">Dinas Pendidikan Kota Probolinggo - Material Design</a>.
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        
        <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                    <ul class="demo-choose-skin">
                        <li data-theme="red" class="<?=is_active('theme-red')?>">
                            <div class="red"></div>
                            <span>Red</span>
                        </li>
                        <li data-theme="pink" class="<?=is_active('theme-pink')?>">
                            <div class="pink"></div>
                            <span>Pink</span>
                        </li>
                        <li data-theme="purple" class="<?=is_active('theme-purple')?>">
                            <div class="purple"></div>
                            <span>Purple</span>
                        </li>
                        <li data-theme="deep-purple" class="<?=is_active('theme-deep-purple')?>">
                            <div class="deep-purple"></div>
                            <span>Deep Purple</span>
                        </li>
                        <li data-theme="indigo" class="<?=is_active('theme-indigo')?>">
                            <div class="indigo"></div>
                            <span>Indigo</span>
                        </li>
                        <li data-theme="blue" class="<?=is_active('theme-blue')?>">
                            <div class="blue"></div>
                            <span>Blue</span>
                        </li>
                        <li data-theme="light-blue" class="<?=is_active('theme-light-blue')?>">
                            <div class="light-blue"></div>
                            <span>Light Blue</span>
                        </li>
                        <li data-theme="cyan" class="<?=is_active('theme-cyan')?>">
                            <div class="cyan"></div>
                            <span>Cyan</span>
                        </li>
                        <li data-theme="teal" class="<?=is_active('theme-teal')?>">
                            <div class="teal"></div>
                            <span>Teal</span>
                        </li>
                        <li data-theme="green" class="<?=is_active('theme-green')?>">
                            <div class="green"></div>
                            <span>Green</span>
                        </li>
                        <li data-theme="light-green" class="<?=is_active('theme-light-green')?>">
                            <div class="light-green"></div>
                            <span>Light Green</span>
                        </li>
                        <li data-theme="lime" class="<?=is_active('theme-lime')?>">
                            <div class="lime"></div>
                            <span>Lime</span>
                        </li>
                        <li data-theme="yellow" class="<?=is_active('theme-yellow')?>">
                            <div class="yellow"></div>
                            <span>Yellow</span>
                        </li>
                        <li data-theme="amber" class="<?=is_active('theme-amber')?>">
                            <div class="amber"></div>
                            <span>Amber</span>
                        </li>
                        <li data-theme="orange" class="<?=is_active('theme-orange')?>">
                            <div class="orange"></div>
                            <span>Orange</span>
                        </li>
                        <li data-theme="deep-orange" class="<?=is_active('theme-deep-orange')?>">
                            <div class="deep-orange"></div>
                            <span>Deep Orange</span>
                        </li>
                        <li data-theme="brown" class="<?=is_active('theme-brown')?>">
                            <div class="brown"></div>
                            <span>Brown</span>
                        </li>
                        <li data-theme="grey" class="<?=is_active('theme-grey')?>">
                            <div class="grey"></div>
                            <span>Grey</span>
                        </li>
                        <li data-theme="blue-grey" class="<?=is_active('theme-blue-grey')?>">
                            <div class="blue-grey"></div>
                            <span>Blue Grey</span>
                        </li>
                        <li data-theme="black" class="<?=is_active('theme-black')?>">
                            <div class="black"></div>
                            <span>Black</span>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- #END# Right Sidebar -->
    </section>