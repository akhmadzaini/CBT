<?php 
if (!defined('BASEPATH')) {	exit('No direct script access allowed'); }

class Sinkron extends CI_Controller {    
	
  function tarik(){
		$this->__cek_token();
    
    // cek apakah id server tersedia (jika id server terisi)
    $id_server = $this->input->post('id_server');
    if($id_server != ''){
      $this->db->where('server', $id_server);
      $jml = $this->db->count_all_results('peserta');
      if($jml == 0){
        json_output(200, array('pesan' => 'server_gagal'));
        die();
      }
    }

		$token = string_acak(5);
		$backup_dir = FCPATH . 'backup-' . $token;
		mkdir($backup_dir);
    
    $data['ujian'] = $this->db->get('ujian')->result();
		$data['soal'] = $this->db->get('soal')->result();
		$data['pilihan_jawaban'] = $this->db->get('pilihan_jawaban')->result();
    if($id_server != '') $data['peserta'] = $this->db->get_where('peserta', array('server' => $id_server))->result();

		$fp = fopen($backup_dir . '/data.json', 'w');
		fwrite($fp, json_encode($data));
		fclose($fp);
    
    
		// copy keseluruhan gambar 
		salin_folder(FCPATH . 'images', $backup_dir . '/images');
		
		// arsipkan folder backup
		$zip_file = './public/backup_CBT_' . date('d-m-Y') . '_' . $token . '.zip';
		arsipkan_folder($backup_dir, $zip_file);
		rrmdir($backup_dir);
		json_output(200, array('pesan'=>'ok', 'nama_zip' => 'backup_CBT_' . date('d-m-Y') . '_' . $token . '.zip'));
	}
	
	function tarik_zip(){
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
  
	function terima_nilai(){
    ini_set('max_execution_time', 0);
    $this->__cek_token();
    $peserta = json_decode($this->input->post('peserta'));
    $peserta_jawaban = json_decode($this->input->post('peserta_jawaban'));
    $id_server = $this->input->post('id_server');

    $jml_peserta = count($peserta); 
    $jml_peserta_jawaban = count($peserta_jawaban);
    
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
		json_output(200, ['peserta' => $jml_peserta, 'peserta_jawaban' => $jml_peserta_jawaban]);
	}
  
	private function __cek_token(){
		$token = $this->input->post('token');
		if($token != 'kEXCZ9KjumHxTO8dsVyg'){
			json_output(200, array('pesan' => 'token_gagal', 'post'=> $_POST));
			die();
		}
	}
  
}