<?php $this->load->view('proktor/header')?>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>ALAT JAM SISTEM
                    <small>
                        Fasilitas koreksi jam
                    </small>
                </h2>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                AKSI KOREKSI JAM SISTEM
                            </h2>
                        </div>
                        <div class="body">
                          <div class="alert alert-warning">
                            Pastikan selisih antara jam sistem dengan jam internet tidak terlalu jauh, contoh selisih yang terlalu jauh (12 jam atau 1 hari).
                            Jika terjadi selisih yang besar, maka atur jam komputer agar sinkron dengan jam internet, kemudian restart VHD ujian
                          </div>
                          <form action="">
                            <label>Jam sistem</label>
                            <div class="form-group">
                                <div class="form-line">
                                  <input type="text" class="form-control" value="<?=date("d M Y H:i:s")?>" disabled>
                                </div>
                            </div>
                            <label>Jam internet</label>
                            <div class="form-group">
                                <div class="form-line">
                                  <input type="text" class="form-control" value="<?=$ntp_time->format('d M Y H:i:s')?>" disabled>
                                </div>
                            </div>
                          </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $this->load->view('proktor/footer')?>

