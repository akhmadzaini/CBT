<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/bank/Home_bank.php';

class Bank_soal extends Home_bank{
  function index() {
    $db_bank = $this->load->database('bank', TRUE);
    $db_bank->select('ujian_id');
    $db_bank->select('judul');
    $data['soal'] = $db_bank->get('soal')->result();
    $this->load->view('bank/bank/soal', $data);
  }
  
  function buka() {
    $ujian_id = $this->input->get('ujian_id');
    $db_bank = $this->load->database('bank', TRUE);
    $db_bank->where('ujian_id', $ujian_id);    
    $data['soal'] = $db_bank->get('soal')->row();
    $this->load->view('bank/bank/browse', $data);
  }

  function hapus() {
    $ujian_id = $this->input->get('ujian_id');
    $db_bank = $this->load->database('bank', TRUE);
    $db_bank->where('ujian_id', $ujian_id);    
    $soal = $db_bank->get('soal')->row();

    // hapus gambar
    $this->_hapus_gambar($soal->konten);

    // hapus soal
    $db_bank->where('ujian_id', $ujian_id);    
    $db_bank->delete('soal');

    // redirect
    $this->session->pesan = '<div class="alert alert-success">
    Soal telah terhapus.
    </div>';
    $this->session->mark_as_flash('pesan');
    redirect('?d=bank&c=bank_soal');
  }

  private function _hapus_gambar($konten) {
    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="UTF-8">' . $konten);
    $tag_img = $dom->getElementsByTagName('img');
    foreach($tag_img as $img){
      $path = FCPATH . $img->getAttribute('src');
      if(file_exists($path)){
        unlink($path);
      }
    }
  }
}