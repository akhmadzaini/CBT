<?php $this->load->view('proktor_sekolah/header')?>

<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>UNGGAH NILAI
        <small>
          Fasilitas pengunggahan nilai
        </small>
      </h2>
    </div>
    
    <?php if(!empty($this->session->pesan)):?>
    <?=$this->session->pesan?>
    <?php endif?>
    
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="card">
          <div class="header">
            <h2>
              UNGGAH NILAI ESSAY
              <small>Unggah nilai sesuai ID Server</small>
            </h2>
          </div>
          <div class="body">
            <form action="?d=proktor_sekolah&c=nilai&m=submit_upload_essay" enctype="multipart/form-data" method="POST">
              
              <label>Ujian</label>
              <div class="form-group">
                <select name="ujian_id" id="" class="form-control show-tick" required>
                  <option value="">-- Pilih sekolah --</option>
                  <?php foreach($ujian as $r):?>
                  <option value="$r->ujian_id"><?=$r->judul?></option>
                  <?php endforeach?>
                </select>
              </div>
              
              <!-- <label>ID Server</label>
              <div class="form-group">
                <select name="ujian_id" id="" class="form-control show-tick" data-live-search="true" required>
                  <option value="">-- Pilih ID server --</option>
                  <?php foreach($server as $r):?>
                  <option value="$r->server"><?=$r->server?></option>
                  <?php endforeach?>
                </select>
              </div> -->

              <label>Berkas Excel</label>
              <div class="form-group">
                <div class="form-line">
                  <input type="file" name="excel" class="form-control" required>
                </div>
                <small class="help-block">masukkan berkas excel (sesuai template) yang telah berisi nilai </small>
              </div>

              <button type="submit" class="btn btn-primary waves-effect">UNGGAH</button>
              
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php $this->load->view('proktor_sekolah/footer')?>

<script>
  $('.btn-simpan').on('click', function(evt){
    var p1 = $('[name="password"]').val();
    var p2 = $('[name="password2"]').val();
    if(p1 != p2){
      swal({
        title: "Password",
        text: "Password dan konfirmasi password tidak sama"
      });
      return;
    }
    swal({
      title: "Anda yakin ?",
      text: "Anda akan mengubah profil diri anda ?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Ya, Saya yakin",
      cancelButtonText: "Tidak",
      closeOnConfirm: true
    },function(isEdited){
      if(isEdited){
        $('#frm-editor-profil').trigger('submit');
      }
    });        
  });
  
</script>
