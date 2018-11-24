<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Login extends CI_Controller{
	function index(){
		if((empty($this->session->login)) || ($this->session->akses != 'siswa')){
			$sql = "SELECT ujian_id, judul
					FROM ujian
					WHERE (NOW() <= selesai) AND (NOW() >= mulai) AND (status_soal <> 0)";
			$arr_ujian_aktif = $this->db->query($sql)->result();
			$data = array('arr_ujian_aktif' => $arr_ujian_aktif);
			$this->load->view('siswa/login', $data);
		}else{
			redirect('?d=siswa&c=ujian');
		}
	}

	function login_proktor(){
		if((empty($this->session->login)) || ($this->session->akses != 'proktor')){
			$this->load->view('proktor/login');
		}else{
			redirect('?d=proktor&c=dashboard');
		}
	}

	function submit_login_proktor(){
		$secret = $this->config->item('encryption_key');
		$post = $this->input->post();
		$sql = "SELECT * FROM proktor WHERE 
				login = '$post[login]' AND 
				password = AES_ENCRYPT('$post[password]', '$secret')";
		$q = $this->db->query($sql);
		if($q->num_rows() > 0){
			$r = $q->row();
			$this->session->login = $post['login'];
			$this->session->akses = 'proktor';
			$this->session->nama  = $r->nama;
			redirect('?d=proktor&c=dashboard');
		}else{
			$pesan = 'login_gagal';
			$this->session->pesan = $pesan;
			$this->session->mark_as_flash('pesan');
			redirect('?c=login&m=login_proktor');
		}

	}

	function submit_login_siswa(){
		$post = $this->input->post();
		$sql = "SELECT * FROM peserta 
				WHERE ujian_id = '$post[ujian_id]'
				AND status = '0' 
				AND UPPER(login) = '" . strtoupper($post['login']) . "'
				AND password = '$post[password]'";
		$q = $this->db->query($sql);
		if($q->num_rows() > 0){
			$ujian = $this->__cek_ujian($post['ujian_id']);
			if($ujian->num_rows() > 0){

				$this->__kunci_ujian($post['ujian_id'], $post['login']);

				// menyimpan last_login
				$this->db->set('last_login', 'NOW()', FALSE);
				$this->db->where('login', $post['login']);
				$this->db->where('ujian_id', $post['ujian_id']);
				$this->db->update('peserta');

				// mengatur session detail login yang aktif
				$r = $q->row();
				$this->session->login = $post['login'];
				$this->session->akses = 'siswa';
				$this->session->nama  = $r->nama;
				$this->session->nis   = $r->nis;

				// mengatur session ujian yang aktif
				$r = $ujian->row();
				$this->session->ujian_id = $r->ujian_id;

				redirect('?d=siswa&c=ujian');
				exit();
			}else{
				$pesan = 'ujian_tak_tersedia';
			}
		}else{
			$pesan = 'login_gagal';
		}
		$this->session->pesan = $pesan;
		$this->session->mark_as_flash('pesan');
		redirect('?c=login');
	}

	function logout_siswa(){
		// logout peserta, status kembali menjadi 0 hanya jika status = 1
		$this->db->where('login', $this->session->login);
		$this->db->where('nis', $this->session->nis);
		$this->db->where('ujian_id', $this->session->ujian_id);
		$this->db->where('status', '1');
		$this->db->set('status', '0');
		$this->db->update('peserta');
		// logout peserta, status menjadi 3 hanya jika status = 2
		$this->db->where('login', $this->session->login);
		$this->db->where('nis', $this->session->nis);
		$this->db->where('ujian_id', $this->session->ujian_id);
		$this->db->where('status', '2');
		$this->db->set('status', '3');
		$this->db->update('peserta');
		
		// hapus session
		$arr_sess = array('login', 'akses', 'nama', 'nis', 'ujian_id');
		$this->session->unset_userdata($arr_sess);

		// atur pesan
		$this->session->pesan = 'logout_sukses';
		$this->session->mark_as_flash('pesan');
		redirect('?c=login');
	}


	function logout_proktor(){
		// hapus session
		$arr_sess = array('login', 'akses', 'nama');
		$this->session->unset_userdata($arr_sess);

		// atur pesan
		$this->session->pesan = 'logout_sukses';
		$this->session->mark_as_flash('pesan');
		redirect('?c=login&m=login_proktor');
	}

	// Periksa status ujian, apakah sudah upload dan berada dalam rentang waktu yang sudah ditentukan
	private function __cek_ujian($ujian_id){
		$this->db->where("ujian_id = '$ujian_id' AND status_soal <> '0' 
						AND (NOW() <= selesai) AND (NOW() >= mulai)");
		$this->db->select('ujian_id');
		return $this->db->get('ujian');
		// return ($jml > 0);
	}

	// Kunci ujian, jika ada peserta yang masuk, sekaligus kunci peserta agar tidak login ulang dari pc lain
	private function __kunci_ujian($ujian_id, $login){
		// kunci ujian
		$this->db->where("ujian_id = '$ujian_id' AND status_soal = '1' 
						AND (NOW() <= selesai) AND (NOW() >= mulai)");
		$this->db->set('status_soal', '2');
		$this->db->update('ujian');

		// kunci peserta
		$this->db->where("login = '$login'");
		$this->db->where("ujian_id = '$ujian_id'");
		$this->db->set('status', '1');
		$this->db->update('peserta');
	}
}