<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Home_proktor extends CI_Controller{
	function __construct() {
		parent::__construct();
		if((empty($this->session->login)) || ($this->session->akses != 'proktor')){
			$this->session->pesan = 'harus_login';
			$this->session->mark_as_flash('pesan');
			redirect(site_url('?c=login&m=login_proktor'));
		}
	}
}