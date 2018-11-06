<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/proktor/Home_proktor.php';

class Dashboard extends Home_proktor{
	function index(){
		$data['jml_ujian'] = $this->_jml_ujian();
		$data['jml_ujian_lalu'] = $this->_jml_ujian('lalu');
		$data['jml_ujian_lanjut'] = $this->_jml_ujian('lanjut');
		$data['jml_ujian_progres'] = $this->_jml_ujian('progres');

		$data['ujian_progres'] = $this->_list_ujian('progres');
		$data['ujian_lanjut'] = $this->_list_ujian('lanjut');

		$this->load->view('proktor/dashboard', $data);
	}
	
	private function _jml_ujian($jenis='all'){
		$this->db->where('status_soal <> 0');
		if($jenis == 'lalu'){
			$this->db->where("selesai < now()");
		}elseif($jenis == 'lanjut'){
			$this->db->where("mulai > now() AND selesai > now()");
		}elseif($jenis == 'progres'){
			$this->db->where("mulai <= now() AND selesai >= now()");
		}
		return $this->db->count_all_results('ujian');
	}
	
	private function _list_ujian($jenis='all'){
		$this->db->where('status_soal <> 0');
		if($jenis == 'lalu'){
			$this->db->where("selesai < now()");
		}elseif($jenis == 'lanjut'){
			$this->db->where("mulai > now() AND selesai > now()");
		}elseif($jenis == 'progres'){
			$this->db->where("mulai <= now() AND selesai >= now()");
		}
		$this->db->join('peserta', 'ujian.ujian_id = peserta.ujian_id');
		$this->db->group_by('ujian.ujian_id');
		$this->db->select('ujian.*');
		$this->db->select('COUNT(peserta.login) AS jml_peserta');
		$this->db->order_by('mulai');
		return $this->db->get('ujian')->result();
	}
}