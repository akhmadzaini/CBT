<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/proktor/Home_proktor.php';

class Profil extends Home_proktor{
	function edit(){
		$this->db->where('login', $this->session->login);
		$data['profil'] = $this->db->get('proktor')->row();
		$this->load->view('proktor/edit_profil.php', $data);
	}

	function submit_edit(){
		$post = $this->input->post();
		$secret = $this->config->item('encryption_key');
		$this->db->set('nama', $post['nama']);
		$this->db->set('email', $post['email']);
		if(!empty($post['password'])){
			$this->db->set('password', "AES_ENCRYPT('$post[password]', '$secret')", FALSE);
		}
		$this->db->where('login',  $this->session->login);
		$this->db->update('proktor');
		$this->session->pesan = 'sukses';
		$this->session->mark_as_flash('pesan');
		redirect('?d=proktor&c=profil&m=edit');
	}

}