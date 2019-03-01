<?php $this->load->view('proktor/header')?>

<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Sinkronisasi
        <small>
          Fasilitas sinkronisasi data dengan server lain
        </small>
      </h2>
    </div>
    
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="card">
          <div class="header">
            <h2>
              KIRIM DATA
              <small>Kirim data nilai ke server</small>
            </h2>
          </div>
          <div class="body loader">
            
            <div class="alert alert-warning">
              <strong>Peringatan!</strong> Pengiriman data akan menimpa data nilai sebelumnya pada server remote.
            </div>
            <p>Pengiriman data merupakan tindakan sinkronisasi data nilai ujian pada server remote dengan server lokal.</p>
            
            <form method="post" id="frm-sinkron">
              <label>Alamat server remote</label>
              <div class="form-group">
                <div class="form-line">
                  <input type="text" class="form-control" name="server_remote" placeholder="http://server-remote" value=""" required="">
                </div>
              </div>
              <label>ID server lokal</label>
              <div class="form-group">
                <div class="form-line">
                  <input type="text" class="form-control" name="id_server" placeholder="ID pada server lokal, misal : smp1" value=""" required="">
                </div>
              </div>
              <!-- <button type="submit" class="btn btn-primary waves-effect btn-sinkron">KIRIM</button>	 -->
            </form>

            <table class="table table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>ID ujian</th>
									<th>Nama ujian</th>
									<th>Tgl Ujian</th>
									<th>Tindakan</th>
								</tr>
							</thead>
							<?php $n=1?>
							<tbody>
								<?php foreach($ujian as $r):?>
									<tr>
										<td><?=$n++?></td>
										<td><?=$r->ujian_id?></td>
										<td><?=$r->judul?></td>
										<td><?=$r->mulai?></td>
										<td><a href="javascript:void(0);" class="btn btn-xs btn-primary btn-kirim" data-ujian_id="<?=$r->ujian_id?>">kirim nilai</a></td>
									</tr>
								<?php endforeach?>
								<tr></tr>
							</tbody>
            </table>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php $this->load->view('proktor/footer')?>

<script>
  $(function(){
    $('.btn-kirim').on('click', function(evt){
			// collect data
			var ujian_id = $(this).data('ujian_id');
			var server_remote = $('[name="server_remote"]').val();
			var id_server = $('[name="id_server"]').val();
			if(server_remote.length === 0 || !server_remote.trim() || id_server.length === 0 || !id_server.trim()){
				swal('Alamat server remote dan ID server lokal harus terisi');
				return;
			}

      evt.preventDefault();
      var frm = $(this);
      swal({
        title: "Anda yakin ?",
        text: "Pengiriman data akan menimpa data nilai sebelumnya pada server remote. Anda yakin ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, Tindas data nilai server remote",
        cancelButtonText: "Tidak",
        closeOnConfirm: true                
      },function(tindas){
				if(!tindas){return;}
        var target = '<?=site_url("?d=proktor&c=sinkron&m=do_kirim")?>';
        var data = {
					'ujian_id' : ujian_id,
					'server_remote' : server_remote,
					'id_server' : id_server,
				};

        $('.loader').waitMe();
        $.post(target, data, function(hasil){
          $('.loader').waitMe('hide');
          swal(hasil.rincian.peserta + ' data peserta dan ' + hasil.rincian.peserta_jawaban + ' data jawaban peserta telah terkirim');
        });
      });
      
      
    });
  });
</script>