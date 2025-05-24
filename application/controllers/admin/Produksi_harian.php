<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produksi_harian extends CI_Controller {
    public function __construct() {
        parent::__construct();

        if($this->session->userdata('hak_akses') != '1') {
            $this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Anda Belum Login!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('login');
        }
    }

    public function index() {
        $data['title'] = "Data Produksi Harian";
        $data['produksi'] = $this->db->get('produksi_harian')->result();
        $data['pegawai'] = $this->db->get('data_pegawai')->result_array();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/produksi_harian', $data);
        $this->load->view('template_admin/footer');
    }
    public function tambah()
    {
        $id_pegawai   = $this->input->post('id_pegawai');
        $tanggal      = $this->input->post('tanggal');
        $jumlah_unit  = $this->input->post('jumlah_unit');
    
        $data = [
            'id_pegawai'  => $id_pegawai,
            'tanggal'     => $tanggal,
            'jumlah_unit' => $jumlah_unit
        ];
    
        $this->db->insert('produksi_harian', $data); // atau gunakan model jika pakai model
    
        redirect('produksi_harian'); // redirect ke halaman data produksi
    }
}
?>
