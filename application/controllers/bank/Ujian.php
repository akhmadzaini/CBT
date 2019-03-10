<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/bank/Home_bank.php';

class Ujian extends Home_bank{

  function index() {
    $this->db->select('ujian_id');
    $this->db->select('judul');
    $this->db->select('jml_soal');
    $this->db->order_by('judul');
    $data['ujian'] = $this->db->get('ujian')->result();
    $this->load->view('bank/ujian/aktif', $data);
  }
  
  function buka() {
    $ujian_id = $this->input->get('ujian_id');
    $this->db->where('ujian_id', $ujian_id);
    $data['soal'] = $this->db->get('ujian')->row();
    $this->load->view('bank/ujian/browse', $data);
  }
  
  function impor() {
    $ujian_id = $this->input->get('ujian_id');
    $this->db->where('ujian_id', $ujian_id);
    $soal = $this->db->get('ujian')->row();
    $konten_soal = $this->_pindah_gambar($soal->konten);
    $db_bank = $this->load->database('bank', TRUE);
    $db_bank->set('ujian_id', $soal->ujian_id);
    $db_bank->set('judul', $soal->judul);
    $db_bank->set('konten', $konten_soal);
    $db_bank->db_debug = FALSE;
    $db_bank->insert('soal');
    if($db_bank->error()['code'] == '23000/19'){
      $this->session->pesan = '<div class="alert alert-danger">
      Soal ujian sudah pernah diimpor sebelumnya.
      </div>';
    }else{
      $this->session->pesan = '<div class="alert alert-success">
      Soal ujian telah diimpor kedalam bank soal.
      </div>';
    }
    $this->session->mark_as_flash('pesan');
    $db_bank->db_debug = TRUE;
    redirect('?d=bank&c=ujian');
  }

  private function _pindah_gambar($konten) {
    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="UTF-8">' . $konten);
    $tag_img = $dom->getElementsByTagName('img');
    foreach($tag_img as $img){
      $rel_path = $img->getAttribute('src');
      $old_path = FCPATH . $rel_path;
      $new_path = FCPATH . 'bank/' . $rel_path;
      $abs_dir = dirname($new_path);
      if ((file_exists($abs_dir) && is_dir($abs_dir)) == FALSE) { 
        mkdir($abs_dir, 0775, true);
      }
      copy($old_path, $new_path);
      $img->setAttribute('src', 'bank/' . $rel_path);
    }
    return $dom->saveHTML();
  }

}