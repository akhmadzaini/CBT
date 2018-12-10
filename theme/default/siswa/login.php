<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Bootstrap Core Css -->
    <link href="<?=base_url('theme/default/css/bootstrap.min.css')?>" rel="stylesheet">
    <!-- Custom css -->
    <link href="<?=base_url('theme/default/css/style.css')?>" rel="stylesheet">
    
    
    <title>Login CBT</title>
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
                    </div> 
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php if($this->session->pesan !== null): ?>
                    <?php if($this->session->pesan == 'login_gagal'): ?>
                    <div class="alert alert-danger">Login gagal (login salah atau login sudah terpakai)</div>
                    <?php endif?>
                    <?php if($this->session->pesan == 'login_reset'): ?>
                    <div class="alert alert-danger">Login anda telah direset</div>
                    <?php endif?>
                    <?php if($this->session->pesan == 'ujian_tak_tersedia'): ?>
                    <div class="alert alert-warning">Ujian tidak tersedia (Belum ada soal atau waktu sudah habis).</div>
                    <?php endif?>
                    <?php if($this->session->pesan == 'logout_sukses'): ?>
                    <div class="alert alert-success">Anda telah keluar dari sistem ujian.</div>
                    <?php endif?>
                <?php else:?>
                    <div style="height:74px;"></div>
                <?php endif?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="loginbox">
                    <div id="logintitle">
                        <p class="text-center">User Login</p>
                    </div>
                    <div id="loginbody">
                        <div style="height :43px"></div>
                        <form method="POST" id="form_login" action="<?=site_url('?c=login&m=submit_login_siswa')?>">
                            <table>
                                <tbody><tr>
                                    <td>User</td>
                                    <td><span class="glyphicon glyphicon-user" aria-hidden="true"></span><input type="text" name="login" id="login" required=""></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Password</td>
                                    <td><span class="glyphicon glyphicon-lock" aria-hidden="true"></span><input type="password" name="password" id="password" required=""></td>
                                    <td><span class="glyphicon glyphicon-eye-open showPassword" aria-hidden="true" id="eye"></span></td>
                                </tr>
                                <tr>
                                    <td>Ujian</td>
                                    <td>
                                        <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span><select name="ujian_id" required="">
                                            <option value="">-- Pilih Ujian --</option>
                                            <?php foreach($arr_ujian_aktif as $r):?>
                                            <option value="<?=$r->ujian_id?>"><?=$r->judul?> - <?=$r->ujian_id?></option>
                                            <?php endforeach?>
                                        </select>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <button type="submit" class="btn btn-success">LOGIN</button>
                                    </td><td></td>
                                </tr>
                            </tbody></table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="<?=base_url('theme/default/js/jquery.min.js')?>"></script>
<script>
    $(function(){
        $('.showPassword').hover(
        function(){
            $('#password')[0].type = "text";
        },
        function(){
            $('#password')[0].type = "password";
        }
        );
    })
</script>
</html>