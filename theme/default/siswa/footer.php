<footer>
<p>© Copyright 2018, DINAS PENDIDIKAN, PEMUDA DAN OLAHRAGA KOTA PROBOLINGGO</p>
</footer>
<script src="<?=base_url('theme/default/js/jquery.min.js')?>"></script>
<script src="<?=base_url('theme/default/js/bootstrap.min.js')?>"></script>
<!-- plugin sweetalert -->
<script src="<?=base_url('theme/adminbsb/')?>plugins/sweetalert/sweetalert.min.js"></script> 
<!-- plugin sweetalert -->
<script src="<?=base_url('theme/adminbsb/')?>plugins/waitme/waitMe.min.js"></script> 
</body>

<script>
    $(function(){
        $(document).on('click', '.tombol_logout', function(evt){
            evt.preventDefault();
            swal({
                title: "Anda yakin ?",
                text: "Jika anda sedang mengerjakan soal, maka akan dianggap mengakhiri sesi ujian, jika belum, status anda akan dianggap belum melaksanakan ujian. Apakah anda yakin ingin keluar ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Saya ingin keluar",
                cancelButtonText: "Tidak",
                closeOnConfirm: false                
            },function(keluar){
                if(keluar){
                    location.href = "<?=site_url('?c=login&m=logout_siswa')?>";
                }
            });
        });
    });
</script>
</html>

