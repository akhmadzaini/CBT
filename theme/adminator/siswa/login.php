<!doctype html>
<html>

<head>
  <?php $this->load->view('base_theme_url')?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
  <title>Sign In</title>
  <style>
  #loader {
    transition: all .3s ease-in-out;
    opacity: 1;
    visibility: visible;
    position: fixed;
    height: 100vh;
    width: 100%;
    background: #fff;
    z-index: 90000
  }

  #loader.fadeOut {
    opacity: 0;
    visibility: hidden
  }

  .spinner {
    width: 40px;
    height: 40px;
    position: absolute;
    top: calc(50% - 20px);
    left: calc(50% - 20px);
    background-color: #333;
    border-radius: 100%;
    -webkit-animation: sk-scaleout 1s infinite ease-in-out;
    animation: sk-scaleout 1s infinite ease-in-out
  }

  @-webkit-keyframes sk-scaleout {
    0% {
      -webkit-transform: scale(0)
    }
    100% {
      -webkit-transform: scale(1);
      opacity: 0
    }
  }

  @keyframes sk-scaleout {
    0% {
      -webkit-transform: scale(0);
      transform: scale(0)
    }
    100% {
      -webkit-transform: scale(1);
      transform: scale(1);
      opacity: 0
    }
  }

  </style>
  <link href="<?=base_url('theme/adminator/')?>style.css" rel="stylesheet">
</head>

<body class="app">
  <div id="loader">
    <div class="spinner"></div>
  </div>
  <script>
  window.addEventListener('load', () => {
    const loader = document.getElementById('loader');
    setTimeout(() => {
      loader.classList.add('fadeOut');
    }, 300);
  });

  </script>
  <div class="peers ai-s fxw-nw h-100vh">
    <div class="d-n@sm- peer peer-greed h-100 pos-r bgr-n bgpX-c bgpY-c bgsz-cv" style="background-image:url(<?=base_url('theme/adminator/')?>assets/static/images/bg.jpg)">
      <div class="pos-a centerXY">
        <div class="bgc-white bdrs-50p pos-r" style="width:120px;height:120px"><img class="pos-a centerXY" src="<?=base_url('theme/adminator/')?>assets/static/images/logo.png" alt=""></div>
      </div>
    </div>
    <div class="col-12 col-md-4 peer pX-40 pY-80 h-100 bgc-white scrollable pos-r" style="min-width:320px">
      <?php if(isset(($this->session->pesan))): ?>
        <?php if($this->session->pesan == 'login_gagal'): ?>
          <div class="alert alert-danger">Login gagal (login salah atau login sudah terpakai)</div>
        <?php endif?>
        <?php if($this->session->pesan == 'ujian_tak_tersedia'): ?>
          <div class="alert alert-warning">Ujian tidak tersedia (Belum ada soal atau waktu sudah habis).</div>
        <?php endif?>
        <?php if($this->session->pesan == 'logout_sukses'): ?>
          <div class="alert alert-success">Anda telah keluar dari sistem ujian.</div>
        <?php endif?>
      <?php endif?>
      <h4 class="fw-300 c-grey-900 mB-40">Login</h4>
      <form method="post" action="<?=site_url('?c=login&m=submit_login_siswa')?>">
        <div class="form-group">
          <label class="text-normal text-dark">Username</label>
          <input name="login" type="text" class="form-control" placeholder="JohnDoe" required="">
        </div>
        <div class="form-group">
          <label class="text-normal text-dark">Password</label>
          <input name="password" type="password" class="form-control" placeholder="Password" required="">
        </div>
        <div class="form-group">
          <label class="text-normal text-dark">Ujian</label>
          <select name="ujian_id" id="" class="form-control" required="">
            <option value="">-- Pilih Ujian --</option>
            <?php foreach($arr_ujian_aktif as $r):?>
              <option value="<?=$r->ujian_id?>"><?=$r->judul?></option>
            <?php endforeach?>
          </select>
          <small class="form-text text-muted">Ujian yang berlangsung sesuai dengan waktu saat ini, ujian yang belum berlangsung tidak muncul dalam daftar.</small>
        </div>
        <div class="form-group">
          <div class="peers ai-c jc-sb fxw-nw">
            <div class="peer">
              <button class="btn btn-primary">Login</button>
            </div>
          </div>
        </div>
      </form>
    </div>  
  </div>
  <script type="text/javascript" src="<?=base_url('theme/adminator/')?>vendor.js"></script>
  <script type="text/javascript" src="<?=base_url('theme/adminator/')?>bundle.js"></script>
</body>

</html>
