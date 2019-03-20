<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Home_bank extends CI_Controller{
	function __construct() {
		parent::__construct();
		if((empty($this->session->login)) || ($this->session->akses != 'bank')){
			$this->session->pesan = 'harus_login';
			$this->session->mark_as_flash('pesan');
      redirect(site_url('?c=login&m=login_bank'));
      die();
    }
    
    // cek password bank soal
    $this->load->library('encryption');
    $db_bank = $this->load->database('bank', TRUE);
    $db_bank->select('password');
    $db_bank->where('login',  $this->session->login);
    $r = $db_bank->get('pengguna')->row();
    if($this->encryption->decrypt($r->password) != $this->session->password){
      redirect(site_url('?c=login&m=logout_bank'));
    }
	}
}