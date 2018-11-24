<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/siswa/Home_siswa.php';

class Ujian extends Home_siswa{

	function index(){
		// cek status ujian siswa 
		$this->db->where('ujian_id', $this->session->ujian_id);
		$this->db->where('nis', $this->session->nis);
		$this->db->where('login', $this->session->login);
		$this->db->select('status');
		$r = $this->db->get('peserta')->row();

		$data = $this->__detail_ujian();
		$data['status_peserta'] = $r->status;

		// jika hanya login saja dan belum memasukkan token
		if($r->status == '1'){
			$this->load->view('siswa/dashboard_peserta', $data);
		}
		// jika sudah memasukkan token
		elseif($r->status == '2'){
			$data['soal'] = $this->__get_soal($data['ujian_acak']);
			$this->load->view('siswa/kerjakan_soal', $data);
		}
		// jika sudah mengerjakan soal
		elseif($r->status == '3'){
			$this->load->view('siswa/nilai_siswa', $data);
		}

	}

	function start(){
		$token = $this->input->post('token');
		$this->db->where('konfig_id', 'TOKEN');
		$this->db->where('nilai_konfig', $token);
		$jml = $this->db->count_all_results('konfig');
		if($jml == 0){
			// atur pesan
			$this->session->pesan = 'token_gagal';
			$this->session->mark_as_flash('pesan');
		}else{		
			// update data peserta, ubah status menjadi sedang ujian (2)
			$this->db->where('ujian_id', $this->session->ujian_id);
			$this->db->where('nis', $this->session->nis);
			$this->db->where('login', $this->session->login);
			$this->db->set('status', '2');
			$this->db->update('peserta');
		}
		redirect('?d=siswa&c=ujian');
	}

	function submit_jawaban(){
		$data = $this->input->post();
		$ujian_id = $this->session->ujian_id;
		$nis = $this->session->nis;
		$login = $this->session->login;

		if(!empty($data['pilihan'])){
			// Jika soal adalah pilihan ganda

			// baca kunci jawaban, sekalian hitung skor
			$pilihan_skor = $this->__get_skor($ujian_id, $data['no_soal'], $data['pilihan']);
	
			$add_sql1 = 'pilihan, pilihan_skor';
			$add_sql2 = "'$data[pilihan]', $pilihan_skor";
		}else{
			$add_sql1 = 'essay';
			$add_sql2 = "'$data[essay]'";
		}

		// simpan jawaban
		$sql = "REPLACE INTO peserta_jawaban 
				(ujian_id, nis, login, no_soal, $add_sql1) VALUES 
				('$ujian_id', '$nis', '$login', $data[no_soal], $add_sql2)";
		if($this->db->query($sql)){
			echo 'ok';
		}

	}

	function jawaban_ragu(){
		$no_soal = $this->input->post('no_soal');
		$ragu = ($this->input->post('ragu') == 'true') ? '1' : '0';
		
		$this->db->where('ujian_id', $this->session->ujian_id);
		$this->db->where('nis', $this->session->nis);
		$this->db->where('login', $this->session->login);
		$this->db->where('no_soal', $no_soal);
		$this->db->set('ragu', $ragu);
		$this->db->update('peserta_jawaban');
		echo 'ok';
	}

	private function __detail_ujian(){   
		$this->db->where('ujian_id', $this->session->ujian_id);
		$r = $this->db->get('ujian')->row();
		$data['ujian_nama'] = $r->judul;
		$data['ujian_jenis'] = $r->jenis_ujian;
		$data['ujian_mulai'] = $r->mulai;
		$data['ujian_selesai'] = $this->__hitung_waktu_selesai($r->alokasi);
		$data['ujian_alokasi'] = $r->alokasi;
		$data['ujian_acak'] = $r->acak;
		$skrg = getdate();
		$data['skrg'] = $skrg['year'] . '-' . $skrg['mon'] . '-' . $skrg['mday'] . ' ' .
						$skrg['hours'] . ':' .$skrg['minutes'] . ':' . $skrg['seconds'] ;

		return $data;
	}

	private function __get_soal($pilihan_acak){

		$login = $this->session->login;
		$nis = $this->session->nis;
		$ujian_id = $this->session->ujian_id;
		$sql = "SELECT a.ujian_id, a.no_soal, a.essay, a.konten,  
				b.pilihan, IFNULL(b.essay, '') AS jawaban_essay, b.ragu
				FROM soal a
				LEFT JOIN peserta_jawaban b ON 
				a.ujian_id = b.ujian_id AND a.no_soal = b.no_soal AND b.nis = '$nis' AND b.login = '$login'
				WHERE a.ujian_id = '$ujian_id'";
		$soal = $this->db->query($sql)->result();

		$this->db->where('ujian_id', $this->session->ujian_id);
		$pilihan_jawaban = $this->db->get('pilihan_jawaban')->result();
		$data = array();
		foreach($soal as $r){
			$tmp = $r;
			$tmp2 = array_filter(
				$pilihan_jawaban,
				function($val) use ($r){
					return ($val->no_soal == $r->no_soal);
				}
			);

			// jika pilihan jawaban diacak
			if($pilihan_acak == '2'){
				shuffle($tmp2);
			}

			// ganti index agar tidak menjadi array asosiatif
			$tmp2 = array_values($tmp2);

			$tmp->pilihan_jawaban = $tmp2;
			$data[] = $tmp;
		}

		// jika soal diacak , acak hanya untuk soal pilihan ganda saja
		if($pilihan_acak != '0'){
			$tmp = array();
			$tmp2 = array();
			foreach($data as $r){
				if($r->essay != 1){
					$tmp[] = $r;
				}else{
					$tmp2[] = $r;
				}
			}
			shuffle($tmp);
			$data = array_merge($tmp, $tmp2);
		}

		return $data;
	}

	private function __get_skor($ujian_id, $no_soal, $pilihan){
		$this->db->where('ujian_id', $ujian_id);
		$this->db->where('no_soal', $no_soal);
		$this->db->where('jawaban', strtoupper($pilihan));
		// $this->db->select('jawaban');
		$this->db->select('skor');
		$q = $this->db->get('soal');
		if($q->num_rows() > 0){
			return $q->row()->skor;
		}else{      
			return 0;
		}
  }
  
  private function __hitung_waktu_selesai($alokasi){
		$this->db->where('ujian_id', $this->session->ujian_id);
		$this->db->where('nis', $this->session->nis);
		$this->db->where('login', $this->session->login);
    $this->db->select('last_login');
    $r = $this->db->get('peserta')->row();
    return interval_tgl($r->last_login, $alokasi);
  }
}