<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/proktor/Home_proktor.php';

class Config extends Home_proktor{

	function sekolah(){
		$this->load->view('proktor/config/sekolah');
	}

	function submit_sekolah(){
		modif_app_config('NAMA_SEKOLAH', $this->input->post('nama_sekolah'));
		$this->session->pesan = 'sukses';
		if(!empty($_FILES['logo']['name'])){		
			$config['upload_path'] = './assets/';
			$config['allowed_types'] = 'png';
			$config['max_size']     = '512';
			$config['file_name'] = 'logo';
			$config['overwrite'] = true;

			$this->load->library('upload', $config);

			// Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
			$this->upload->initialize($config);
			$do_upload = $this->upload->do_upload('logo');
			if($do_upload){
				$data_upload = $this->upload->data();
				modif_app_config('LOGO_SEKOLAH', $data_upload['file_name']);
			}else{
				$this->session->pesan = 'gagal_upload';
			}
    }
    
    // bersihkan cache logo
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

    $this->session->mark_as_flash('pesan');
    

		redirect('?d=proktor&c=config&m=sekolah');
	}

	function theme_color(){
		modif_app_config('THEME_COLOR', $this->input->post('theme'));
		return 'ok';
	}

	function token(){
		$this->load->view('proktor/config/token');
	}

	function generate_token(){
		$token = string_acak(5);
		modif_app_config('TOKEN', $token);
		json_output(200, array('token' => $token));
	}
}