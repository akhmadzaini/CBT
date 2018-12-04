<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/proktor/Home_proktor.php';
require_once FCPATH . 'vendor/autoload.php';

class PDFKU extends FPDF {
  function Footer() {
      // Go to 1.5 cm from bottom
      $this->SetY(-15);
      // Select Arial italic 8
      $this->SetFont('Arial','I',8);
      // Print centered page number
      $this->Cell(0,10,'Halaman '.$this->PageNo() . " dari {nb}" ,0,0,'C');
  }
}

class Cetak extends Home_proktor{
	function index(){
    $sql = 'SELECT ujian_id, judul, status_soal, mulai, selesai, jml_soal
    FROM ujian WHERE judul <> "" ORDER BY mulai ASC';
    $data['ujian'] = $this->db->query($sql)->result();
    
    // ambil data pengelompokan
    $sql = "SELECT DISTINCT kelas FROM peserta 
    ORDER BY  kelas ASC";
    $data['kelompok'] = $this->db->query($sql)->result();

    $sql = "SELECT DISTINCT sesi FROM peserta 
    ORDER BY sesi";
    $data['kelompok2'] = $this->db->query($sql)->result();
    
    $this->load->view('proktor/cetak/index', $data);
  }
  
  function kartu_peserta(){
    $ujian_id = $_GET['ujian_id'];
    $sql = "SELECT nis, login, password, server, sesi, nama FROM peserta WHERE ujian_id = '$ujian_id'";
    $data = $this->db->query($sql)->result();
    
    
    $siswa = array();
    $no = 1;
    foreach($data as $k => $r){
      $siswa[]=array($r->login,$r->password,$r->sesi,$r->server,$r->nis,substr($r->nama, 0, 25));
    }
    //$siswa[$r][5]
    
    require_once FCPATH . 'vendor/setasign/fpdf/fpdf.php';
    $pdf = new FPDF('P', 'mm', 'legal');
    $lembar=intval(count($siswa) / 5);
    for($t=0;$t<=$lembar;$t++){
      $pdf->AddPage();
      $no = 0;
      while($no < 5) {
        if ($no+($t*5) >= count($siswa))
        {
          break;
        }
        $nourut=$no+($t*5);
        
        $pdf->Image('./assets/kartu.jpg',10,$no*55+13,94,0,'JPG');
        $pdf->Image('./assets/kartu.jpg',105,$no*55+13,94,0,'JPG');
        $pdf->Image(base_url('assets/') . get_app_config('LOGO_SEKOLAH'),16,$no*55+16,11,0,'PNG');
        $pdf->Image(base_url('assets/') . get_app_config('LOGO_SEKOLAH'),111,$no*55+16,11,0,'PNG');
        $pdf->Image('./assets/foto.png',18,$no*55+37,20,0,'PNG');
        $pdf->Image('./assets/foto.png',113,$no*55+37,20,0,'PNG');
        
        //$pdf->Image('./assets/logo_dinas.png',50,100,100,0,'PNG');
        $pdf -> SetY($no*55+16); 
        $pdf -> SetFont('Arial', '', 9);  
        $pdf->Cell(10 ,2,'',0,0);$pdf->Cell(90 ,4,'DINAS PENDIDIKAN PEMUDA DAN OLAHRAGA',0,0,'C');
        $pdf->Cell(5 ,2,'',0,0);$pdf->Cell(90 ,4,'DINAS PENDIDIKAN PEMUDA DAN OLAHRAGA',0,1,'C');
        $pdf -> SetFont('Arial', 'B', 11);  
        $pdf->Cell(10 ,2,'',0,0);$pdf->Cell(90 ,5,get_app_config('NAMA_SEKOLAH'),0,0,'C');
        $pdf->Cell(5 ,2,'',0,0);$pdf->Cell(90 ,5,get_app_config('NAMA_SEKOLAH'),0,1,'C');
        $pdf -> SetFont('Arial', '', 9);  
        $pdf->Cell(10 ,2,'',0,0);$pdf->Cell(90 ,4,'KARTU UJIAN SISWA',0,0,'C');
        $pdf->Cell(5 ,2,'',0,0);$pdf->Cell(90 ,4,'KARTU TEMPEL MEJA',0,1,'C');
        
        $pdf->Cell(90 ,3,'_____________________________________________',0,0,'C');
        $pdf->Cell(8 ,3,'',0,0,'C');
        $pdf->Cell(90 ,3,'_____________________________________________',0,1,'C');
        $pdf->Cell(189 ,6,'',0,1);//end of line
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(30 ,4,'',0,0);$pdf->Cell(10 ,4,'User',0,0);$pdf->Cell(3 ,4,':',0,0);
        $pdf->Cell(30 ,4,$siswa[$nourut][0],0,0);
        $pdf->Cell(55 ,4,'',0,0);$pdf->Cell(10 ,4,'User',0,0);$pdf->Cell(3 ,4,':',0,0);
        $pdf->Cell(30 ,4,$siswa[$nourut][0],0,1);
        $pdf->Cell(30 ,4,'',0,0);$pdf->Cell(10 ,4,'Pass',0,0);$pdf->Cell(3 ,4,':',0,0);
        $pdf->Cell(30 ,4,$siswa[$nourut][1],0,0);
        $pdf->Cell(55 ,4,'',0,0);$pdf->Cell(10 ,4,'Pass',0,0);$pdf->Cell(3 ,4,':',0,0);
        $pdf->Cell(30 ,4,$siswa[$nourut][1],0,1);
        $pdf->Cell(30 ,4,'',0,0);$pdf->Cell(10 ,4,'Sesi',0,0);$pdf->Cell(3 ,4,':',0,0);
        $pdf->Cell(30 ,4,$siswa[$nourut][2],0,0);
        $pdf->Cell(55 ,4,'',0,0);$pdf->Cell(10 ,4,'Sesi',0,0);$pdf->Cell(3 ,4,':',0,0);
        $pdf->Cell(30 ,4,$siswa[$nourut][2],0,1);
        $pdf->Cell(30 ,4,'',0,0);$pdf->Cell(10 ,4,'Server',0,0);$pdf->Cell(3 ,4,':',0,0);
        $pdf->Cell(30 ,4,$siswa[$nourut][3],0,0);
        $pdf->Cell(55 ,4,'',0,0);$pdf->Cell(10 ,4,'Server',0,0);$pdf->Cell(3 ,4,':',0,0);
        $pdf->Cell(30 ,4,$siswa[$nourut][3],0,1);
        $pdf->Cell(30 ,4,'',0,0);$pdf->Cell(10 ,4,'NISN',0,0);$pdf->Cell(3 ,4,':',0,0);
        $pdf->Cell(30 ,4,$siswa[$nourut][4],0,0);
        $pdf->Cell(55 ,4,'',0,0);$pdf->Cell(10 ,4,'NISN',0,0);$pdf->Cell(3 ,4,':',0,0);
        $pdf->Cell(30 ,4,$siswa[$nourut][4],0,1);
        $pdf->Cell(30 ,4,'',0,0);$pdf->Cell(10 ,4,'Nama',0,0);$pdf->Cell(3 ,4,':',0,0);
        $pdf->Cell(30 ,4,$siswa[$nourut][5],0,0);
        $pdf->Cell(55 ,4,'',0,0);$pdf->Cell(10 ,4,'Nama',0,0);$pdf->Cell(3 ,4,':',0,0);
        $pdf->Cell(30 ,4,$siswa[$nourut][5],0,1);
        
        $no++;
      }
    }	
    
    $pdf->Output();
    
    
    
    
  }
  
  function presensi(){
    $ujian_id = $_GET['ujian_id'];
    $sesi = $_GET['sesi'];
    $sql = "SELECT nis, nama FROM peserta 
    WHERE ujian_id = '$ujian_id'
    AND sesi = '$sesi'
    ORDER BY  kelas, nama";
    $data = $this->db->query($sql)->result();
    
    $siswa = array();
    $no = 1;
    foreach($data as $k => $r){
      $siswa[]=array($r->nis,$r->nama);
    }
    //$siswa[$r][5]
    
    require_once FCPATH . 'vendor/setasign/fpdf/fpdf.php';
    $pdf = new FPDF();
    $lembar=intval((count($siswa)-1) / 20);
    for($t=0;$t<=$lembar;$t++){
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',13);
      $pdf->Image(base_url('assets/') . get_app_config('LOGO_SEKOLAH'),17,13,15,0,'PNG');
      
      //$pdf->Image('./assets/logo_dinas.png',50,100,100,0,'PNG');
      $pdf->Cell(189 ,4,'',0,1);//end of line
      
      $pdf->Cell(10 ,6,'',0,0);$pdf->Cell(189 ,7,'DINAS PENDIDIKAN PEMUDA DAN OLAHRAGA KOTA PROBOLINGGO',0,1,'C');
      $pdf->Cell(10 ,6,'',0,0);$pdf->Cell(189 ,7,'DAFTAR HADIR UJIAN________________________________CBT',0,1,'C');
      $pdf->Cell(10 ,6,'',0,0);$pdf->Cell(189 ,7,'TAHUN PELAJARAN 2018/2019',0,1,'C');
      $pdf->Cell(40 ,6,'______________________________________________________________________________',0,1);
      $pdf->Cell(189 ,4,'',0,1);//end of line
      $pdf->SetFont('Arial','',12);
      $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(54 ,6,'1. Kota',0,0);
      $pdf->Cell(3 ,6,':',0,0);
      $pdf->Cell(40 ,6,'PROBOLINGGO',0,1);
      $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(50 ,6,'Sekolah/Madrasah',0,0);
      $pdf->Cell(3 ,6,':',0,0);
      $pdf->Cell(40 ,6,get_app_config('NAMA_SEKOLAH'),0,1);
      $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(50 ,6,'Tempat Ujian',0,0);
      $pdf->Cell(3 ,6,':',0,0);
      $pdf->Cell(40 ,6,'__________________________',0,1);
      $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(50 ,6,'Ruang',0,0);
      $pdf->Cell(3 ,6,':',0,0);
      $pdf->Cell(40 ,6,'__________________________',0,1);
      $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(50 ,6,'Hari / Tanggal',0,0);
      $pdf->Cell(3 ,6,':',0,0);
      $pdf->Cell(40 ,6,'__________________________',0,1);
      $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(50 ,6,'Mata Pelajaran',0,0);
      $pdf->Cell(3 ,6,':',0,0);
      $pdf->Cell(40 ,6,'__________________________',0,1);
      
      
      // Atur isian data
      $pdf->Cell(189 ,4,'',0,1);//end of line
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(3 ,4,'',0,0);$pdf->Cell(7 ,6,'NO',1,0,'C');
      $pdf->Cell(30 ,6,'NOPES',1,0,'C');
      $pdf->Cell(80 ,6,'NAMA',1,0,'C');
      $pdf->Cell(69 ,6,'TANDA TANGAN',1,1,'C');
      $pdf->SetFont('Arial','',11);
      $no = 0;
      while($no < 20) {
        if ($no+($t*20) >= count($siswa))
        {
          break;
        }
        $nourut=$no+($t*20);
        $pdf->Cell(3 ,4,'',0,0);$pdf->Cell(7 ,6,$nourut+1,1,0,'C');
        $pdf->Cell(30 ,6,$siswa[$nourut][0],1,0);
        $pdf->Cell(80 ,6,$siswa[$nourut][1],1,0);
        if (($nourut+1) % 2 != 0){
          $pdf->Cell(69 ,6,($nourut+1).'......',1,1);
        }else{
          $pdf->Cell(69 ,6,($nourut+1).'......',1,1,'C');
        }
        
        
        
        $no++;
      }
      $pdf->Cell(189 ,4,'',0,1);//end of line
      $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(40 ,6,'Yang membuat berita acara (Jabatan,Nama,TTD)',0,1);
      $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(40 ,6,'1. Pengawas',0,0);
      $pdf->Cell(70 ,6,'________________________',0,0);
      $pdf->Cell(40 ,6,'1.___________________',0,1);
      $pdf->Cell(15 ,4,'',0,0);$pdf->Cell(35 ,6,'NIP',0,0);
      $pdf->Cell(70 ,6,'________________________',0,1);
      $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(40 ,6,'2. Proktor',0,0);
      $pdf->Cell(70 ,6,'________________________',0,0);
      $pdf->Cell(40 ,6,'2.___________________',0,1);
      $pdf->Cell(15 ,4,'',0,0);$pdf->Cell(35 ,6,'NIP',0,0);
      $pdf->Cell(70 ,6,'________________________',0,1);
      $pdf->Cell(189 ,4,'',0,1);//end of line
      $pdf->SetFont('Arial','IU',12);
      $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(40 ,6,'Catatan',0,1);
      $pdf->SetFont('Arial','',12);
      $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(40 ,6,'1 Lembar Untuk Sekolah',0,1);
      $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(40 ,6,'1 Lembar Dinas Pendidikan',0,1);
    }	
    
    $pdf->Output();
    
  }
  
  function berita_acara(){
    require_once FCPATH . 'vendor/setasign/fpdf/fpdf.php';
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',13);
    $pdf->Image(base_url('assets/') . get_app_config('LOGO_SEKOLAH'),17,13,15,0,'PNG');
    
    //$pdf->Image('./assets/logo_dinas.png',50,100,100,0,'PNG');
    $pdf->Cell(189 ,4,'',0,1);//end of line
    $pdf->SetFont('Arial','',12);
    
    $pdf->Cell(10 ,6,'',0,0);$pdf->Cell(189 ,7,'DINAS PENDIDIKAN PEMUDA DAN OLAHRAGA KOTA PROBOLINGGO',0,1,'C');
    $pdf->Cell(10 ,6,'',0,0);$pdf->Cell(189 ,7,'DAFTAR HADIR UJIAN________________________________CBT',0,1,'C');
    $pdf->Cell(10 ,6,'',0,0);$pdf->Cell(189 ,7,'TAHUN PELAJARAN 2018/2019',0,1,'C');
    $pdf->Cell(40 ,6,'______________________________________________________________________________',0,1);
    $pdf->Cell(189 ,4,'',0,1);//end of line
    $pdf->Cell(189 ,4,'',0,1);//end of line
    
    $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(189 ,6,'Pada hari ini____________tanggal____________bulan____________tahun 2018',0,1);
    $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(189 ,6,'telah diselenggarakan ujian_____CBT untuk Program Studi____________. Mata Pelajaran',0,1);
    $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(189 ,6,'_________________',0,1);
    $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(189 ,6,'dari pukul____________sampai dengan pukul____________sesi____________',0,1);
    $pdf->Cell(189 ,4,'',0,1);//end of line
    $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(54 ,6,'1. Kota',0,0);
    $pdf->Cell(3 ,6,':',0,0);
    $pdf->Cell(40 ,6,'PROBOLINGGO',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(50 ,6,'Sekolah/Madrasah',0,0);
    $pdf->Cell(3 ,6,':',0,0);
    $pdf->Cell(40 ,6,get_app_config('NAMA_SEKOLAH'),0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(50 ,6,'Tempat Ujian',0,0);
    $pdf->Cell(3 ,6,':',0,0);
    $pdf->Cell(40 ,6,'__________________________',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(50 ,6,'Ruang',0,0);
    $pdf->Cell(3 ,6,':',0,0);
    $pdf->Cell(40 ,6,'__________________________',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(50 ,6,'Jumlah Seharusnya',0,0);
    $pdf->Cell(3 ,6,':',0,0);
    $pdf->Cell(40 ,6,'__________________________',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(50 ,6,'Jumlah Hadir',0,0);
    $pdf->Cell(3 ,6,':',0,0);
    $pdf->Cell(40 ,6,'__________________________',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(50 ,6,'Jumlah Tidak Hadir',0,0);
    $pdf->Cell(3 ,6,':',0,0);
    $pdf->Cell(40 ,6,'__________________________',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(50 ,6,'No.Peserta Tidak Hadir',0,0);
    $pdf->Cell(3 ,6,':',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(40 ,6,'________________________________________________________________________',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(40 ,6,'________________________________________________________________________',0,1);
    $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(40 ,6,'2. Catatan selama pelaksanaan ujian :',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(40 ,6,'Pengawas',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(40 ,6,'________________________________________________________________________',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(40 ,6,'________________________________________________________________________',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(40 ,6,'Proktor',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(40 ,6,'________________________________________________________________________',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(40 ,6,'________________________________________________________________________',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(40 ,6,'Teknisi',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(40 ,6,'________________________________________________________________________',0,1);
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(40 ,6,'________________________________________________________________________',0,1);
    $pdf->Cell(189 ,4,'',0,1);//end of line
    $pdf->Cell(14 ,4,'',0,0);$pdf->Cell(40 ,6,'Yang membuat berita acara (Jabatan,Nama,TTD)',0,1);
    $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(40 ,6,'1. Pengawas',0,0);
    $pdf->Cell(70 ,6,'________________________',0,0);
    $pdf->Cell(40 ,6,'1.___________________',0,1);
    $pdf->Cell(15 ,4,'',0,0);$pdf->Cell(35 ,6,'NIP',0,0);
    $pdf->Cell(70 ,6,'________________________',0,1);
    $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(40 ,6,'2. Proktor',0,0);
    $pdf->Cell(70 ,6,'________________________',0,0);
    $pdf->Cell(40 ,6,'2.___________________',0,1);
    $pdf->Cell(15 ,4,'',0,0);$pdf->Cell(35 ,6,'NIP',0,0);
    $pdf->Cell(70 ,6,'________________________',0,1);
    $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(40 ,6,'3. Teknisi',0,0);
    $pdf->Cell(70 ,6,'________________________',0,0);
    $pdf->Cell(40 ,6,'3.___________________',0,1);
    $pdf->Cell(15 ,4,'',0,0);$pdf->Cell(35 ,6,'NIP',0,0);
    $pdf->Cell(70 ,6,'________________________',0,1);
    $pdf->Cell(189 ,4,'',0,1);//end of line
    $pdf->SetFont('Arial','IU',12);
    $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(40 ,6,'Catatan',0,1);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(40 ,6,'1 Lembar Untuk Sekolah',0,1);
    $pdf->Cell(10 ,4,'',0,0);$pdf->Cell(40 ,6,'1 Lembar Dinas Pendidikan',0,1);
    
    
    $pdf->Output();
  }
  
  function essay(){
    
    $post = $this->input->get();
    // Ambil nama ujian 
    $sql = "SELECT judul FROM ujian WHERE ujian_id = '$post[ujian_id]'";
    $r = $this->db->query($sql)->row();
    $nama_ujian = $r->judul;
    
    // ambil data essay
    $sql = "SELECT nis, login, nama FROM peserta WHERE ujian_id = '$post[ujian_id]' AND kelas = '$post[kelas]' ORDER BY kelas,nama ASC";
    $peserta = $this->db->query($sql)->result_array();
    foreach($peserta as $k => $r){
      $sql = "SELECT a.no_soal, b.essay
      FROM soal a
      LEFT JOIN peserta_jawaban b ON a.ujian_id = b.ujian_id
      AND a.no_soal = b.no_soal
      AND b.nis = '$r[nis]'
      AND b.login = '$r[login]'
      WHERE a.ujian_id = '$post[ujian_id]'
      AND a.essay = '1'";
      $essay = $this->db->query($sql)->result_array();
      $peserta[$k]['essay'] = $essay;
    }
    
    $pdf = new PDFKU();
    $pdf->AddPage();
    $pdf->AliasNbPages();
    
    // Atur judul 
    $pdf->SetFont('Arial','B',13);
    $pdf->Image(base_url('assets/') . get_app_config('LOGO_SEKOLAH'),17,13,15,0,'PNG');
    
    $pdf->Cell(10 ,7,'',0,0);$pdf->Cell(0 ,7,'DINAS PENDIDIKAN PEMUDA DAN OLAHRAGA KOTA PROBOLINGGO',0, 1,'C');
    $pdf->Cell(10 ,7,'',0,0);$pdf->Cell(0 ,7,get_app_config('NAMA_SEKOLAH'),0, 1,'C');
    $pdf->Cell(10 ,7,'',0,0);$pdf->Cell(0 ,7,'LEMBAR JAWABAN ESSAY', 'B', 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(10, 7, 'ID UJIAN : ' . $post['ujian_id'], '', 1);
    $pdf->Cell(10, 7, 'NAMA UJIAN :  ' . $nama_ujian , '', 1);
    $pdf->Cell(10, 7, 'KELAS :  ' . $post['kelas'] , '', 1);
    $pdf->Ln(5);
    
    foreach($peserta as $r){
      // Cetak nis dan nama
      $pdf->Cell(40, 7, $r['nis'], 'B');
      $pdf->Cell(0, 7, $r['nama'], 'B', 1);
      
      // Cetak jawaban
      foreach($r['essay'] as $essay){
        $pdf->Cell(10, 7, $essay['no_soal'] . '.');
        $konten = strip_tags($essay['essay']);
        $konten = preg_replace("/&#?[a-z0-9]{2,8};/i"," ", $konten); 
        $pdf->MultiCell(0, 7, $konten, 0, 1);
      }
      $pdf->Ln(5);
    }
    $pdf->Output();
    
  }
  
}