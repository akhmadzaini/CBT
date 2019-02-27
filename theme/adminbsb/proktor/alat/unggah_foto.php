<?php $this->load->view('proktor/header')?>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>ALAT UNGGAH FOTO
                    <small>
                        Fasilitas unggah foto siswa
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
                                AKSI UNGGAH FOTO SISWA
                                <small>Baca dengan seksama petunjuk penggunaan</small>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="alert alert-info">
                              Berkas foto yang diunggah harus dikemas dalam bentuk zip (terkompresi). 
                              Berikut beberapa hal yang harus dperhatikan :
                              <ol>
                                <li>File-file foto bertipe jpg.</li>
                                <li>Nama file foto diberi nama sesuai NISN, contoh : 080808111.jpg</li>
                                <li>File-file foto yang dikompresi tidak disimpan didalam folder.</li>
                                <li>Tinggi file foto 400 piksel.</li>
                                <li>Lebar file foto 300 piksel.</li>
                              </ol>
                            </div>

							<form action="?d=proktor&c=alat&m=do_unggah_foto" method="post" enctype="multipart/form-data" id="frm-unggah-foto">
                                <label>Arsip foto</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="file" name="arsip" class="form-control" required>
                                    </div>
                                    <small class="help-block">Pilih arsip foto yang terkompresi dalam format zip</small>
                                </div>
                                <button type="submit" class="btn btn-primary waves-effect btn-unggah">UNGGAH FOTO</button>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $this->load->view('proktor/footer')?>