<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/proktor/Home_proktor.php';
require_once FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Cetak extends Home_proktor{
	function index(){
    $sql = 'SELECT ujian_id, judul, status_soal, mulai, selesai, jml_soal
    FROM ujian ORDER BY mulai DESC';
    $data['ujian'] = $this->db->query($sql)->result();
    $this->load->view('proktor/cetak/index', $data);
  }
  
  function kartu_peserta(){
    $ujian_id = $_GET['ujian_id'];
    $sql = "SELECT nis, login, password, server, nama FROM peserta WHERE ujian_id = '$ujian_id'
    ORDER BY nis ";
    $data = $this->db->query($sql)->result();
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->getColumnDimension('A')->setWidth(2);
    $sheet->getColumnDimension('B')->setWidth(2);
    $sheet->getColumnDimension('C')->setWidth(8);
    $sheet->getColumnDimension('D')->setWidth(2);
    $sheet->getColumnDimension('E')->setWidth(8);
    $sheet->getColumnDimension('F')->setWidth(24);
    $sheet->getColumnDimension('G')->setWidth(2);
    $sheet->getColumnDimension('H')->setWidth(2);
    $sheet->getColumnDimension('I')->setWidth(2);
    $sheet->getColumnDimension('J')->setWidth(2);
    $sheet->getColumnDimension('K')->setWidth(8);
    $sheet->getColumnDimension('L')->setWidth(2);
    $sheet->getColumnDimension('M')->setWidth(8);
    $sheet->getColumnDimension('N')->setWidth(24);
    
    // Border untuk judul
    //$this->__garis_pinggir($sheet, 'A2:F8');
    //$this->__garis_pinggir($sheet, 'C3:C7');
    //$this->__garis_pinggir($sheet, 'I2:M8');
    //$this->__garis_pinggir($sheet, 'J3:J7');
    $sheet->setCellValue('E3', 'DINAS PENDIDIKAN PEMUDA DAN OLAHRAGA');
    $sheet->setCellValue('E4', 'KOTA PROBOLINGGO');
    $sheet->setCellValue('E5', 'KARTU UJIAN PESERTA');
    
    $baris_awal = 2;
    $baris_terakhir = 13;
    foreach($data as $r){
      $this->__garis_pinggir($sheet, "B$baris_awal".":"."G$baris_terakhir");
	  $sheet->mergeCells('E'.($baris_awal + 1).':F'.($baris_awal + 1));	
	  $sheet->mergeCells('E'.($baris_awal + 2).':F'.($baris_awal + 2));	
	  $sheet->mergeCells('E'.($baris_awal + 3).':F'.($baris_awal + 3));		
	  $sheet->setCellValue('E' . ($baris_awal + 1), 'DISDIPENDIK KOTA PROBOLINGGO');
      $sheet->setCellValue('E' . ($baris_awal + 2), get_app_config('NAMA_SEKOLAH'));
      $sheet->setCellValue('E' . ($baris_awal + 3), 'KARTU UJIAN PESERTA');
      $this->__garis_pinggir($sheet, 'C' . ($baris_awal + 1) . ':C' . ($baris_awal + 3));
      $this->__garis_pinggir($sheet, 'C' . ($baris_awal + 6) . ':C' . ($baris_awal + 10));
      $sheet->setCellValue('E' . ($baris_awal + 6), 'USER');
      $sheet->setCellValue('F' . ($baris_awal + 6), $r->login);
      $sheet->setCellValue('E' . ($baris_awal + 7), 'PASS');
      $sheet->setCellValue('F' . ($baris_awal + 7), $r->password);
      $sheet->setCellValue('E' . ($baris_awal + 8), 'SERVER');
      $sheet->setCellValue('F' . ($baris_awal + 8), $r->server);
      $sheet->setCellValue('E' . ($baris_awal + 9), 'NISN');
      $sheet->setCellValue('F' . ($baris_awal + 9), $r->nis);
      $sheet->setCellValue('E' . ($baris_awal + 10), 'NAMA');
      $sheet->setCellValue('F' . ($baris_awal + 10), $r->nama);
	  
	  
      $this->__garis_pinggir($sheet, "J$baris_awal".":"."N$baris_terakhir");
	  $sheet->mergeCells('M'.($baris_awal + 1).':N'.($baris_awal + 1));	
	  $sheet->mergeCells('M'.($baris_awal + 2).':N'.($baris_awal + 2));	
	  $sheet->mergeCells('M'.($baris_awal + 3).':N'.($baris_awal + 3));		
	  $sheet->setCellValue('M' . ($baris_awal + 1), 'DISDIPENDIK KOTA PROBOLINGGO');
      $sheet->setCellValue('M' . ($baris_awal + 2), get_app_config('NAMA_SEKOLAH'));
      $sheet->setCellValue('M' . ($baris_awal + 3), 'KARTU MEJA');
      $this->__garis_pinggir($sheet, 'K' . ($baris_awal + 1) . ':K' . ($baris_awal + 3));
      $this->__garis_pinggir($sheet, 'K' . ($baris_awal + 6) . ':K' . ($baris_awal + 10));
      $sheet->setCellValue('M' . ($baris_awal + 6), 'USER');
      $sheet->setCellValue('N' . ($baris_awal + 6), $r->login);
      $sheet->setCellValue('M' . ($baris_awal + 7), 'PASS');
      $sheet->setCellValue('N' . ($baris_awal + 7), $r->password);
      $sheet->setCellValue('M' . ($baris_awal + 8), 'SERVER');
      $sheet->setCellValue('N' . ($baris_awal + 8), $r->server);
      $sheet->setCellValue('M' . ($baris_awal + 9), 'NISN');
      $sheet->setCellValue('N' . ($baris_awal + 9), $r->nis);
      $sheet->setCellValue('M' . ($baris_awal + 10), 'NAMA');
      $sheet->setCellValue('N' . ($baris_awal + 10), $r->nama);
      
      $tambahan = ($baris_terakhir % 43 == 0) ? 6 : 3;
      $baris_awal = $baris_terakhir + $tambahan;
      $baris_terakhir = $baris_awal + 11;
    }
    
    $writer = new Xlsx($spreadsheet);
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=kartu_peserta_$ujian_id.xlsx");
    $writer->save('php://output');
    
  }
  
  function presensi(){
    $ujian_id = $_GET['ujian_id'];
    $sql = "SELECT nis, nama FROM peserta WHERE ujian_id = '$ujian_id'
    ORDER BY nis ";
    $data = $this->db->query($sql)->result();
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Atur lebar kolom
    $sheet->getColumnDimension('B')->setWidth(21);
    $sheet->getColumnDimension('C')->setWidth(30);
    $sheet->getColumnDimension('D')->setWidth(16);
    $sheet->getColumnDimension('E')->setWidth(16);
    
    // merger cell
    $sheet->mergeCells('A2:E2');
    $sheet->mergeCells('A3:E3');
    $sheet->mergeCells('A4:E4');
    $sheet->mergeCells('A5:E5');
    $sheet->mergeCells('D8:E8');
    
    // Atur judul
    $sheet->setCellValue('A2', 'DINAS PENDIDIKAN PEMUDA DAN OLAHRAGA');
    $sheet->setCellValue('A3', 'KOTA PROBOLINGGO');
    $sheet->setCellValue('A4', get_app_config('NAMA_SEKOLAH'));
    $sheet->setCellValue('A5', 'DAFTAR KEHADIRAN');
    
    // format rata
    $this->__format_rata_tengah($sheet, 'A2:D8');

    $sheet->setCellValue('A8', 'No.');
    $sheet->setCellValue('B8', 'NISN');
    $sheet->setCellValue('C8', 'NAMA');
    $sheet->setCellValue('D8', 'TTD');
    $sheet->getRowDimension('8')->setRowHeight(30);
    
    // Atur isian data
    foreach($data as $k => $r){
      $no = $k + 1;
      $baris = $k + 9;
      $sheet->getRowDimension($baris)->setRowHeight(30);
      $sheet->setCellValue('A' . $baris, "$no");
      $sheet->setCellValue('B' . $baris, $r->nis);
      $sheet->setCellValue('C' . $baris, $r->nama);
      $kolom = ($no % 2 == 0) ? 'E' : 'D';
      $sheet->setCellValue($kolom . $baris, $no . '. ');
      $this->__garis_pinggir($sheet, "D$baris:E$baris");
    }

    // format border
    $this->__garis_semua($sheet, 'A8:E8');
    $this->__garis_semua($sheet, 'A9:C' . (8 + count($data)));

    
    // luaran
    $writer = new Xlsx($spreadsheet);
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=presensi_$ujian_id.xlsx");
    $writer->save('php://output');
    
  }

  function berita_acara(){
    require_once FCPATH . 'vendor/setasign/fpdf/fpdf.php';
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(40,10,'Berita Acara Ujian');
    $pdf->Output();
  }
  
  private function __garis_pinggir($sheet, $area){
    $styleArray = [
      'borders' => [
        'outline' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '000'],
        ],
      ],
    ];
    $sheet->getStyle($area)->applyFromArray($styleArray);
  }

  private function __garis_semua($sheet, $area){
    $styleArray = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '000'],
        ],
      ],
    ];
    $sheet->getStyle($area)->applyFromArray($styleArray);
  }
  
  private function __format_rata_tengah($sheet, $area){
    $format = array(
      'font' => [
        'bold' => true,
      ],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ]
      );
    $sheet->getStyle($area)->applyFromArray($format);
  }
}