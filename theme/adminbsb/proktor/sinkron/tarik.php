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
                                TARIK DATA
                                <small>Tarik data dari server</small>
                            </h2>
                        </div>
                        <div class="body loader">

                           <div class="alert alert-warning">
                                <strong>Peringatan!</strong> Penarikan data akan menyebabkan seluruh data ujian pada server lokal sama persis dengan data ujian pada server remote.
                            </div>
							<p>Penarikan data merupakan tindakan sinkronisasi data ujian server lokal dengan server remote.</p>

                            <form method="post" id="frm-sinkron" action="?d=proktor&c=sinkron&m=do_tarik">
                        		<label>Alamat server remote</label>
	                            <div class="form-group">
	                                <div class="form-line">
	                                	<input type="text" class="form-control" name="server_remote" placeholder="http://server-remote" value=""" required="">
	                                </div>
	                            </div>
                                <label>ID server lokal</label>
	                            <div class="form-group">
	                                <div class="form-line">
	                                	<input type="text" class="form-control" name="id_server" placeholder="ID pada server lokal, misal : smp1" value="">
                                  </div>
                                  <small class="help-block">Biarkan kosong, jika hanya ingin sinkronisasi soal saja </small>
	                            </div>
	                            <button type="submit" class="btn btn-primary waves-effect btn-sinkron">TARIK</button>	
                        	</form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $this->load->view('proktor/footer')?>

<script>
    $(function(){
      $('#frm-sinkron').on('submit', function(evt){
        evt.preventDefault();
        var frm = $(this);
        swal({
            title: "Anda yakin ?",
            text: "Penarikan data akan menyebabkan seluruh data ujian pada server lokal sama persis dengan data ujian pada server remote. anda yakin ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Tindas data server lokal",
            cancelButtonText: "Tidak",
            closeOnConfirm: true                
        },function(tindas){
          if(tindas){
            frm.unbind('submit').submit();
          }
        });

        // $('#frm-sinkron').on('submit', function(evt){
        //     evt.preventDefault();
        //     var frm = $(this);
        //     swal({
        //         title: "Anda yakin ?",
        //         text: "Penarikan data akan menyebabkan seluruh data ujian pada server lokal sama persis dengan data ujian pada server remote. anda yakin ?",
        //         type: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#DD6B55",
        //         confirmButtonText: "Ya, Tindas data server lokal",
        //         cancelButtonText: "Tidak",
        //         closeOnConfirm: true                
        //     },function(tindas){
        //         if(tindas){
        //             const target = '<?=site_url("?d=proktor&c=sinkron&m=do_tarik")?>';
        //             const data = JSON.parse(JSON.stringify(frm.serializeArray())); 
                    
        //             $('.loader').waitMe({text: 'Sinkronisasi server lokal dengan remote, mohon tunggu ... => (1/3)'});
        //             $.post(target, data, function(hasil){
        //                 // console.log(hasil);
        //                 if(hasil.pesan == 'konek_gagal'){
        //                     swal({
        //                         title: "Koneksi gagal",
        //                         text: "Koneksi server lokal dengan server remote gagal",
        //                         type: "warning"
        //                     });
        //                     $('.loader').waitMe('hide');
        //                     return;
        //                 }else if(hasil.pesan == 'token_gagal'){
        //                     swal({
        //                         title: "Token gagal",
        //                         text: "Token server lokal tak dikenali atau server remote tidak tepat sasaran, mohon periksa kembali",
        //                         type: "warning"
        //                     });
        //                     $('.loader').waitMe('hide');
        //                     return;
        //                 }
        //                 $('.loader').waitMe({text: 'Sedang mengunduh data sinkronisasi \
        //                   ('+ data[0].value +'/public/'+ hasil.nama_zip +'). <br>Proses ini memerlukan waktu, \
        //                   bergantung dari kecepatan koneksi... => (2/3)'});
        //                 const target2 = '<?=site_url("?d=proktor&c=sinkron&m=do_tarik_2")?>';
        //                 const data2 = {
        //                   'nama_zip' : hasil.nama_zip,
        //                   'server_remote' : data[0].value
        //                 }
        //                 $.post(target2, data2, function(hasil){
        //                   if(hasil.pesan == 'ok'){
        //                     $('.loader').waitMe({text: 'Sedang menerapkan data sinkronisasi ... => (3/3)'});
        //                     const target3 = '<?=site_url("?d=proktor&c=sinkron&m=do_restore")?>';
        //                     const data3 = {
        //                       'arsip_sinkron' : data2.nama_zip
        //                     }
        //                     $.post(target3, data3, function(hasil){
        //                       $('.loader').waitMe('hide');
        //                       swal({
        //                         title: "Sukses",
        //                         text: "Proses sinkronisasi telah selesai",
        //                         type: "info"
        //                       });
        //                     });
        //                   }
        //                 });
        //             });
        //         }
        //     });

            
        }); 
    });
</script>