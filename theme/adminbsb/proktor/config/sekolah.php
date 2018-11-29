<?php $this->load->view('proktor/header')?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>KONFIGURASI SEKOLAH
                    <small>
                        Konfigurasi detil identitas sekolah
                    </small>
                </h2>
            </div>

            <div class="row clearfix">
				<?php if($this->session->pesan !== null): ?>
					<?php if($this->session->pesan == 'sukses'): ?>
					  <div class="alert alert-success">Data sekolah telah diupdate</div>
					<?php endif?>
					<?php if($this->session->pesan == 'gagal_upload'): ?>
					  <div class="alert alert-warning">
					  	Gagal mengunggah logo, pastikan logo memenuhi kriteria berikut :
					  	<ol>
					  		<li>Tipe berkas yang diakui : png</li>
					  		<li>Kapasitas berkas tidak melebihi 512 KB</li>
					  	</ol>
					  </div>
					<?php endif?>
				<?php endif?>
            	<div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                SEKOLAH
                                <small>Isi formulir berikut dengan data yang benar</small>
                            </h2>
                        </div>
                        <div class="body">
                        	<form action="<?=site_url('?d=proktor&c=config&m=submit_sekolah')?>" method="post" enctype="multipart/form-data">
                        		<label>Nama Sekolah</label>
	                            <div class="form-group">
	                                <div class="form-line">
	                                	<input type="text" class="form-control" name="nama_sekolah" placeholder="Nama Sekolah" value="<?=get_app_config('NAMA_SEKOLAH')?>" required="">
	                                </div>
	                            </div>
	                            <label>Logo Sekolah</label>
	                            <div class="form-group">
	                            	<div class="form-line">
			                            <input type="file" name="logo" class="form-control">
	                            	</div>
		                            <small class="help-block">Biarkan kosong, jika tak ingin mengganti logo. Kapasitas logo tidak boleh melebihi 512 KB dan dimensi ukuran yang direkomendasikan adalah 100px x 100px </small>
	                            </div>
	                            <button type="submit" class="btn btn-primary waves-effect">SIMPAN</button>	
                        	</form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
<?php $this->load->view('proktor/footer')?>
