<?php 
if (!defined('BASEPATH')) {	exit('No direct script access allowed'); }

class Excel extends CI_Controller {

	function __construct(){
		parent::__construct();
		$post = $this->input->post();		
		if(!login_webservice($post['login'], $post['password'])){
			json_output(200, array('pesan' => 'login_gagal'));
			die();
		}		
	}

	function ujian_baru(){
		$post = $this->input->post();
    // $selesai = interval_tgl($post['mulai'], $post['alokasi']);
    $selesai = jam_akhir($post['mulai']);
		$peserta = json_decode($post['peserta'], TRUE);
		$ujian_id = string_acak(10);

		// Masukkan data ujian
		$sql = "INSERT INTO ujian (ujian_id, mulai, selesai, alokasi, jml_soal, acak)
				VALUES ('$ujian_id', '$post[mulai]', '$selesai', $post[alokasi], $post[jml_soal], $post[acak])";
		$this->db->query($sql);

		// Masukkan data peserta
		$sql = "INSERT INTO peserta (ujian_id, nis, login, nama, password, server, sesi, kelas) VALUES ";
		foreach($peserta as $p){
			$nama = mysqli_real_escape_string($this->db->conn_id, $p['nama']);
			$rows[] = "('$ujian_id', '$p[nis]', '$p[login]', '$nama', '$p[password]', '$p[server]', '$p[sesi]', '$p[kelas]')";
		}
		$sql .= implode(',', $rows);
		$this->db->query($sql);

		// Atur nilai kembalian pada console json
		$hasil = array(
			'pesan' => 'ok',
			'ujian_id' => $ujian_id,
		);
		json_output(200, $hasil);
	}

	function sunting_ujian(){
		$post = $this->input->post();
    // $selesai = interval_tgl($post['mulai'], $post['alokasi']);
    $selesai = jam_akhir($post['mulai']);
    log_message('custom', "mulai : $post[mulai], selesai : $selesai");
		$peserta = json_decode($post['peserta'], TRUE);
		$ujian_id = $post['ujian_id'];

		// Periksa apakah id ujian telah tersedia
		$this->db->where("ujian_id='$ujian_id'");
		if($this->db->count_all_results('ujian') == 0){
			json_output(200, array('pesan' => 'ujian_tak_tersedia'));
			die();
		}

		// Periksa apakah soal ujian sudah terkunci
		// $this->db->where("ujian_id='$ujian_id' AND status_soal=2");
		// if($this->db->count_all_results('ujian') > 0){
		// 	json_output(200, array('pesan' => 'terkunci'));
		// 	die();
		// }

		// Sunting data ujian
		$sql = "UPDATE ujian 
				SET mulai = '$post[mulai]', selesai = '$selesai', alokasi = '$post[alokasi]',
					jml_soal = $post[jml_soal], acak = $post[acak]
				WHERE ujian_id = '$ujian_id'";
		$this->db->query($sql);

		// Cek status soal (untuk nilai kembalian webservice)
		$r = $this->db->query("SELECT status_soal FROM ujian WHERE ujian_id='$ujian_id'")->row();
		$status_soal = $r->status_soal;

		// Reset data peserta ujian
		$this->db->query("DELETE FROM peserta WHERE ujian_id='$ujian_id'");

		// Masukkan data peserta
		$sql = "INSERT INTO peserta (ujian_id, nis, login, nama, password, server, sesi, kelas) VALUES ";
		foreach($peserta as $p){
			$nama = mysqli_real_escape_string($this->db->conn_id, $p['nama']);
			$rows[] = "('$ujian_id', '$p[nis]', '". strtoupper($p['login']) ."', '$nama', '$p[password]', '$p[server]', '$p[sesi]', '$p[kelas]')";
		}
		$sql .= implode(',', $rows);
		$this->db->query($sql);

		// Atur nilai kembalian pada console json
		$hasil = array(
			'pesan' => 'ok',
			'ujian_id' => $ujian_id,
			'status_soal' => $status_soal,
		);
		json_output(200, $hasil);
	}

	function tarik_nilai(){
		$post = $this->input->post();
		if(empty($post)){
			die('request tidak sah');
		}

		// Periksa apakah id ujian telah tersedia
		$this->db->where("ujian_id='$post[ujian_id]'");
		if($this->db->count_all_results('ujian') == 0){
			json_output(200, array('pesan' => 'ujian_tak_tersedia'));
			die();
    }
    
    // ambil data kunci jawaban
    $sql = "SELECT no_soal, jawaban, skor
          FROM soal
          WHERE essay = 0
          AND ujian_id = '$post[ujian_id]'
          ORDER BY no_soal";
    $kunci_jawaban = $this->db->query($sql)->result();
    $jml_soal = count($kunci_jawaban);

		// Generate query kolom untuk no soal
		$sql_add = array();
		foreach($kunci_jawaban as $r){
      $no = $r->no_soal;
			$sql_add[] = "(SELECT pilihan FROM  peserta_jawaban 
						WHERE ujian_id = a.ujian_id AND nis = a.nis AND login = a.login AND no_soal = $no) AS no_$no";
		}
		$sql_add = implode(',', $sql_add);
		
		$sql = "SELECT a.nis, a.nama, a.status, a.last_login,	$sql_add
				FROM peserta a
        WHERE a.ujian_id = '$post[ujian_id]'
        ORDER BY a.server, a.kelas, a.nama";
        
		$data = array(
      'pesan' => 'ok' , 
      'data' => $this->db->query($sql)->result_array(), 
      'kunci_jawaban' => $kunci_jawaban
    );
		json_output(200, $data);
	}

	function reset_status_login_siswa(){
		$post = $this->input->post();
		if(login_webservice($post['login'], $post['password'])){
			$username = $post['username'];
			$ujian_id = $post['ujian_id'];
			$sql = "UPDATE peserta SET status = 0 WHERE ujian_id = '$ujian_id' AND login = '$username'";
			$this->db->query($sql);
			$hasil = array(
				'pesan' => 'ok'
			);
		}else{
			$hasil = array(
				'pesan' => 'gagal'
			);
		}
		echo json_encode($hasil);
	}

}