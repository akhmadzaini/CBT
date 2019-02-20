<?php $this->load->view('siswa/header')?>
<?php $soal_json = json_encode($soal ) ?>
<link rel="stylesheet" href="<?=base_url('theme/adminbsb/')?>plugins/font-awesome/css/font-awesome.min.css">
<div class="container-fluid">
  
  <div class="row" id="app-vue">
    <div class="col-md-12">
      <div id="soal">
        <div id="soal-head" class="">
          <div id="nomor">
            SOAL NO <span>{{idxSoal+1}}</span>
          </div>
          <div id="waktu">
            <span>Sisa Waktu</span>
            <span class="sisa">{{sisaWaktu}}</span>
          </div>
          <div class="clear"></div>
        </div>
        <div id="font-size">
          Ukuran font soal : <span class="a1">A</span><span class="a2">A</span><span class="a3">A</span>
        </div>
        <div id="soal-body" style="display: block;">
          <div class="soal active">
            <table>
              <tr>
                <td>
                  <div class="isi-soal" v-html="soalJson[idxSoal].konten"></div>
                </td>
              </tr>
              <tr>
                <td>
                  <div v-if="(soalJson[idxSoal].essay != 1)" class="options-group">
                    <table width="100%">
                      <tr v-for="pilihan, idx in soalJson[idxSoal].pilihan_jawaban">
                        <td>
                          <div class="options" @click="jawabPilihanGanda(pilihan.pilihan_ke)">
                            <span :class="(pilihan.pilihan_ke == soalJson[idxSoal].pilihan) ? 'option checked' : 'option'" >
                              <span class="inneroption"> {{String.fromCharCode(65 + idx)}} </span>
                            </span>
                            <p><span v-html="pilihan.konten"></span></p>
                          </div>                            
                        </td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
            </table>
            <div v-if="(soalJson[idxSoal].essay == 1)">
              <!-- <wysiwyg v-model="soalJson[idxSoal].jawaban_essay" /> -->
              <vue-html5-editor :content="soalJson[idxSoal].jawaban_essay" :height="150" @change="updateData" ref="editor"></vue-html5-editor>
            </div>
            <div v-if="(soalJson[idxSoal].essay == 1)">
              <p>&nbsp;</p>
              <button class="btn btn-primary btn-simpan-essay">Simpan Jawaban</button> 
            </div>
          </div>
        </div>               
        <div id="soal-foot">
          <table width="100%">
            <tbody><tr>
              <td align="left">
                <button type="button" id="btn-prev" class="btn btn-primary" :disabled="idxSoal == 0">
                  <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span> SOAL SEBELUMNYA
                </button>
              </td>
              <td align="center">
                <button type="button" class="btn btn-warning btn-ragu" :disabled="soalJson[idxSoal].pilihan == null">
                  <span :class="(soalJson[idxSoal].ragu != 1) ? 'glyphicon glyphicon-unchecked' : 'glyphicon glyphicon-check'" aria-hidden="true"></span> RAGU - RAGU
                </button></td>
                <td align="right">
                  <button type="button" id="btn-next" class="btn btn-primary" :style="(idxSoal == (soalJson.length - 1)) ? 'display:none' : ''">SOAL BERIKUTNYA 
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                  </button>
                </td>
                <td align="right">
                  <button :style="(idxSoal == (soalJson.length - 1)) ? '' : 'display:none'" type="button" id="btn-kumpul" class="btn btn-primary ">KUMPULKAN JAWABAN
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    <div id="summary-button" class="" style="right: 0px;">
      <button type="button" class="btn btn-danger"><span class="summary-nav glyphicon glyphicon-menu-left" style="position:relative; top:10px"></span> <span class="cpt">Daftar <br>Soal</span></button>
    </div>
    
    <div id="summary" style="display: none;">
      <div v-for="soal, idx in soalJson" :class="cssSummary(idx)" :data-idx="idx">
        <p>{{idx+1}}</p>
        <span v-if="soal.essay != 1"><span class="inneroption">{{pilihan_display(soal.pilihan, soal.pilihan_jawaban)}}</span></span>
      </div>            
    </div>
  </div>
  
</div>


<?php $this->load->view('siswa/footer')?>
<!-- plugin vue -->
<script src="<?=base_url('theme/adminbsb/')?>plugins/vue/vue.js"></script> 
<script src="<?=base_url('theme/global-plugins/vue-html5-editor/')?>vue-html5-editor.js"></script> 


<script>
  $(function(){
    
    Vue.use(VueHtml5Editor, {});
    var vueApp = new Vue({
      el: '#app-vue',
      data: {
        idxSoal: 0,
        sisaWaktu: '--:--',
        soalJson : <?=$soal_json?>,
        cssSummary : function(idx){
          if(this.soalJson[idx].essay != 1){
            var done = (this.soalJson[idx].pilihan == null) ? 'not-done ' : 'done ';
            var active = (this.soalJson[idx].ragu != '1' && this.soalJson[idx].pilihan != null) ? 'active ' : ' ';
            var ragu = (this.soalJson[idx].ragu == '1') ? ' ragu-ragu ' : '';
            return 'no ' + done + active + ragu;
          }else{
            var done = (Boolean(this.soalJson[idx].jawaban_essay)) ? 'done active ': 'not-done ';
            return 'no ' + done;
          }
        },
      },
      methods: {
        updateData: function (data) {
          this.soalJson[this.idxSoal].jawaban_essay = data;
        },
        pilihan_display: function(pilihan, pilihan_jawaban){
          var hasil = '';
          pilihan_jawaban.forEach(function(item, index){
            if(item.pilihan_ke == pilihan){
              hasil = String.fromCharCode(65 + index);
            }
          });
          return hasil;
        },
        jawabPilihanGanda: function (jawaban) {
          var data = {
            no_soal : vueApp.soalJson[vueApp.idxSoal].no_soal,
            pilihan : jawaban
          }
          $('#soal').waitMe();
          $.post("<?=site_url('?d=siswa&c=ujian&m=submit_jawaban')?>", data, function(hasil){
            if(hasil == 'ok'){
              $("#soal").waitMe('hide');
              this.soalJson[this.idxSoal].pilihan = jawaban;
            }
          }.bind(this));
        }
      }
    });

    $('body').on('keypress', function (e) {
      var key = String.fromCharCode(e.which).toUpperCase();
      var acceptedKey = ['A', 'B', 'C', 'D'];
      if(acceptedKey.includes(key)){
        vueApp.jawabPilihanGanda(key);
      }
    })
    
    // Klik Next
    $('#btn-next').on('click', function(){
      if(vueApp.idxSoal < vueApp.soalJson.length - 1){
        bukaSoal(vueApp.idxSoal + 1);
      }
    });
    
    // Klik Prev
    $('#btn-prev').on('click', function(){
      if(vueApp.idxSoal > 0){
        bukaSoal(vueApp.idxSoal - 1);
      }
    });
    
    // Klik kumpulkan jawaban
    $('#btn-kumpul').on('click', function(){                              
      if(adaRagu()){
        swal({
          title: "Terdapat Jawaban Ragu !!!",
          text: "Anda tidak bisa menyelesaikan ujian sekarang, terdapat jawaban yang berstatus ragu-ragu",
          type: "warning",
        });
        return;
      }
      if(vueApp.sisaWaktu != "KADALUARSA"){
        swal({
          title: "Anda yakin ?",
          text: "Periksa seluruh jawaban sebelum menyelesaikan ujian. \
          Masih ada waktu sekitar " + vueApp.sisaWaktu + ", \
          pastikan seluruh jawaban telah terpenuhi. Apakah anda yakin ingin menyelesaikan ujian ?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya",
          cancelButtonText: "Tidak",
          closeOnConfirm: false                
        },function(keluar){
          if(keluar){
            location.href = "<?=site_url('?c=login&m=logout_siswa')?>";
          }
        });
      }else{
        location.href = "<?=site_url('?c=login&m=logout_siswa')?>";
      }
    });

    // $(document).on('click', '.options', function(){
    //   $('.options .option').removeClass('checked');
    //   $(this).find('.option').addClass('checked');
    //   console.log($(this).data());
    // });
    
    // Klik tombol ragu
    $(document).on('click', '.btn-ragu', function(){
      var btnRagu = $(this).find('span');
      $('#soal').waitMe();
      btnRagu.toggleClass('glyphicon-unchecked');
      btnRagu.toggleClass('glyphicon-check');
      var data = {
        no_soal: vueApp.soalJson[vueApp.idxSoal].no_soal,
        ragu: btnRagu.hasClass('glyphicon-check')
      }
      $.post('<?=site_url("?d=siswa&c=ujian&m=jawaban_ragu")?>', data, function(hasil){
        if(hasil == 'ok'){
          vueApp.soalJson[vueApp.idxSoal].ragu = data.ragu;
          $('#soal').waitMe('hide');
        }
      });
      console.log(data);
    });
    
    // Klik tombol daftar soal
    $(document).on('click', '#summary-button', function(){
      console.log($('#summary').toggle());
      var posisiTombol = $('#summary').is(":visible") ? '365px' : '0px';
      var cptTombol = $('#summary').is(":visible") ? '&nbsp;<br>&nbsp;' : 'Daftar <br>Soal';
      $(this).css('right', posisiTombol);
      $(this).find('.summary-nav').toggleClass('glyphicon-menu-left');
      $(this).find('.summary-nav').toggleClass('glyphicon-menu-right');
      $(this).find('.cpt').html(cptTombol);
    });
    
    // Klik tombol nomor soall pada bagian summary
    $(document).on('click', '.no', function(){
      bukaSoal($(this).data('idx'));
    });
    
    // Klik tombol simpan essay
    $(document).on('click', '.btn-simpan-essay', function(){            
      var data = {
        no_soal : vueApp.soalJson[vueApp.idxSoal].no_soal,
        essay : vueApp.soalJson[vueApp.idxSoal].jawaban_essay,
      }
      $("#soal").waitMe();
      $.post("<?=site_url('?d=siswa&c=ujian&m=submit_jawaban')?>", data, function(hasil){
        if(hasil == 'ok'){
          $("#soal").waitMe('hide');
        }
      });
    });
    
    var adaRagu = function(){
      var hasil = false;
      $.each(vueApp.soalJson, function(k, v){
        console.log(v.ragu);
        if(v.ragu == "1"){
          hasil = true;
          return;
        }
      });
      return hasil;
    }
    
    var hitungMundur = function(){
      // Set the date we're counting down to
      var countDownDate = new Date("<?=$ujian_selesai?>").getTime();
      
      var now = new Date("<?=$skrg?>").getTime();
      var distance = countDownDate - now;
      
      // Update the count down every 1 second
      var x = setInterval(function() {
        
        distance = distance - 1000;
        
        var hours = Math.floor(distance  / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // $('.info-sisa-waktu').html(hours + ':' + minutes + ':' + seconds);
        vueApp.sisaWaktu = hours + ':' + minutes + ':' + seconds;
        
        
        // If the count down is over, write some text 
        if (distance < 0) {
          clearInterval(x);
          vueApp.sisaWaktu = "KADALUARSA";
          swal({
            title: "Waktu Habis !!!",
            text: "Waktu anda telah habis, pekerjaan anda akan dikumpulkan secara otomatis",
            type: "warning",
          }, function(){
            location.href = "<?=site_url('?c=login&m=logout_siswa')?>"
          });
          setTimeout(function(){
            location.href = "<?=site_url('?c=login&m=logout_siswa')?>"
          }, 5000)
          
          
        }
      }, 1000);
    }
    
    $(document).on('click', '.a1', function(){
      $('#soal-body').css('font-size', '12pt');
    })
    
    $(document).on('click', '.a2', function(){
      $('#soal-body').css('font-size', '16pt');
    })
    
    $(document).on('click', '.a3', function(){
      $('#soal-body').css('font-size', '20pt');
    })
    
    hitungMundur();

    var bukaSoal = function(idx){
      $.get('?c=login&m=cek_status_login_siswa', function(hasil){
        if(hasil == '2'){
          vueApp.idxSoal = idx;
        }else{
          document.location.href = "<?php site_url('?c=ujian')?>";
        }
      });
    }        
    
    // console.log(vueApp.soalJson);
    
  });
  
  
</script>