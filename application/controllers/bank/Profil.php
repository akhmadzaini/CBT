<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/bank/Home_bank.php';

class Profil extends Home_bank{
	function edit(){
    $this->load->library('encryption');
    $db_bank = $this->load->database('bank', TRUE);
    $db_bank->where('login', $this->session->login);
		$data['profil'] =  $db_bank->get('pengguna')->row();
		$this->load->view('bank/edit_profil.php', $data);
	}

	function submit_edit(){
    $this->load->library('encryption');
		$post = $this->input->post();
    $secret = $this->config->item('encryption_key');
    $db_bank = $this->load->database('bank', TRUE);
		$db_bank->set('nama', $post['nama']);
		$db_bank->set('email', $post['email']);
		if(!empty($post['password'])){
			$db_bank->set('password', $this->encryption->encrypt($post['password']));
		}
		$db_bank->where('login',  $this->session->login);
		$db_bank->update('pengguna');
		$this->session->pesan = 'sukses';
		$this->session->mark_as_flash('pesan');
		redirect('?d=bank&c=profil&m=edit');
	}

}