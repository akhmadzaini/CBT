<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/proktor_sekolah/Home_proktor_sekolah.php';
require_once FCPATH . 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Nilai extends Home_proktor_sekolah{

  function download_essay() {
    $sql = 'SELECT ujian_id, judul, status_soal, mulai, selesai, jml_soal
    FROM ujian WHERE judul <> "" ORDER BY mulai ASC';
    $data['ujian'] = $this->db->query($sql)->result();
    
    // ambil data pengelompokan
    $sql = "SELECT DISTINCT kelas,nama_sekolah FROM peserta 
    ORDER BY  nama_sekolah, kelas ASC";
    $data['kelompok'] = $this->db->query($sql)->result();
    
    $this->load->view('proktor_sekolah/download_essay', $data);
  }

  function do_download_essay() {
    $ujian_id = $this->input->get('ujian_id');
    $kelas = $this->input->get('kelas');

    $sql = "SELECT judul FROM ujian WHERE ujian_id = '$ujian_id'";
    $data = $this->db->query($sql)->row();

    // Create new Spreadsheet object
    $spreadsheet = new Spreadsheet();

    // Atur judul kolom
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('B2', 'Mapel')
    ->setCellValue('C2', $data->judul)
    ->setCellValue('B3', 'Kelas')
    ->setCellValue('C3', $kelas)
    ->setCellValue('B5', 'Nilai Maksimal')
    ->setCellValue('B6', 'No')
    ->setCellValue('C6', 'NIS')
    ->setCellValue('D6', 'Login')
    ->setCellValue('E6', 'Nama');

    // Atur judul kolom yang dinamis
    $sql = "SELECT no_soal, skor FROM soal 
            WHERE essay = 1 AND ujian_id = '$ujian_id' 
            ORDER BY no_soal";
    $data = $this->db->query($sql)->result();

    $kolom = 6;
    foreach($data as $r){
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValueByColumnAndRow($kolom, 5, $r->skor)
      ->setCellValueByColumnAndRow($kolom++, 6, $r->no_soal);
    }

    // Atur nama-nama siswa
    $sql = "SELECT a.nis, a.login, a.nama, a.nama_sekolah, b.judul
            FROM peserta a
            LEFT JOIN ujian b ON a.ujian_id = b.ujian_id
            WHERE a.ujian_id = '$ujian_id'
            AND a.kelas = '$kelas'
            ORDER BY a.nis";
    $data = $this->db->query($sql)->result();
    $baris = 7;
    foreach($data as $r){
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValueByColumnAndRow(2, $baris, $baris - 6)
      ->setCellValueByColumnAndRow(3, $baris, $r->nis)
      ->setCellValueByColumnAndRow(4, $baris, strtoupper($r->login))
      ->setCellValueByColumnAndRow(5, $baris++, strtoupper($r->nama));
      $nama_sekolah=strtoupper($r->nama_sekolah);
      $judul=strtoupper($r->judul);
    }

    // Atur lebar kolom
    $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(5);
    $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(15);
    $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(15);
    $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(45);

    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$nama_sekolah.'""'.$judul.'"essay.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;
  }  

  function upload_essay() {
    // ambil data ujian
    $this->db->select('ujian_id');
    $this->db->select('judul');
    $this->db->select('mulai as tgl');
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
          if(empty($v2)) continue;
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