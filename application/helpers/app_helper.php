<?php

function flip_tanggal($prm_tgl, $separator1 = '/', $separator2 = '/'){
	$tgl = explode($separator1, $prm_tgl);
	return $tgl[2] . $separator2 . $tgl[1] . $separator2 . $tgl[0];
}

// mengambil isi dari elemen DOM
function get_inner_html($node) {
    $innerHTML= '';
    $children = $node->childNodes;
    
    foreach ($children as $child){
        $innerHTML .= $child->ownerDocument->saveXML( $child );
    }
    
    return $innerHTML;
}

// otentikasi ke webservice
function login_webservice($login, $password){
	$CI =& get_instance();
    $acc_login = get_app_config('LOGIN_SERVICE');
    $acc_pass = get_app_config('PASS_SERVICE');
	return(($login == $acc_login) && ($password = $acc_pass));
}

// String unik dengan panjang tertentu
function string_acak($pjg) {
    $char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $char = str_shuffle($char);
    for($i = 0, $rand = '', $l = strlen($char) - 1; $i < $pjg; $i ++) {
        $rand .= $char{mt_rand(0, $l)};
    }
    return $rand;
}

// penambahan tanggal dengan sejumlah menit tertentu
function interval_tgl($tgl_mulai, $interval){
	$time = new DateTime($tgl_mulai);
	$time->add(new DateInterval('PT' . $interval . 'M'));
	return $time->format('Y-m-d H:i');
}

// Mendapatkan jam akhir pada tanggal tertentu
function jam_akhir($tgl){
  $tmp = explode(' ', $tgl);
  return $tmp[0] . ' ' . '23:59:59';
}

// konversi kode jenis ujian
function jenis_ujian($kode){
    $arr = array('0' => 'Ulangan Harian', '1' => 'USBN');
    return $arr[$kode];
}

// konversi digit bulan menjadi string
function bulan($digit){
    $arr = array(
        '01'=> 'Januari',
        '02'=> 'Februari',
        '03'=> 'Maret',
        '04'=> 'April',
        '05'=> 'Mei',
        '06'=> 'Juni',
        '07'=> 'Juli',
        '08'=> 'Agustus',
        '09'=> 'September',
        '10'=> 'Oktober',
        '11'=> 'Nopember',
        '12'=> 'Desember',
    );
    return $arr[$digit];
}

// konversi tgl mysql ke string
function mysqldate_to_str($date){
    $tmp = explode(' ', $date);
    $tgl = flip_tanggal($tmp[0],'-', '-');
    $jam = $tmp[1];
    
    $dmy = explode('-', $tgl);
    return $dmy[0] . ' ' . bulan($dmy[1]) . ' ' . $dmy[2] . ', ' . $jam;
}

// deteksi menu css yang aktif
function css_class_active($class, $get = 'c'){
    $CI =& get_instance();
    if($CI->input->get($get) == $class){
        return 'active';
    }else{
        return '';
    }
}

// Ambil nilai konfigurasi dari tabel
function get_app_config($id){
    $CI =& get_instance();
    $CI->db->select('nilai_konfig');
    $CI->db->where('konfig_id', $id);
    return $CI->db->get('konfig')->row()->nilai_konfig;
}

// Atur nilai konfigurasi
function modif_app_config($id, $val){
    $CI =& get_instance();
    $CI->db->where('konfig_id', $id);
    $CI->db->set('nilai_konfig', $val);
    return $CI->db->update('konfig');
}

// Menghasilkan output json
function json_output($statusHeader,$response){
    $ci =& get_instance();
    $ci->output->set_content_type('application/json');
    $ci->output->set_status_header($statusHeader);
    // $ci->output->set_output(json_encode($response));
    echo json_encode($response);
}

function ekstrak_zip($zip_file, $dir_target){
    $zip = new ZipArchive;
    $res = $zip->open($zip_file);
    if ($res === TRUE) {
        $zip->extractTo($dir_target);
        $zip->close();
        return true;
    } else {
        return false;
    }
}

function arsipkan_folder($dir, $zip_file = 'file.zip'){
    
    
    // Get real path for our folder
    $rootPath = realpath($dir);
    
    // Initialize archive object
    $zip = new ZipArchive();
    $zip->open($zip_file, ZipArchive::CREATE);
    
    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach ($files as $name => $file)
    {
        // Skip directories (they would be added automatically)
        if (!$file->isDir())
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);
            
            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }
    
    // Zip archive will be created only after closing object
    $zip->close();
    
    
}

function salin_folder($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                salin_folder($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// Function to remove folders and files 
function rrmdir($dir) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file)
          if ($file != "." && $file != "..") rrmdir("$dir/$file");
          rmdir($dir);
    }
    else if (file_exists($dir)) unlink($dir);
}

// Function to Copy folders and files       
function rcopy($src, $dst) {
    if (file_exists ( $dst )) rrmdir ( $dst );
    if (is_dir ( $src )) {
        mkdir ( $dst );
        $files = scandir ( $src );
        foreach ( $files as $file )
        if ($file != "." && $file != "..")
        rcopy ( "$src/$file", "$dst/$file" );
    } else if (file_exists ( $src ))
    copy ( $src, $dst );
}

function data_do_pemulihan_data($data, $peserta = true){
    ini_set('max_execution_time', 0);
    $CI =& get_instance();
    foreach($data as $nama_tabel => $tabel){
      if(($nama_tabel == 'peserta') and (!$peserta)) continue;
      $jml_baris = count($tabel);
      foreach($tabel as $k => $row){
        $percent = round(($k / $jml_baris * 100), 2) . '%';
        $progres_bar = '<div id="progress" style="width:500px; border:1px solid #ccc;"> \
        <div style="width:'. $percent .';background-color:#ddd;">&nbsp;</div> \
        </div>';
        myob('<p>memproses tabel ' . $nama_tabel . ' '. $percent .'</p>' . $progres_bar);
        $d = array();
        foreach($row as $kolom){
          $d[] = $CI->db->escape($kolom);
        }
        $data = '(' . implode(',', $d) . ')';
        $sql = "INSERT INTO $nama_tabel VALUES $data";
        if ($CI->db->conn_id->ping() === FALSE){
          usleep(1);
          $CI->db->reconnect();
        }
        $CI->db->query($sql);
      }
    }
}

function data_do_reset($hapus_peserta_jawaban = true, $hapus_peserta = true){
    $CI =& get_instance();
    // hapus data peserta dan ujian
    if($hapus_peserta_jawaban) $CI->db->query('DELETE FROM peserta_jawaban');
    if($hapus_peserta) $CI->db->query('DELETE FROM peserta');
    $CI->db->query('DELETE FROM ujian'); // cukup hapus ujian, tabel soal dan pilihan akan ikut terhapus (FK)
    // hapus folder gambar
    rrmdir(FCPATH . 'images/');
    mkdir(FCPATH . 'images');
    
}

function bersihkan_gambar($ujian_id){
  
  $CI =& get_instance();
  $sql = "SELECT konten FROM ujian WHERE ujian_id = '$ujian_id'";
  $r = $CI->db->query($sql)->row();
  if($r->konten !== null){
    
    $dom = new DOMDocument();
    $dom->loadHTML($r->konten);
    $tag_gambar = $dom->getElementsByTagName("img");
    
    foreach($tag_gambar as $img){
      $gbr = FCPATH . $img->getAttribute('src');
      // log_message('custom', 'menghapus : ' . $gbr);
      if(file_exists($gbr)){
        unlink($gbr);
        log_message('custom', 'menghapus :' . $gbr);
      }else{
        log_message('custom', $gbr . ' tidak ada');
      }
    }
  }
}

function myob($var) {
  echo '<div id="message"></div>';
  echo '<script>document.getElementById(\'message\').innerHTML = \''. $var .'\';</script>';
  flush();
  ob_flush();
  usleep(1);
}