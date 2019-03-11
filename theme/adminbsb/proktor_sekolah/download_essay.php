<?php $this->load->view('proktor_sekolah/header')?>
<section class="content">
  <div class="container-fluid">
    
    <div class="block-header">
      <h2>UNDUH TEMPLATE
        <small>
          Fasilitas unduh template nilai
        </small>
      </h2>
    </div>
    
    <div class="col-lg-12">
      <div class="card">
        <div class="header">
          <h2>
            DAFTAR UJIAN
            <small>Berikut ini merupakan daftar ujian yang tersimpan pada database</small>
          </h2>
        </div>
        <div class="body">
          <table class="table table-striped table-hover tabel-ujian dataTable">
            <thead>
              <tr>
                <td>#</td>
                <td>Nama Ujian</td>
                <td>Mulai</td>
                <td>Selesai</td>
                <td>Cetak</td>
              </tr>
            </thead>
            <tbody>
              <?php $n = 1?>
              <?php foreach($ujian as $r):?>
              <tr>
                <td><?=$n++?></td>
                <td><?=$r->judul?></td>
                <td><?=$r->mulai?></td>
                <td><?=$r->selesai?></td>
                <td>
                  <a class="btn-cetak" data-judul="Unduh template nilai essay" data-ujian_id="<?=$r->ujian_id?>" 
                  data-url="<?=site_url('?d=proktor_sekolah&c=nilai&m=do_download_essay&ujian_id=' . $r->ujian_id)?>" 
                  href="javascript:void(0)" data-kelompok="kelompok1"><i class="material-icons" data-toggle="tooltip" data-placement="top" title data-original-title="Template Nilai Essay">file_download</i></a>
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

<div class="modal fade" id="modal-group-cetak" style="display: none;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="defaultModalLabel">Pilihan pengelompokan <span class="modal-data-judul"></span></h4>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead class="kelompok kelompok1">
            <tr>
              <th>#</th>
              <th>Kelas</th>
              <th>Sekolah</th>
              <th>Cetak</th>
            </tr>
          </thead>
          <tbody class="kelompok kelompok1">
            <?php $n=1?>
            <?php foreach($kelompok as $r):?>
            <tr>
              <td><?=$n++?></td>
              <td><?=$r->kelas?></td>
              <td><?=$r->nama_sekolah?></td>
              <td>
                <a href="javascript:void(0)" class="btn-cetak-detail" data-url="" data-kelas="<?=$r->kelas?>">
                  <i class="material-icons">print</i>
                </a>
              </td>
            </tr>
            <?php endforeach?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">SELESAI</button>
      </div>
    </div>
  </div>
</div>


<?php $this->load->view('proktor_sekolah/footer')?>
<script>
  $(function(){
    $('[data-toggle="tooltip"]').tooltip({
      container: 'body'
    });

    // klik tombol cetak
    $(document).on('click', '.btn-cetak', function() {
      var kelompok = $(this).data('kelompok');
      $('.modal-data-judul').text($(this).data('judul'));
      $('.btn-cetak-detail').data('url', $(this).data('url'));
      $('.kelompok').hide();
      $('.' + $(this).data('kelompok')).show();
      $('#modal-group-cetak').modal('show');
    });

    // klik detail cetak
    $(document).on('click', '.btn-cetak-detail', function() {
      var url = '';
      $.each( $(this).data(), function( key, value ) {
        if(key == 'url'){
          url = url + value;
        }
        else{
          url = url + '&' + key + '=' + value;
        }
      });
      window.open(
        url,
        '_blank' 
      );
    });
  });
</script>