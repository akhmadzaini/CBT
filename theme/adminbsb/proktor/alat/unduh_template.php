<?php $this->load->view('proktor/header')?>

<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>ALAT UNDUH TEMPLATE
        <small>
          Unduh template peserta dan soal ujian
        </small>
      </h2>
    </div>
    
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="card loader">
          <div class="header">
            <h2>
              UNDUH TEMPLATE 
              <small>Template untuk guru</small>
            </h2>
          </div>
          <div class="body">
            <p>File template terdiri dari dua file, yakni template peserta dan template soal ujian. 
              Template peserta (Excel) berisi data konfigurasi (setting) ujian, sedangkan 
              template ujian (Word) yang berisi soal, kunci jawaban dan skor yang dikemas dalam file ms word.</p>
            <p><a href="./template-guru/template-ujian&peserta.xlsm" class="btn btn-primary waves-effect" target="_blank">Unduh Template Excel</a></p>
            <p><a href="./template-guru/template-soal.docm" class="btn btn-primary waves-effect" target="_blank">Unduh Template Word</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <?php $this->load->view('proktor/footer')?>