<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/proktor/Home_proktor.php';

class Monitor extends Home_proktor{
	function ujian(){
		$this->load->view('proktor/monitor/ujian');
	}

	function get_ujian(){
		$jenis = $this->input->get('jenis');
		$this->db->where('status_soal <> 0');
		if($jenis == 'lalu'){
			$this->db->where("DATE(mulai) < CURRENT_DATE()");
		}elseif($jenis == 'lanjut'){
			$this->db->where("DATE(mulai) > CURRENT_DATE()");
		}elseif($jenis == 'progres'){
			$this->db->where("DATE(mulai) = CURRENT_DATE()");
		}
		$this->db->join('peserta', 'ujian.ujian_id = peserta.ujian_id', 'left');
		$this->db->group_by('ujian.ujian_id');
		$this->db->select('ujian.ujian_id, judul');
		$this->db->select('COUNT(peserta.login) AS jml_peserta');
		$this->db->select("(SELECT COUNT(*) FROM peserta WHERE ujian_id = ujian.ujian_id AND status = 0) AS belum_login");
		$this->db->select("(SELECT COUNT(*) FROM peserta WHERE ujian_id = ujian.ujian_id AND status = 1) AS pending");
		$this->db->select("(SELECT COUNT(*) FROM peserta WHERE ujian_id = ujian.ujian_id AND status = 2) AS progres");
		$this->db->select("(SELECT COUNT(*) FROM peserta WHERE ujian_id = ujian.ujian_id AND status = 3) AS selesai");
		$this->db->order_by('mulai');
		$data = $this->db->get('ujian')->result();
		json_output(200, $data);
	}

	function peserta(){
		// daftar ujian yang tersedia
		$this->db->select('ujian_id, judul');
		$this->db->where('status_soal <> 0');
		$this->db->order_by('judul');
		$data['ujian'] = $this->db->get('ujian')->result();

		$ujian_id = $this->input->get('ujian_id');
		$data['peserta'] = array();
    $data['ujian_id'] = $ujian_id;
    
    // data nama sekolah
    $sql = "SELECT DISTINCT nama_sekolah FROM peserta ORDER BY nama_sekolah";
    $data['nama_sekolah'] = $this->db->query($sql)->result();

		$this->load->view('proktor/monitor/peserta', $data);
	}

	function get_peserta(){
		$ujian_id = $this->input->get('ujian_id');
		if(!empty($ujian_id)){
			$this->db->where('ujian_id', $ujian_id);
			$this->db->order_by('LENGTH(login)');
			$this->db->order_by('login');
			$this->db->select('*');
			$this->db->select('(SELECT COUNT(*) FROM peserta_jawaban WHERE ujian_id = peserta.ujian_id AND login = peserta.login AND ragu = 0) AS terjawab');
			$this->db->select('(SELECT COUNT(*) FROM peserta_jawaban WHERE ujian_id = peserta.ujian_id AND login = peserta.login AND ragu = 1) AS ragu');
			$data['peserta'] = $this->db->get('peserta')->result();

			$this->db->where('ujian_id', $ujian_id);
			$data['jml_soal'] = $this->db->count_all_results('soal');
			json_output(200, $data);
		}
	}

	function get_detail_peserta(){
		// ambil biodata data peserta
		$login = $this->input->get('login');
		$ujian_id = $this->input->get('ujian_id');

		$this->db->where('login', $login);
		$this->db->where('ujian_id', $ujian_id);
		$this->db->select('nama, status');
		$r = $this->db->get('peserta')->row();
		$data['nama_peserta'] = $r->nama;
		$data['status'] = $r->status;

		// ambil data ujian
		$this->db->where('ujian_id', $ujian_id);
		$this->db->select('judul, selesai');
		$r = $this->db->get('ujian')->row();
		$data['nama_ujian'] = $r->judul;
		$data['waktu_selesai'] = $r->selesai;

		// ambil data jawaban
		$data['jawaban'] = $this->get_jawaban_peserta();

		json_output(200, $data);
	}

	function reset_peserta(){
		$data = $this->input->get();
		$this->db->where('login', $data['login']);
		$this->db->where('ujian_id', $data['ujian_id']);
		$this->db->set('status', '0');
		$this->db->update('peserta');
		json_output(200, array('message' => 'ok'));
  }
  
  function tambah_peserta(){
    $post = $this->input->post();
    $this->db->set('ujian_id', $post['ujian_id']);
    $this->db->set('nis', $post['nis']);
    $this->db->set('login', $post['login']);
    $this->db->set('password', $post['password']);
    $this->db->set('nama', $post['nama']);
    $this->db->set('nama_sekolah', $post['nama_sekolah']);
    $this->db->set('server', $post['server']);
    $this->db->set('kelas', $post['kelas']);
    $this->db->set('sesi', $post['sesi']);
    $this->db->insert('peserta');
    redirect('d=proktor&c=monitor&m=peserta&ujian_id=' . $post['ujian_id']);
  }

  function hapus_ujian(){
    $ujian_id = $this->input->get('ujian_id');
    
    if(!empty($ujian_id)){
      // 1. bersihkan gambar
      bersihkan_gambar($ujian_id);

      // 2. hapus data peserta jawaban
      $this->db->query("DELETE FROM peserta_jawaban WHERE ujian_id = '$ujian_id'");
      
      // 3. hapus data peserta
      $this->db->query("DELETE FROM peserta WHERE ujian_id = '$ujian_id'");

      // 4. hapus data pilihan_jawaban
      $this->db->query("DELETE FROM pilihan_jawaban WHERE ujian_id = '$ujian_id'");
     
      // 5. hapus data soal
      $this->db->query("DELETE FROM soal WHERE ujian_id = '$ujian_id'");
     
      // 6. hapus data ujian
      $this->db->query("DELETE FROM ujian WHERE ujian_id = '$ujian_id'");
    }

    redirect("?d=proktor&c=monitor&m=ujian");
  }

	private function get_jawaban_peserta(){
		// ambil biodata data peserta
		$login = $this->input->get('login');
		$ujian_id = $this->input->get('ujian_id');

		$sql = "SELECT a.no_soal, a.essay AS is_essay, b.pilihan, b.ragu, b.essay, b.pilihan_skor
				FROM soal a
				LEFT JOIN peserta_jawaban b 
				ON a.ujian_id = b.ujian_id 
				AND  a.no_soal = b.no_soal 
				AND b.login = '$login'
				WHERE a.ujian_id = '$ujian_id'";
		return $this->db->query($sql)->result();

	}

}