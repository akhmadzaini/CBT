<?php 
if (!defined('BASEPATH')) {	exit('No direct script access allowed'); }

class Word extends CI_Controller {
  
	function index(){
		$this->ip = $this->input->ip_address();
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->helper('file');
		$config['functions']['blogger.getUsersBlogs'] = array('function' => 'Word.getUsersBlogs');
		$config['functions']['metaWeblog.newPost'] = array('function' => 'Word.simpan');
		$config['functions']['metaWeblog.editPost'] = array('function' => 'Word.simpan');
		$config['functions']['metaWeblog.getPost'] = array('function' => 'Word.get_post');
		$config['functions']['metaWeblog.newMediaObject'] = array('function' => 'Word.simpan_media');
		$config['object'] = $this;
		$this->xmlrpcs->initialize($config);
		$this->xmlrpcs->serve();
	}
  
	function getUsersBlogs($req) {
		$prm = $req->output_parameters();
		if (!login_webservice($prm['1'], $prm['2'])) {
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}
    $response = array(array(
      'isAdmin' => array('1', 'int'),
      'url'  => array(base_url(),'string'),
      'blogName'  => array('USBN','string'),
      'blogid'    => array('1','int'),
      'xmlrpc'    => array(site_url('xmlrpc'),'string')                  
    ),'struct');
    
    return $this->xmlrpc->send_response($response);  
	}
  
	function simpan($req){
		$konten = $req->decode_message($req->params[3]);
		$login = $req->decode_message($req->params[1]);
    $pass = $req->decode_message($req->params[2]);
    
		if (!login_webservice($login, $pass)) {
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}
    
		$lembar_soal = $konten['description'];
    
		$ujian = $this->__parse_soal($lembar_soal);
    
		if($this->__lolos_verifikasi_ujian($ujian)){
      $ujian['judul'] = trim($konten['title']);
			$this->__update_db_soal($lembar_soal, $ujian);
			log_message('custom', 'Soal berhasil disimpan, ip : ' . $this->ip);
			$response = array($ujian['ujian_id'], 'string');
			return $this->xmlrpc->send_response($response);
		}else{			
			return $this->xmlrpc->send_error_message('1', 'Gagal menyimpan soal');
		}
	}
  
	function get_post($req){
		$post_id = $req->decode_message($req->params[0]);
		$user = $req->decode_message($req->params[1]);
		$pass = $req->decode_message($req->params[2]);
    
    
		if (login_webservice($user, $pass)) {
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}
    
		$r = $this->__get_ujian($post_id);
    
		// konversi ke tanggal yang didukung oleh API
		$dateCreated = date("Y-m-d\TH:i:sO", strtotime($r['tgl_unggah']));
    
		$response = array(
			array(
				'postid' => array($post_id, 'string'),
				'dateCreated' => array($dateCreated, 'dateTime.iso8601'),
				'title' => array($r['ujian_id'], 'string'),
				'description' => array($r['konten'], 'string'),
				'categories' => array(array(''), 'array'),
				'publish' => array(1, 'boolean'),
			),
			'struct'
		);
		// log_message('custom', 'Hasil :: ' . json_encode($response));
		return $this->xmlrpc->send_response($response);		
	}
  
	function simpan_media($req) {
		$login = $req->decode_message($req->params[1]);
		$pass = $req->decode_message($req->params[2]);
    
		if (!login_webservice($login, $pass)) {
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}
    
		// $blogid = $parameters['0'];
		$file = $req->decode_message($req->params[3]);
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    
		//Cek folder dulu apakah ada.
		$y = date("Y");
		$m = date("m");
		$d = date("d");
		$rel_dir = "images/$y/$m/$d/";
		$filename = uniqid() . '.' . $ext;
		$abs_dir = FCPATH . $rel_dir;
    
    
		if ((file_exists($abs_dir) && is_dir($abs_dir)) == FALSE) { 
			mkdir($abs_dir, 0777, true);
		}
    
		// $filename = substr($filename, (strrpos($filename, "/") ? strrpos($filename, "/") + 1 : 0));
		if (write_file($abs_dir . $filename, $file['bits'])) {
			$response = array(
				array(
					'url' => array($rel_dir . $filename, 'string')
				), 'struct'
			);
			return $this->xmlrpc->send_response($response);
		}
		return $this->xmlrpc->send_error_message('2', 'File Failed to Write');
	}
  
	private function __lolos_verifikasi_ujian($ujian){
		// Periksa apakah ID ujian telah tersedia
		$r = $this->__get_ujian($ujian['ujian_id']); 
		if(empty($r)){
			log_message('custom', 'ID Ujian tak ditemukan, IP : ' . $this->ip);
			return FALSE;
		}
    
		// Periksa apakah jumlah soal telah sesuai dengan setting(konfigurasi) di Excel
		if($r['jml_soal'] != count($ujian['soal'])){
			log_message('custom', 'Jumlah soal di ms word tidak sama dengan Excel, IP : ' . $this->ip);
			return FALSE;
		}
    
		// Periksa apakah kondisi soal sudah terkunci
		// if($r['status_soal'] == 2){
      // 	log_message('custom', 'Soal terkunci, IP : ' . $this->ip);
      // 	return FALSE;
      // }
      
      return TRUE;
    }
    
    private function __parse_soal($lembar_soal){
      $dom = new DOMDocument();
      $dom->loadHTML($lembar_soal);
      
      $ujian_id = $dom->getElementsByTagName('td')->item(1)->nodeValue;
      
      // Mengekstrak body soal
      $dom_soal = $dom->getElementsByTagName('table')->item(0)->getElementsByTagName('tr');
      $arr_soal = array();
      $nomor = 1;
      for($n = 1; $n < $dom_soal->length; $n++){
        $kolom = $dom_soal->item($n)->getElementsByTagName('td');
        
        if($kolom->length == 4){
          // Jika baris merupakan baris soal
          $soal = array(	
            'konten' 	=> get_inner_html($kolom->item(1)), 
            'jawaban' => trim(filter_var($kolom->item(2)->nodeValue, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH), " "),
            'skor' 		=> filter_var($kolom->item(3)->nodeValue, FILTER_SANITIZE_NUMBER_FLOAT),
            'essay'		=> 0
          );
          $arr_soal[$nomor++] = array('soal' 		=> $soal,
          'pilihan' 	=> array());
        }else if($kolom->length == 2){
          // Jika baris merupakan baris soal essay
          $soal = array(	'konten' 	=> get_inner_html($kolom->item(1)),
          'jawaban'	=> '',
          'skor'		=> 0,     							
          'essay'		=> 1
        );
        $arr_soal[$nomor++] = array('soal' 		=> $soal,
        'pilihan' 	=> array());
      }
      else{
        // Jika baris merupakan baris jawaban
        $idx = trim(filter_var($kolom->item(1)->nodeValue, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH), " ");
        $jawab = get_inner_html($kolom->item(2));
        $arr_soal[count($arr_soal)]['pilihan'][$idx] = $jawab;
      }
    }
    
    return array(	'ujian_id'	=> $ujian_id,
  );
}

private function __get_ujian($ujian_id){
  $q = $this->db->query("SELECT * FROM ujian WHERE ujian_id = '$ujian_id'");
  return $q->row_array();
}

private function __update_db_soal($lembar_soal, $ujian){
  $ujian_id = $ujian['ujian_id'];
  $arr_soal = $ujian['soal'];
  
  // Update data ujian
  $sql = "UPDATE ujian 
  SET judul = '$ujian[judul]', konten = '$lembar_soal', status_soal = 1, tgl_unggah = NOW()
  WHERE ujian_id = '$ujian_id' ";
  $this->db->query($sql);
  
  // Reset pilihan soal
  $sql = "DELETE FROM pilihan_jawaban WHERE ujian_id = '$ujian_id'";
  $this->db->query($sql);
  
  // Reset soal
  $sql = "DELETE FROM soal WHERE ujian_id = '$ujian_id'";
  $this->db->query($sql);
  
  // Simpan soal beserta pilihan jawaban
  $sql = "INSERT INTO soal (ujian_id, no_soal, essay, konten, jawaban, skor) VALUES ";
  $sql2 = "INSERT INTO pilihan_jawaban (ujian_id, no_soal, pilihan_ke, konten) VALUES ";
  $baris = array();
  $baris2 = array();
  foreach($arr_soal as $no_soal => $butir){
    $soal = $butir['soal'];
    $baris[] = "('$ujian_id', $no_soal, '$soal[essay]', '$soal[konten]', '$soal[jawaban]', $soal[skor])";
    foreach($butir['pilihan'] as $pilihan_ke => $konten){
      $baris2[] = "('$ujian_id', $no_soal, '$pilihan_ke', '$konten')";
    }
  }
  $sql .= implode(',', $baris);
  $sql2 .= implode(',', $baris2);
  $this->db->query($sql);
  $this->db->query($sql2);
}
}