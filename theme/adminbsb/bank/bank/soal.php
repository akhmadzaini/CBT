<?php $this->load->view('bank/header')?>
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>BANK SOAL
        <small>
          Koleksi soal 
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
              BANK SOAL
              <small>Daftar soal tersimpan</small>
            </h2>
          </div>
          <div class="body">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>ID Soal</th>
                  <th>Nama</th>
                  <th>Tindakan</th>
                </tr>
              </thead>
              <tbody>
                <?php $n=1?>
                <?php foreach($soal as $r):?>
                <tr>
                  <td><?=$n++?></td>
                  <td><?=$r->ujian_id?></td>
                  <td><?=$r->judul?></td>
                  <td>
                    <a href="?d=bank&c=bank_soal&m=buka&ujian_id=<?=$r->ujian_id?>" class="btn btn-xs btn-primary waves-effect">
                      <i class="material-icons">open_in_new</i> BUKA
                    </a>
                    <a href="?d=bank&c=bank_soal&m=hapus&ujian_id=<?=$r->ujian_id?>" class="btn btn-xs btn-primary waves-effect">
                      <i class="material-icons">delete</i> HAPUS
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
