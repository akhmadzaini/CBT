<?php $this->load->view('proktor/header')?>

<!-- Moris chart -->
<link href="<?=base_url('theme/adminbsb/')?>plugins/morrisjs/morris.css" rel="stylesheet">

<section class="content">
  <div class="container-fluid" id="app">
    <div class="block-header">
      <h2>MONITOR UJIAN
        <small>
          Fasilitas penggawasan ujian
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
            <div class="number">{{ jml_ujian }}</div>
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
            <div class="number">{{ ujian_lalu.length }} ujian</div>
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
            <div class="number">{{ujian_lanjut.length}} ujian</div>
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
            <div class="number">{{ujian_progres.length}} ujian</div>
          </div>
        </div>
      </div>
      
    </div>  
    <!-- /INFOBOX -->
    
    <div class="row clearfix">
      <!-- Detail Ujian -->
      <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="header bg-light-blue">
                <h2>
                  UJIAN TERKINI
                  <small>Saat ini terdapat {{ujian_progres.length}} ujian yang sedang berlangsung</small>
                </h2>
              </div>
              <div class="body">
                
                <div v-for="item in ujian_progres" class="ujian-selesai">
                  <p>
                    <a :href="'?d=proktor&c=monitor&m=peserta&ujian_id=' + item.ujian_id"># {{item.judul}}</a> - 
                    {{item.ujian_id}} -
                    <span class="label label-danger">{{item.belum_login}} belum login</span>
                    <span class="label label-warning">{{item.pending}} pending</span>
                    <span class="label label-info">{{item.progres}} sedang mengerjakan</span>
                    <span class="label label-success">{{item.selesai}} selesai</span>
                    <a :href="'?d=proktor&c=monitor&m=hapus_ujian&ujian_id=' + item.ujian_id" class="btn-hapus-ujian">
                      <span class="label label-default">Hapus ujian ini</span>
                    </a>
                  </p>
                  <div class="progress">
                    <div class="progress-bar progress-bar-danger" v-bind:style="{width : (Math.floor(item.belum_login/item.jml_peserta *100)) + '%'}">
                    </div>
                    <div class="progress-bar progress-bar-warning" v-bind:style="{width : (Math.floor(item.pending/item.jml_peserta *100)) + '%'}">
                    </div>
                    <div class="progress-bar progress-bar-info" v-bind:style="{width : (Math.floor(item.progres/item.jml_peserta *100)) + '%'}">
                    </div>
                    <div class="progress-bar progress-bar-success" v-bind:style="{width : (Math.floor(item.selesai/item.jml_peserta *100)) + '%'}">
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
          <!-- UJIAN SELESAI -->
          <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
              <div class="body bg-pink">
                <div class="m-b--35 font-bold">UJIAN SELESAI</div>
                <ul class="dashboard-stat-list">
                  <li v-if="ujian_lalu.length == 0">
                    <em>Belum ada ujian selesai</em>
                  </li>
                  <li v-for="item in ujian_lalu">
                    #{{item.judul}} - {{item.ujian_id}}
                    <span class="pull-right">
                      <a :href="'?d=proktor&c=monitor&m=hapus_ujian&ujian_id=' + item.ujian_id" class="btn-hapus-ujian">
                        <i class="material-icons">delete</i>
                      </a>
                      <a :href="'?d=proktor&c=monitor&m=peserta&ujian_id=' + item.ujian_id"> 
                        <i class="material-icons">trending_up</i>
                      </a>
                    </span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- /UJIAN SELESAI -->
          
          <!-- UJIAN SELANJUTNYA -->
          <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
              <div class="body bg-deep-purple">
                <div class="m-b--35 font-bold">UJIAN SELANJUTNYA</div>
                <ul class="dashboard-stat-list">
                  <li v-if="ujian_lanjut.length == 0">
                    <em>Tidak ada ujian selanjutnya</em>
                  </li>
                  <li v-for="item in ujian_lanjut">
                    #{{item.judul}}
                    <span class="pull-right">
                      <a :href="'?d=proktor&c=monitor&m=hapus_ujian&ujian_id=' + item.ujian_id" class="btn-hapus-ujian">
                        <i class="material-icons">delete</i>
                      </a>
                      <a :href="'?d=proktor&c=monitor&m=peserta&ujian_id=' + item.ujian_id"> 
                        <i class="material-icons">trending_up</i>
                      </a>
                    </span>
                  </li>
                </ul>
              </div>
            </div>
          </div>  
          <!-- /UJIAN SELANJUTNYA -->
        </div>
      </div>
      <!-- /Detail Ujian -->
      
      <!-- Grafik ujian -->
      <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="card">
          <div class="header">
            <h2>
              GRAFIK UJIAN
            </h2>
          </div>
          <div class="body">
            <div id="grafik" class="graph"></div>
          </div>
        </div>
      </div>
      <!-- /Grafik ujian -->
      
    </div>
  </div>
</section>
<?php $this->load->view('proktor/footer')?>
<script src="<?=base_url('theme/adminbsb/')?>plugins/raphael/raphael.min.js"></script>
<script src="<?=base_url('theme/adminbsb/')?>plugins/morrisjs/morris.js"></script>
<script src="<?=base_url('theme/adminbsb/')?>plugins/vue/vue.js"></script>
<script>
  
  var counter = 1;
  var app = new Vue({
    el : '#app',
    data : {
      ujian_lalu : [],
      ujian_progres : [],
      ujian_lanjut : [],
      timer : null,
    },
    created : function(){
      this.segarkanUjian();
      
      this.timer = setInterval(function(){
        this.segarkanUjian();
      }.bind(this), 10000);
    },
    computed : {
      jml_ujian : function(){
        return this.ujian_lalu.length + this.ujian_progres.length + this.ujian_lanjut.length;
      },
    },
    methods : {
      segarkanUjian : function(){
        $.getJSON('<?=site_url("?d=proktor&c=monitor&m=get_ujian&jenis=lalu")?>', function(hasil){
          // periksa, jika hasil tidak berupa array, ada kemungkinan session sudah habis, logout
          if($.isArray(hasil)){
            this.ujian_lalu = hasil;
            this.segarkanGrafik();
          }else{
            document.location.href = "<?=site_url('?c=login&m=login_proktor')?>";
          }
        }.bind(this));
        $.getJSON('<?=site_url("?d=proktor&c=monitor&m=get_ujian&jenis=lanjut")?>', function(hasil){
          this.ujian_lanjut = hasil;
          this.segarkanGrafik();
        }.bind(this));
        $.getJSON('<?=site_url("?d=proktor&c=monitor&m=get_ujian&jenis=progres")?>', function(hasil){
          this.ujian_progres = hasil;
          this.segarkanGrafik();
        }.bind(this));
        console.log('penyegaran ke : ' + counter++);
      },
      segarkanGrafik : function(){
        $('#grafik').empty();
        Morris.Donut({
          element: 'grafik',
          data: [{
            label: 'Ujian Selesai',
            value: this.ujian_lalu.length
          }, {
            label: 'Ujian Berlangsung',
            value: this.ujian_progres.length
          }, {
            label: 'Ujian Selanjutnya',
            value: this.ujian_lanjut.length
          }],
          colors: ['#e91e63', '#03a9f4', '#673ab7'],
          formatter: function (y) {
            var jml_ujian = this.ujian_lalu.length + this.ujian_progres.length + this.ujian_lanjut.length
            return Math.round(y/jml_ujian*100) + '%';
          }.bind(this)
        });              
      },
    },
    beforeDestroy() {
      clearInterval(this.timer);
    }
  });
  
  $(function(){
    $(document).on('click', '.btn-hapus-ujian', function(evt){
      var url = $(this).attr('href');
      evt.preventDefault();
      swal({
        title: "Anda yakin ?",
        text: "Anda akan menghapus ujian ini, anda yakin ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, Saya yakin",
        cancelButtonText: "Tidak",
        closeOnConfirm: true
      },function(isReset){
        if(isReset) document.location.href = url;
      });
    });
  });
  
</script>
