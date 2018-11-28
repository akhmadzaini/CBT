<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require FCPATH . 'vendor/autoload.php';
use \Curl\Curl;

require_once APPPATH . 'controllers/proktor/Home_proktor.php';

class Sinkron extends Home_proktor{
  
  function __construct(){
    parent::__construct();
    $this->token = 'kEXCZ9KjumHxTO8dsVyg';
  }
  
  function tarik(){
    $this->load->view('proktor/sinkron/tarik');
  }
  
  function do_tarik(){
    $post = $this->input->post();
    $target = $post['server_remote'] . '/index.php?c=sinkron&m=tarik';
    $data = [
      'token' => $this->token,
      'id_server' => $post['id_server']
    ];
    
    // kirim request ke server remote
    $curl = new Curl();
    $curl->post($target, $data);
    
    if($curl->getHttpStatusCode() == 200){
      
      $r = $curl->response;
      if(!isset($r->pesan) || $r->pesan == 'token_gagal'){
        json_output(200, array('pesan' => 'token_gagal'));
      }else{
        json_output(200, array('pesan' => 'ok', 'nama_zip' => $r->nama_zip));
        $curl->close();
      }
    }else{
      json_output(200, array('pesan' => 'konek_gagal'));
    }
  }
  
  function do_tarik_2(){
    // Simpan zip dari remote ke lokal
    $nama_zip = $this->input->post('nama_zip');
    $server_remote = $this->input->post('server_remote');
    $full_path_zip = $server_remote . '/index.php?c=sinkron&m=tarik_zip&zip=' . $nama_zip;
    $nama_sinkron = FCPATH . 'public/sinkron_' . $nama_zip;
    ini_set('max_execution_time', 0); 
    file_put_contents($nama_sinkron, fopen($full_path_zip, 'r'));
    json_output(200, array('pesan' => 'ok'));
  }
  
  function do_restore(){
    ini_set('max_execution_time', 0);
    
    // 1. Reset data
    data_do_reset();
    
    // 2. ekstrak backup
    $arsip_sinkron = $this->input->post('arsip_sinkron');
    $fullpath_arsip_sinkron = FCPATH . 'public/sinkron_' . $arsip_sinkron;
    $ekstrak_path = FCPATH . 'public/tmp-sinkron';
    $berhasil_ekstrak = ekstrak_zip($fullpath_arsip_sinkron, $ekstrak_path);
    
    // 3. pindahkan gambar
    rcopy($ekstrak_path . '/images', FCPATH . 'images');
    
    // 4. baca data json, sekaligus masukkan ke database
    $string = file_get_contents($ekstrak_path . '/data.json');
    $data = json_decode($string, true);
    data_do_pemulihan_data($data);
    
    // 5. hapus sisa backup yg tak diperlukan lagi
    unlink($fullpath_arsip_sinkron);
    rrmdir($ekstrak_path);
    
    // 6. atur notif
    json_output(200, array('pesan' => 'ok'));
  }
  
  function kirim(){
    $this->load->view('proktor/sinkron/kirim');
  }
  
  function do_kirim(){
    $post = $this->input->post();
    $target = $post['server_remote'] . '/index.php?c=sinkron&m=terima_nilai';
    $sql= "SELECT b.*
    FROM peserta a
    LEFT JOIN peserta_jawaban b ON a.ujian_id = b.ujian_id AND a.nis = b.nis AND a.login = b.login
    WHERE a.server = '$post[id_server]' AND b.ujian_id IS NOT NULL";
    $data = $this->db->query($sql)->result();
    $data = [
      'token' => $this->token,
      'id_server' => $post['id_server'],
      'data' => json_encode($data)
    ];
    
    // kirim request ke server remote
    $curl = new Curl();
    $curl->post($target, $data);
    if($curl->getHttpStatusCode() == 200){
      $r = $curl->response;
      json_output(200, ['pesan' => 'ok', 'aff_rows' => $r->aff_rows]);
    }else{
      json_output(200, array('pesan' => 'konek_gagal', 'resp' => $curl->getHttpStatusCode()));
    }
  }
  
}