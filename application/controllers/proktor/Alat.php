<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require_once APPPATH . 'controllers/proktor/Home_proktor.php';
require_once FCPATH . 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use Bt51\NTP\Socket;
use Bt51\NTP\Client;

class Alat extends Home_proktor{
	function backup(){
		$this->load->view('proktor/alat/backup.php');
	}
	
	function restore(){
		$this->load->view('proktor/alat/restore');
	}
	
	function reset(){
		$this->load->view('proktor/alat/reset.php');
  }
  
  function unduh_template(){
    $this->load->view('proktor/alat/unduh_template.php');
  }

  function unggah_nilai_essay(){
    $sql = "SELECT ujian_id, judul, DATE(mulai) as tgl 
            FROM ujian ORDER BY judul";
    $data['ujian'] = $this->db->query($sql)->result();
    $this->load->view('proktor/alat/unggah_nilai_essay', $data);
  }
  
  function submit_unggah_nilai_essay() {
    $ujian_id = $this->input->post('ujian_id');
    $config['upload_path'] = './public/';
    $config['allowed_types'] = 'xlsx';
    $config['file_name'] = string_acak(10);
    $this->load->library('upload', $config);
    $this->upload->do_upload('file_nilai');
    $file = $this->upload->data();
    $file = $file['full_path'];
    
    $spreadsheet = IOFactory::load($file);
    unlink($file);
    $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $header = $data[6];
    $no_soal = array();
    $excl = array('A', 'B', 'C', 'D', 'E');
    foreach($header as $k => $v){
      if(!in_array($k, $excl)){
        $no_soal[$k] = $v;
      }
    }

    $nilai_essay = array();
    foreach($data as $k => $v){
      if($k > 6) {
        foreach($no_soal as $k2 => $v2){
          if(empty($v2)) continue;
          $nilai = array();
          $nilai['ujian_id'] = $ujian_id;
          $nilai['nis'] = $v['C'];
          $nilai['login'] = $v['D'];
          $nilai['no_soal'] = $v2;
          $nilai['essay_skor'] = $v[$k2];
          $nilai_essay[] = $nilai;
        }
      }
    }    
    
    $jml_query = 0;
    $jml_query_gagal = 0;
    $sql = insert_or_update('peserta_jawaban', ['ujian_id', 'nis', 'login'], $nilai_essay);
    $this->db->trans_start();
    foreach ($sql as $key => $value) {
      $nis = $nilai_essay[$key]['nis'];
      $login = $nilai_essay[$key]['login'];
      $sql2 = "SELECT COUNT(nis) AS jml FROM peserta
                WHERE ujian_id = '$ujian_id' 
                AND nis='$nis'
                AND login = '$login'";
      $r = $this->db->query($sql2)->row();
      if($r->jml > 0){
        $this->db->query($value);
        $jml_query++;
      }else{
        $jml_query_gagal++;
      }
    }
    $this->db->trans_complete();
    $this->session->pesan = '
    <div class="alert alert-info">
      <strong>Unggah selesai</strong>, sistem telah memproses '.$jml_query.' data dan gagal memproses '. $jml_query_gagal .' data.
    </div>
    ';
		$this->session->mark_as_flash('pesan');
		redirect('?d=proktor&c=alat&m=unggah_nilai_essay');


  }
  
  function optimize(){
    $this->load->view('proktor/alat/optimize.php');
  }

  function do_optimize(){
    $sql = "SELECT konten FROM ujian";
    $data = $this->db->query($sql)->result();
    $dom = new DOMDocument();
    $jml_media = 0;
    foreach($data as $r){
      if($r->konten !== null){
        @$dom->loadHTML($r->konten);
        $tag_gambar = $dom->getElementsByTagName('img');
        foreach($tag_gambar as $img){
          $gbr = FCPATH . $img->getAttribute('src');
          if(file_exists($gbr)) {
            unlink($gbr);
            $jml_media ++;
          }
        }
      }
    }
    $data = ['pesan' => 'ok', 'jml_media' => $jml_media];
    json_output(200, $data);
  }
	
	function do_backup(){
    ini_set('memory_limit','1024M');
    ini_set('max_execution_time', 0);

		// buat direktori backup
		$token = string_acak(5);
		$backup_dir = FCPATH . 'backup-' . $token;
		mkdir($backup_dir);
		
    // buat json yg berisi db 
    // buffer table ujian
    $query = $this->db->get('ujian');
    $tmp = array();
    while ($row = $query->unbuffered_row()){
      $tmp[] = $row;
    }
    $data['ujian'] = $tmp;
    // buffer table soal
    $query = $this->db->get('soal');
    $tmp = array();
    while ($row = $query->unbuffered_row()){
      $tmp[] = $row;
    }
    $data['soal'] = $tmp;
    // buffer table pilihan_jawaban
    $query = $this->db->get('pilihan_jawaban');
    $tmp = array();
    while ($row = $query->unbuffered_row()){
      $tmp[] = $row;
    }
    $data['pilihan_jawaban'] = $tmp;
    // buffer table peserta
    $query = $this->db->get('peserta');
    $tmp = array();
    while ($row = $query->unbuffered_row()){
      $tmp[] = $row;
    }
    $data['peserta'] = $tmp;
    // buffer table peserta_jawaban
    $query = $this->db->get('peserta_jawaban');
    $tmp = array();
    while ($row = $query->unbuffered_row()){
      $tmp[] = $row;
    }
    $data['peserta_jawaban'] = $tmp;

		// backup db json ke file
		$fp = fopen($backup_dir . '/data.json', 'w');
		fwrite($fp, json_encode($data));
		fclose($fp);
		
		// copy keseluruhan gambar 
		salin_folder(FCPATH . 'images', $backup_dir . '/images');
		
		// arsipkan folder backup
		$zip_file = './public/backup_CBT_' . date('d-m-Y') . '_' . $token . '.zip';
		arsipkan_folder($backup_dir, $zip_file);
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($zip_file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($zip_file));
		readfile($zip_file);
		rrmdir($backup_dir);
		unlink($zip_file);
		
	}
	
	function do_reset(){
		data_do_reset();
		json_output(200, array('pesan'=> 'ok'));
	} 
	
	function do_restore(){
    // 0. setting ini
    ini_set('post_max_size', '100M');
    ini_set('upload_max_filesize', '100M');

		// 1. unggah backup
		$config['upload_path']          = './public/';
		$config['allowed_types']        = 'zip';
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('arsip')){
			$pesan = $this->upload->display_errors("<div class=\"alert alert-danger\">", "</div>");
			$this->session->pesan = $pesan;
			$this->session->mark_as_flash('pesan');
			redirect('?d=proktor&c=alat&m=restore');
		}else{
      // 2. reset seluruh ujian
      myob('<p>reset data lama...</p>');
			data_do_reset();
      
			// 3. ekstrak backup
      myob('<p>ekstrak backup ...</p>');
			$backup_file = $this->upload->data('full_path');
			$backup_raw = $this->upload->data('raw_name');
			$ekstrak_path = FCPATH . 'public/' . $backup_raw;
			$berhasil_ekstrak = ekstrak_zip($backup_file, $ekstrak_path);
      
			// 4. pindahkan gambar
      myob('<p>menyalin gambar ...</p>');
			rcopy($ekstrak_path . '/images', FCPATH . 'images');
			rrmdir($ekstrak_path . '/images');
      
			// 5. baca data json, sekaligus masukkan ke database
			$string = file_get_contents($ekstrak_path . '/data.json');
			$data = json_decode($string, true);
			data_do_pemulihan_data($data);
      
			// 6. hapus sisa backup yg tak diperlukan lagi
      myob('<p>menghapus sisa backup ...</p>');
			unlink($backup_file);
			rrmdir($ekstrak_path);

			// $data = array('upload_data' => $this->upload->data());			
			$pesan = "<div class=\"alert alert-success\">Proses restore telah berhasil dilaksanakan</div>";
			$this->session->pesan = $pesan;
			$this->session->mark_as_flash('pesan');
      echo "<script>document.location=href='?d=proktor&c=alat&m=restore'</script>";
		}
  }
  
  function cek_koneksi_inet() {
    $this->load->view('proktor/alat/cek_inet');
  }
  
  function do_cek_koneksi_inet() {
    $connected = @fsockopen("www.google.com", 80); 
    if ($connected){
      $is_conn = true; //action when connected
      fclose($connected);
    }else{
      $is_conn = false; //action in connection failure
    }
    
    $data = array('is_conn' => $is_conn);
    json_output(200, $data);
  }
  
  function unggah_foto() {
    $this->load->view('proktor/alat/unggah_foto');
  }

  function do_unggah_foto() {
    // 1. unggah foto
		$config['upload_path']          = './public/';
		$config['allowed_types']        = 'zip';
		$config['max_size']             = 1048576;
		
		$this->load->library('upload', $config);
		
		if (!$this->upload->do_upload('arsip')){
			$pesan = $this->upload->display_errors("<div class=\"alert alert-danger\">", "</div>");
			$this->session->pesan = $pesan;
			$this->session->mark_as_flash('pesan');
			redirect('?d=proktor&c=alat&m=unggah_foto');
		}else{
      $file_zip = $this->upload->data('full_path');
      $dir_upload = FCPATH . 'public/'. string_acak(5);
      ekstrak_zip($file_zip, $dir_upload);
      $this->gagal_unggah = array();
      $this->sukses_unggah = 0;
      $arr_nis = $this->_registered_nis();
      $this->_scan_foto($dir_upload, $arr_nis);
      unlink($file_zip);
      rrmdir($dir_upload);
      $pesan = '';
      if(count($this->gagal_unggah) > 0){
        $arr = implode('</li><li>' , $this->gagal_unggah);
        $arr = '<li>'. $arr .'</li>';
        $pesan = 'Terdapat beberapa foto yang gagal diunggah, karena NIS tidak ditemukan pada database, antara lain : <ol>'. $arr .'</ol>';
      }
      $pesan = "<div class=\"alert alert-info\">Arsip foto telah terunggah sebanyak ". $this->sukses_unggah ." berkas. $pesan</div>";
			$this->session->pesan = $pesan;
			$this->session->mark_as_flash('pesan');
			redirect('?d=proktor&c=alat&m=unggah_foto');
    }
  }

  function jam_sistem() {
    $socket = new Socket('0.id.pool.ntp.org', 123); 
    $ntp = new Client($socket);
    $date = $ntp->getTime();
    $data['ntp_time'] = $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
    $this->load->view('proktor/alat/jam_sistem', $data);
  }

  private function _registered_nis() {
    $this->db->select('nis');
    $rs = $this->db->get('peserta')->result();
    $hasil = array();
    foreach($rs as $r){
      $hasil[] = $r->nis;
    }
    return $hasil;
  }

  private function _scan_foto($dir_upload, $arr_nis ) {
    $ffs = scandir($dir_upload);
    $dir_foto = FCPATH . '/public/foto_siswa/';
    if(!file_exists($dir_foto)) mkdir($dir_foto);
    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);    
    
    foreach($ffs as $ff){
      $src = $dir_upload . '/' . $ff;
      if(is_dir($src)) {
        $this->_scan_foto($src, $arr_nis);
      }else{
        $path = pathinfo($src);
        // copy hanya yang jpg saja
        if(strtolower($path['extension']) == 'jpg' or strtolower($path['extension'] == 'jpeg')) {
          if(in_array($path['filename'], $arr_nis)){
            $dest = $dir_foto . $path['filename'] . '.jpg';
            copy($src, $dest);
            $this->sukses_unggah++;
          }else{
            $this->gagal_unggah[] = $ff;
          }
        }
      }
    }
  }
	
}