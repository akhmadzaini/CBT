<?php $this->load->view('bank/header')?>
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>BROWSE SOAL
        <small>
          Fasilitas lihat soal
        </small>
      </h2>
    </div>
    
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="card">
          <div class="header">
            <h2>
              <?=$soal->ujian_id?> - <?=$soal->judul?>
              <small>Tanggal pelaksanaan : <?=$soal->mulai?></small>
            </h2>
          </div>
          <div class="body">
            <?=$soal->konten?>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</section>
<?php $this->load->view('bank/footer')?>