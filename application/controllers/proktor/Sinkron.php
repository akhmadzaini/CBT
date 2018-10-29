<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require FCPATH . 'vendor/autoload.php';
use \Curl\Curl;

require_once APPPATH . 'controllers/proktor/Home_proktor.php';

class Sinkron extends Home_proktor{
    
    function tarik(){
        $this->load->view('proktor/sinkron/tarik');
    }
    
    function do_tarik(){
        $token = 'kEXCZ9KjumHxTO8dsVyg';
        $post = $this->input->post();
        $target = $post['server_remote'] . '/index.php?c=sinkron&m=tarik';
        $data = [
            'token' => $token,
            'id_server' => $post['id_server']
        ];
        
        // kirim request ke server remote
        $curl = new Curl();
        $curl->post($target, $data);
        
        if($curl->getHttpStatusCode() == 200){
            
            $r = $curl->response;
            if(!isset($r->pesan) || $r->pesan == 'token_gagal'){
                json_output(200, array('pesan' => 'token_gagal'));
            }else{
                // Simpan zip dari remote ke lokal
                $full_path_zip = $post['server_remote'] . '/index.php?c=sinkron&m=tarik_zip&zip=' . $r->nama_zip;
                $nama_sinkron = FCPATH . 'public/sinkron_' . $r->nama_zip;
                $d = new Curl();
                $d->setOpt(CURLOPT_ENCODING , '');
                $d->download($full_path_zip, $nama_sinkron);
                $d->close();
                
                json_output(200, array('pesan' => 'ok', 'arsip_sinkron' => 'public/sinkron_' . $r->nama_zip));
                $curl->close();
            }
        }else{
            json_output(200, array('pesan' => 'konek_gagal'));
        }
    }
    
    function do_restore(){
        // 1. Reset data
        data_do_reset();

        // 2. ekstrak backup
        $arsip_sinkron = $this->input->get('arsip_sinkron');
        $fullpath_arsip_sinkron = FCPATH . $arsip_sinkron;
        $ekstrak_path = FCPATH . 'public/tmp-sinkron';
        $berhasil_ekstrak = ekstrak_zip($fullpath_arsip_sinkron, $ekstrak_path);
        
        // 3. pindahkan gambar
        rcopy($ekstrak_path . '/images', FCPATH . 'images');
        rrmdir($ekstrak_path . '/images');
        
        // 4. baca data json, sekaligus masukkan ke database
        $string = file_get_contents($ekstrak_path . '/data.json');
        $data = json_decode($string, true);
        data_do_pemulihan_data($data);
        
        // 5. hapus sisa backup yg tak diperlukan lagi
        unlink($fullpath_arsip_sinkron);
        rrmdir($ekstrak_path);

        // 6. atur notif
        json_output(200, array('pesan' => 'ok'));
    }
    
    function kirim(){
        
    }
    
}