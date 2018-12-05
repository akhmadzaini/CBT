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
    $this->__cek_token();
    $data = json_decode($this->input->post('data'));
    $id_server = $this->input->post('id_server');
    
		if(count($data) > 0){
			// Hapus jawaban peserta yang lama
			$this->db->query("DELETE FROM peserta_jawaban 
                        WHERE nis IN (SELECT nis FROM peserta WHERE server = '$id_server')");
			
			// Masukan jawaban baru
			$aff_rows = $this->db->insert_batch('peserta_jawaban', $data);
		}else{
			$aff_rows = 0;
		}
		json_output(200, ['aff_rows' => $aff_rows]);
	}
  
	private function __cek_token(){
		$token = $this->input->post('token');
		if($token != 'kEXCZ9KjumHxTO8dsVyg'){
			json_output(200, array('pesan' => 'token_gagal', 'post'=> $_POST));
			die();
		}
	}
  
}