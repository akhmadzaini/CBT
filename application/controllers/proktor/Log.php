<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require_once APPPATH . 'controllers/proktor/Home_proktor.php';

class Log extends Home_proktor{
  function sync_tarik() {
    $db_log = $this->load->database('log', TRUE);
    $db_log->order_by('mulai', 'desc');
    $data['log'] = $db_log->get('sinkron_server_tarik')->result();
    $this->load->view('proktor/log/sync_tarik', $data);
  }

  function del_sync_tarik() {
    $db_log = $this->load->database('log', TRUE);
    $db_log->query('DELETE FROM sinkron_server_tarik');
    redirect('?d=proktor&c=log&m=sync_tarik');
  }
  
  function sync_kirim() {
    // membuat array ujian
    $rs = $this->db->query('SELECT ujian_id, judul FROM ujian')->result();
    $arr_ujian = array();
    foreach($rs as $r){
      $arr_ujian[$r->ujian_id] = $r->judul;
    }
    
    // membuat array sekolah
    $rs = $this->db->query('SELECT DISTINCT server, nama_sekolah FROM peserta')->result();
    $arr_sekolah = array();
    foreach($rs as $r){
      $arr_sekolah[$r->server] = $r->nama_sekolah;
    }

    $db_log = $this->load->database('log', TRUE);
    $db_log->order_by('mulai', 'desc');
    $data['log'] = $db_log->get('sinkron_server_kirim')->result();
    $data['arr_ujian'] = $arr_ujian;
    $data['arr_sekolah'] = $arr_sekolah;
    $this->load->view('proktor/log/sync_kirim', $data);
  }

  function del_sync_kirim() {
    $db_log = $this->load->database('log', TRUE);
    $db_log->query('DELETE FROM sinkron_server_kirim');
    redirect('?d=proktor&c=log&m=sync_kirim');
  }
}