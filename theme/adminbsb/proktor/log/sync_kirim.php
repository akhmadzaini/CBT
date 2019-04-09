<?php $this->load->view('proktor/header')?>
<style>
  .table-danger {
    background-color: #fb483a !important;
    color: white;
  }
</style>

<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>LOG SINKRON SERVER
        <small>
          Fasilitas periksa log 
        </small>
      </h2>
    </div>
    
    <div class="col-lg-12">
      <div class="card">
        <div class="header">
          <h2>
            RIWAYAT KIRIM DATA
            <small>Riwayat kirim data dari server sekolah</small>
          </h2>
        </div>
        <div class="body">
          <p>
            <a href="?d=proktor&c=log&m=del_sync_kirim" class="btn btn-primary waves-effect">Bersihkan log</a>
          </p>
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>IP</th>
                <th>ID Ujian</th>
                <th>ID Server</th>
                <th>Mulai</th>
                <th>Selesai</th>
              </tr>
            </thead>
            <tbody>
              <?php $n=1?>
              <?php foreach($log as $r):?>
              <?php $cls = empty($r->selesai) ? 'class="table-danger"' : ''?>
              <?php $selesai = empty($r->selesai) ? '--' : date('d M Y h:i:s', $r->selesai)?>
              <tr <?=$cls?>>
                <td><?=$n++?></td>
                <td><?=$r->ip?></td>
                <td><?=(array_key_exists($r->ujian_id, $arr_ujian)? $arr_ujian[$r->ujian_id] : $r->ujian_id)?></td>
                <td><?=(array_key_exists($r->id_server, $arr_sekolah)? $arr_sekolah[$r->id_server] : $r->id_server)?></td>
                <td><?=date('d M Y h:i:s', $r->mulai)?></td>
                <td><?=$selesai?></td>
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
<?php $this->load->view('proktor/footer')?>
