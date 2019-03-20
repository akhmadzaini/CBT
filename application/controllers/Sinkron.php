<?php 
if (!defined('BASEPATH')) {	exit('No direct script access allowed'); }

class Sinkron extends CI_Controller {    
	
  function tarik(){
    $this->__cek_token();
    
    // cek apakah id server tersedia (jika id server terisi)
    $id_server = $this->input->post('id_server');
    if($id_server != 'all'){
      if($id_server != ''){
        $this->db->where('server', $id_server);
        $jml = $this->db->count_all_results('peserta');
        if($jml == 0){
          json_output(200, array('pesan' => 'server_gagal'));
          die();
        }
      }
    }

    // mulai catat log
    $id_log = string_acak(10);
    $ip = $this->input->ip_address();
    $db_log = $this->load->database('log', TRUE);
    $db_log->set('id', $id_log);
    $db_log->set('ip', $ip);
    $db_log->set('id_server', $id_server);
    $db_log->set('mulai', time());
    
    $token = string_acak(5);
    $backup_dir = FCPATH . 'backup-' . $token;
    mkdir($backup_dir);
    
    $data['ujian'] = $this->db->get('ujian')->result();
    $data['soal'] = $this->db->get('soal')->result();
    $data['pilihan_jawaban'] = $this->db->get('pilihan_jawaban')->result();
    if($id_server != '') $data['peserta'] = $this->db->get_where('peserta', array('server' => $id_server))->result();
    if($id_server == 'all') $data['peserta'] = $this->db->get('peserta')->result();
    
		$fp = fopen($backup_dir . '/data.json', 'w');
		fwrite($fp, json_encode($data));
		fclose($fp);
    
    
		// copy keseluruhan gambar 
		salin_folder(FCPATH . 'images', $backup_dir . '/images');
		
		// arsipkan folder backup
		$zip_file = './public/backup_CBT_' . date('d-m-Y') . '_' . $token . '.zip';
		arsipkan_folder($backup_dir, $zip_file);
    rrmdir($backup_dir);
    $nama_zip = 'backup_CBT_' . date('d-m-Y') . '_' . $token . '.zip';

    // catat log dulu sebelum return
    $db_log->set('zip_file', $nama_zip);
    $db_log->set('selesai_zip', time());
    $db_log->insert('sinkron_server_tarik');

    // ambil password bank soal
    $db_bank = $this->load->database('bank', TRUE);
    $this->load->library('encryption');
    $db_bank->select('password');
    $db_bank->where('login','bank');
    $r = $db_bank->get('pengguna')->row();
    // $password_bank = $this->encryption->decrypt($r->password);
    
		json_output(200, array('pesan'=>'ok', 'nama_zip' => $nama_zip, 'id_log' => $id_log, 'password_bank' => $r->password));
	}
	
	function tarik_zip(){
    // unduh zip
		$zip_file = FCPATH . 'public/' . $this->input->get('zip');
		header('Content-Description: File Transfer');
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename='.basename($zip_file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($zip_file));
		readfile($zip_file);
    unlink($zip_file);
  }
  
  function log_tarik_sukses() {
    $id_log = $this->input->post('id_log');
    $db_log = $this->load->database('log', TRUE);
    $db_log->set('selesai', time());
    $db_log->where('id', $id_log);
    $db_log->update('sinkron_server_tarik');
  }
  
	function terima_nilai(){
    ini_set('max_execution_time', 0);
    $this->__cek_token();
    $data_peserta = $this->input->post('peserta');
    $data_peserta_jawaban = $this->input->post('peserta_jawaban');
    $peserta = json_decode($data_peserta);
    $peserta_jawaban = json_decode($data_peserta_jawaban);
    $id_server = $this->input->post('id_server');
    $ujian_id = $this->input->post('ujian_id');
    
    $jml_peserta = count($peserta); 
    $jml_peserta_jawaban = count($peserta_jawaban);
    $data_ = ['peserta' => $jml_peserta, 'peserta_jawaban' => $jml_peserta_jawaban];

    // catat log kirim
    $db_log = $this->load->database('log', TRUE);
    $id_log = string_acak(10);
    $ip = $this->input->ip_address();
    $db_log->set('id', $id_log);
    $db_log->set('ip', $ip);
    $db_log->set('ujian_id', $ujian_id);
    $db_log->set('id_server', $id_server);
    $db_log->set('mulai', time());
    $db_log->insert('sinkron_server_kirim');

    set_time_limit(0);
    ob_end_clean();
    ignore_user_abort(true);
    ob_start();
    json_output(200, $data_);
    $size = ob_get_length();
    header("Content-Length: $size");
    ob_end_flush();
    flush();

    // proses ini dikerjakan di bagian background
    $this->db->trans_start();
    if($jml_peserta > 0){
      $pecahan_peserta = array_chunk($peserta, 100);
      foreach($pecahan_peserta as $pecahan){
        $this->db->query(replace_batch('peserta', $pecahan));
      }        
    }
    if($jml_peserta_jawaban > 0){
      $pecahan_peserta_jawaban = array_chunk($peserta_jawaban, 100);
      foreach($pecahan_peserta_jawaban as $pecahan){
        $this->db->query(replace_batch('peserta_jawaban', $pecahan));
      }
    }
    $this->db->trans_complete();

    // catat log selesai
    $db_log->set('selesai', time());
    $db_log->where('id', $id_log);
    $db_log->update('sinkron_server_kirim');

    exit();

  }
  
	function terima_nilai_gz(){
    ini_set('max_execution_time', 0);
    $this->__cek_token();
    $data_peserta = gzinflate(base64_decode($this->input->post('peserta')));
    $data_peserta_jawaban = gzinflate(base64_decode($this->input->post('peserta_jawaban')));
    // log_message('custom', $data_peserta_jawaban);
    $peserta = json_decode($data_peserta);
    $peserta_jawaban = json_decode($data_peserta_jawaban);
    $id_server = $this->input->post('id_server');
    $ujian_id = $this->input->post('ujian_id');
    
    $jml_peserta = count($peserta); 
    $jml_peserta_jawaban = count($peserta_jawaban);
    $data_ = ['peserta' => $jml_peserta, 'peserta_jawaban' => $jml_peserta_jawaban];

    // catat log kirim
    $db_log = $this->load->database('log', TRUE);
    $id_log = string_acak(10);
    $ip = $this->input->ip_address();
    $db_log->set('id', $id_log);
    $db_log->set('ip', $ip);
    $db_log->set('ujian_id', $ujian_id);
    $db_log->set('id_server', $id_server);
    $db_log->set('mulai', time());
    $db_log->insert('sinkron_server_kirim');

    set_time_limit(0);
    ob_end_clean();
    ignore_user_abort(true);
    ob_start();
    json_output(200, $data_);
    $size = ob_get_length();
    header("Content-Length: $size");
    ob_end_flush();
    flush();

    // proses ini dikerjakan di bagian background
    $this->db->trans_start();
    if($jml_peserta > 0){
      $pecahan_peserta = array_chunk($peserta, 100);
      foreach($pecahan_peserta as $pecahan){
        $this->db->query(replace_batch('peserta', $pecahan));
      }        
    }
    if($jml_peserta_jawaban > 0){
      $pecahan_peserta_jawaban = array_chunk($peserta_jawaban, 100);
      foreach($pecahan_peserta_jawaban as $pecahan){
        $this->db->query(replace_batch('peserta_jawaban', $pecahan));
      }
    }
    $this->db->trans_complete();

    // catat log selesai kirim
    $db_log->set('selesai', time());
    $db_log->where('id', $id_log);
    $db_log->update('sinkron_server_kirim');

    exit();

	}
  
	private function __cek_token(){
		$token = $this->input->post('token');
		if($token != 'kEXCZ9KjumHxTO8dsVyg'){
			json_output(200, array('pesan' => 'token_gagal', 'post'=> $_POST));
			die();
		}
	}
  
}