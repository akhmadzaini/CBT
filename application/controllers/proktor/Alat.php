<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/proktor/Home_proktor.php';

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
    ini_set('memory_limit','256M');
    ini_set('max_execution_time', 0);

		// buat direktori backup
		$token = string_acak(5);
		$backup_dir = FCPATH . 'backup-' . $token;
		mkdir($backup_dir);
		
		// buat json yg berisi db 
		$data['ujian'] = $this->db->get('ujian')->result();
		$data['soal'] = $this->db->get('soal')->result();
		$data['pilihan_jawaban'] = $this->db->get('pilihan_jawaban')->result();
		$data['peserta'] = $this->db->get('peserta')->result();
		$data['peserta_jawaban'] = $this->db->get('peserta_jawaban')->result();
		
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
		// 1. unggah backup
		$config['upload_path']          = './public/';
		$config['allowed_types']        = 'zip';
		$config['max_size']             = 1048576;
		
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

	

	
}