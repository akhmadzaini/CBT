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
                          <form action="">
                            <label>Jam sekarang</label>
                            <div class="form-group">
                                <div class="form-line">
                                  <input type="text" class="form-control" value="<?=date("d M Y h:i:s")?>">
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

<script>
    $(function(){
        $(document).on('click', '.btn-cadang', function(){
            document.location.href="<?=site_url('d=proktor&c=alat&m=do_backup')?>";
        });
    });
</script>
