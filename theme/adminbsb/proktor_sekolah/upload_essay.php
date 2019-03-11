<?php $this->load->view('proktor_sekolah/header')?>

<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>ALAT UNGGAH NILAI ESSAY
        <small>
          Unggah nilai essay berdasarkan template
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
        <div class="card loader">
          <div class="header">
            <h2>
              UNGGAH NILAI 
              <small>Daftar ujian yang tersimpan di database</small>
            </h2>
          </div>
          <div class="body">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Ujian</th>
                  <th>Tanggal</th>
                  <th>Unggah nilai</th>
                </tr>
              </thead>
              <tbody>
                <?php $n=1?>
                <?php foreach($ujian as $r):?>
                  <tr>
                    <td><?=$n++?></td>
                    <td><?=$r->judul?></td>
                    <td><?=$r->tgl?></td>
                    <td>
                      <form class="form-inline" method="post" action="?d=proktor_sekolah&c=nilai&m=submit_upload_essay" enctype="multipart/form-data">
                        <div class="form-group">
                          <input type="hidden" name="ujian_id" value="<?=$r->ujian_id?>">
                          <input type="file" name="excel" class="form-control" required>
                          <input type="submit" value="unggah" class="btn btn-primary">
                        </div>
                      </form>
                    </td>
                  </tr>
                <?php endforeach?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <?php $this->load->view('proktor_sekolah/footer')?>