<?php $this->load->view('proktor/header')?>

  <section class="content">
    <div class="container-fluid">

      <div class="block-header">
        <h2>ALAT CEK INTERNET
          <small>
              Fasilitas pemeriksaan koneksi internet
          </small>
        </h2>
      </div>

      <div class="row clearfix loader">
        <div class="col-lg-12">
          <div class="card">

            <div class="header">
              <h2>
                  PERIKSA KONEKSI SAYA
              </h2>
            </div>

            <div class="body">
              <p>
              Cek koneksi internet digunakan untuk memeriksa ketersediaan koneksi internet dari server. Pemeriksaan 
              koneksi dilakukan hanya pada layanan http saja (port 80).
              </p>
              <button class="btn btn-primary waves-effect btn-cek">PERIKSA KONEKSI</button>
            </div>

          </div>
        </div>
      </div>

    </div>
  </section>

<?php $this->load->view('proktor/footer')?>

<script>
$(function() {
  $('.btn-cek').on('click', function() {
    $('.loader').waitMe();
    $.get('?d=proktor&c=alat&m=do_cek_koneksi_inet', function(hasil){
      if(hasil.is_conn) {
        swal({
              title: "Cek koneksi",
              text: "Server telah terhubung ke jaringan internet",
              type: "success"
          });
      }else{
        swal({
              title: "Cek koneksi",
              text: "Server tidak terhubung ke jaringan internet",
              type: "error"
          });
      }
      $('.loader').waitMe('hide');
    });
  });
});
</script>