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
            RIWAYAT TARIK DATA
            <small>Riwayat tarik data server sekolah</small>
          </h2>
        </div>
        <div class="body">
          <p>
            <a href="?d=proktor&c=log&m=del_sync_tarik" class="btn btn-primary waves-effect">Bersihkan log</a>
          </p>
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>IP</th>
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
                <td><?=$r->id_server?></td>
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
