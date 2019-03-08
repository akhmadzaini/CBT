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
  
  function sync_kirim() {
    $db_log = $this->load->database('log', TRUE);
    $db_log->order_by('mulai', 'desc');
    $data['log'] = $db_log->get('sinkron_server_kirim')->result();
    $this->load->view('proktor/log/sync_kirim', $data);
  }
}