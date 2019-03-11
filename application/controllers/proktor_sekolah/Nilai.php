<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/proktor_sekolah/Home_proktor_sekolah.php';
require_once FCPATH . 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class Nilai extends Home_proktor_sekolah{

  function upload_essay() {
    // ambil data ujian
    $this->db->select('ujian_id');
    $this->db->select('judul');
    $data['ujian'] = $this->db->get('ujian')->result();
    
    // ambil data server
    $this->db->distinct();
    $this->db->select('server');
    $data['server'] = $this->db->get('peserta')->result();

    $this->load->view('proktor_sekolah/upload_essay', $data);
  }

  function submit_upload_essay() {
    $ujian_id = $this->input->post('ujian_id');
    // $server = $this->input->post('server');
    $config['upload_path'] = './public/';
    $config['allowed_types'] = 'xlsx';
    $config['file_name'] = string_acak(10);
    $this->load->library('upload', $config);
    $this->upload->do_upload('excel');
    $file = $this->upload->data();
    $file = $file['full_path'];
    
    $spreadsheet = IOFactory::load($file);
    unlink($file);
    $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $header = $data[6];
    $no_soal = array();
    $excl = array('A', 'B', 'C', 'D', 'E');
    foreach($header as $k => $v){
      if(!in_array($k, $excl)){
        $no_soal[$k] = $v;
      }
    }

    $nilai_essay = array();
    foreach($data as $k => $v){
      if($k > 6) {
        foreach($no_soal as $k2 => $v2){
          $nilai = array();
          $nilai['ujian_id'] = $ujian_id;
          $nilai['nis'] = $v['C'];
          $nilai['login'] = $v['D'];
          $nilai['no_soal'] = $v2;
          $nilai['essay_skor'] = $v[$k2];
          $nilai_essay[] = $nilai;
        }
      }
    }    
    
    $jml_query = 0;
    $jml_query_gagal = 0;
    $sql = insert_or_update('peserta_jawaban', ['ujian_id', 'nis', 'login'], $nilai_essay);
    $this->db->trans_start();
    foreach ($sql as $key => $value) {
      $nis = $nilai_essay[$key]['nis'];
      $login = $nilai_essay[$key]['login'];
      $sql2 = "SELECT COUNT(nis) AS jml FROM peserta
                WHERE ujian_id = '$ujian_id' 
                AND nis='$nis'
                AND login = '$login'";
      $r = $this->db->query($sql2)->row();
      if($r->jml > 0){
        $this->db->query($value);
        $jml_query++;
      }else{
        $jml_query_gagal++;
      }
    }
    $this->db->trans_complete();
    $this->session->pesan = '
    <div class="alert alert-info">
      <strong>Unggah selesai</strong>, sistem telah memproses '.$jml_query.' data dan gagal memproses '. $jml_query_gagal .' data.
    </div>
    ';
		$this->session->mark_as_flash('pesan');
		redirect('?d=proktor_sekolah&c=nilai&m=upload_essay');
  }

}