<?php
$arr_acak = array(
  0 => 'urut',
  1 => 'acak soal saja',
  2 => 'acak soal & jawaban'
);
?>
<?php $this->load->view('proktor/header',array('refresh_time' => 60))?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>DASHBOARD PROKTOR
                    <small>
                        Selayang pandang sistem ujian CBT, Laman ini diperbarui setiap 1 menit sekali
                    </small>
                </h2>
            </div>

            <!-- INFOBOX -->
            <div class="row infobox">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="info-box bg-red hover-expand-effect hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">present_to_all</i>
                        </div>
                        <div class="content">
                            <div class="text">JML. UJIAN</div>
                            <div class="number"><?=$jml_ujian?></div>
                        </div>
                    </div>
                </div>
            
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="info-box bg-pink hover-expand-effect hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">access_time</i>
                        </div>
                        <div class="content">
                            <div class="text">SELESAI</div>
                            <div class="number"><?=$jml_ujian_lalu?> ujian</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="info-box bg-deep-purple hover-expand-effect hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">queue_play_next</i>
                        </div>
                        <div class="content">
                            <div class="text">SELANJUTNYA</div>
                            <div class="number"><?=$jml_ujian_lanjut?> ujian</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="info-box bg-light-blue hover-expand-effect hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">play_circle_filled</i>
                        </div>
                        <div class="content">
                            <div class="text">BERLANGSUNG</div>
                            <div class="number"><?=$jml_ujian_progres?> ujian</div>
                        </div>
                    </div>
                </div>

            </div>  
            <!-- /INFOBOX -->

            <div class="row clearfix">
                <!-- Detail Ujian -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                UJIAN TERBARU
                                <small>Saat ini terdapat <?=$jml_ujian_lanjut?> ujian yang belum terlaksana dan <?=$jml_ujian_progres?> ujian yang sedang berlangsung</small>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Ujian</th>
                                            <th>Mulai</th>
                                            <th>Selesai</th>
                                            <th>Acak</th>
                                            <th>Waktu</th>
                                            <th>Jml. Peserta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $n = 1?>
                                        <?php foreach($ujian_progres as $ujian):?>
                                            <tr>
                                                <td><?=$n++?></td>
                                                <td>
                                                  <a href="?d=proktor&c=monitor&m=peserta&ujian_id=<?=$ujian->ujian_id?>"><?=$ujian->judul?></a> 
                                                  <span class="label bg-pink"><?=$ujian->ujian_id?></span>
                                                  <span class="label bg-lime"><?=$ujian->jml_soal?> soal</span> 
                                                  <span class="label bg-light-blue">berlangsung</span>
                                                </td>
                                                <td><?=mysqldate_to_str($ujian->mulai)?></td>
                                                <td><?=mysqldate_to_str($ujian->selesai)?></td>
                                                <td><?=$arr_acak[$ujian->acak]?></td>
                                                <td><?=$ujian->alokasi?></td>
                                                <td><?=$ujian->jml_peserta?></td>
                                            </tr>
                                        <?php endforeach?>
                                        <?php foreach($ujian_lanjut as $ujian):?>
                                            <tr>
                                                <td><?=$n++?></td>
                                                <td>
                                                  <a href="?d=proktor&c=monitor&m=peserta&ujian_id=<?=$ujian->ujian_id?>"><?=$ujian->judul?></a> 
                                                  <span class="label bg-pink"><?=$ujian->ujian_id?></span>
                                                  <span class="label bg-lime"><?=$ujian->jml_soal?> soal</span> 
                                                  <span class="label bg-deep-puple">selanjutnya</span>
                                                </td>
                                                <td><?=mysqldate_to_str($ujian->mulai)?></td>
                                                <td><?=mysqldate_to_str($ujian->selesai)?></td>
                                                <td><?=$arr_acak[$ujian->acak]?></td>
                                                <td><?=$ujian->alokasi?></td>
                                                <td><?=$ujian->jml_peserta?></td>
                                            </tr>
                                        <?php endforeach?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Akhir Detail Ujian -->

            </div>
        </div>
    </section>
<?php $this->load->view('proktor/footer')?>
