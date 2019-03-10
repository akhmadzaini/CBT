<?php $this->load->view('proktor/header')?>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>SUNTING PROFIL
                    <small>
                        Fasilitas penyuntingan profil
                    </small>
                </h2>
            </div>

            <?php if($this->session->pesan == 'sukses'):?>
            <div class="alert alert-success">
                Data profil telah tersimpan.
            </div>
            <?php endif?>

            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                SUNTING PROFIL SAYA
                                <small>Edit detail profil saya</small>
                            </h2>
                        </div>
                        <div class="body">
							<form action="?d=bank&c=profil&m=submit_edit" method="post" id="frm-editor-profil">
                                <label>Login</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="login" class="form-control" value="<?=$profil->login?>" disabled>
                                    </div>
                                </div>
                                <label>Nama Lengkap</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="nama" value="<?=$profil->nama?>" placeholder="Nama lengkap" required="">
                                    </div>
                                </div>
                                <label>Alamat Surel</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="email" class="form-control" name="email" value="<?=$profil->email?>" placeholder="Alamat surat elektronik">
                                    </div>
                                </div>
                                <label>Password</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="password" value="">
                                    </div>
                                    <small class="help-block">Biarkan kosong jika tak ingin mengganti password</small>
                                </div>
                                <label>Konfirmasi Password</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="password2" value="">
                                    </div>
                                    <small class="help-block">Ketik ulang password</small>
                                </div>
                                <button type="button" class="btn btn-primary waves-effect btn-simpan">SIMPAN</button>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $this->load->view('proktor/footer')?>

<script>
    $('.btn-simpan').on('click', function(evt){
        var p1 = $('[name="password"]').val();
        var p2 = $('[name="password2"]').val();
        if(p1 != p2){
            swal({
                title: "Password",
                text: "Password dan konfirmasi password tidak sama"
            });
            return;
        }
        swal({
            title: "Anda yakin ?",
            text: "Anda akan mengubah profil diri anda ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Saya yakin",
            cancelButtonText: "Tidak",
            closeOnConfirm: true
        },function(isEdited){
            if(isEdited){
                $('#frm-editor-profil').trigger('submit');
            }
        });        
    });

</script>
