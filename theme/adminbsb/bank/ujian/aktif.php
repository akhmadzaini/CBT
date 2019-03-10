<?php $this->load->view('bank/header')?>
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>SOAL UJIAN AKTIF
        <small>
          Fasilitas periksa ujian
        </small>
      </h2>
    </div>

    <?php
      if(!empty($this->session->pesan)){
        echo $this->session->pesan;
      }
    ?>
    
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="card">
          <div class="header">
            <h2>
              SOAL UJIAN
              <small>Daftar soal ujian aktif saat ini</small>
            </h2>
          </div>
          <div class="body">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>ID Ujian</th>
                  <th>Nama</th>
                  <th>Jml Soal</th>
                  <th>Tindakan</th>
                </tr>
              </thead>
              <tbody>
                <?php $n=1?>
                <?php foreach($ujian as $r):?>
                <tr>
                  <td><?=$n++?></td>
                  <td><?=$r->ujian_id?></td>
                  <td><?=$r->judul?></td>
                  <td><?=$r->jml_soal?></td>
                  <td>
                    <a href="?d=bank&c=ujian&m=buka&ujian_id=<?=$r->ujian_id?>" class="btn btn-xs btn-primary waves-effect">
                      <i class="material-icons">open_in_new</i> BUKA
                    </a>
                    <a href="?d=bank&c=ujian&m=impor&ujian_id=<?=$r->ujian_id?>" class="btn btn-xs btn-primary waves-effect">
                      <i class="material-icons">import_export</i> IMPOR
                    </a>
                  </td>
                </tr>
                <?php endforeach?>
              </tbody>
            </table>            
          </div>
        </div>
      </div>
    </div>
    
  </div>
</section>
<?php $this->load->view('bank/footer')?>
