<?php

class Dashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if($this->session->userdata('hak_akses') != '1'){
            $this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Anda Belum Login!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>');
                redirect('login');
        }
    }
    public function index() 
    {
        $pegawai = $this->db->query("SELECT * FROM data_pegawai");
        $admin = $this->db->query("SELECT * FROM data_pegawai WHERE jabatan = 'Admin'");
        $jabatan = $this->db->query("SELECT * FROM data_jabatan");
        $kehadiran = $this->db->query("SELECT * FROM data_kehadiran");

        // Ambil total produksi harian untuk hari ini dengan kolom yang benar: jumlah_unit
        $today = date('Y-m-d');
        $this->db->select_sum('jumlah_unit');
        $this->db->where('tanggal', $today);
        $produksi_harian = $this->db->get('produksi_harian');
        $total_produksi = $produksi_harian->row()->jumlah_unit ? $produksi_harian->row()->jumlah_unit : 0;
        $data['total_produksi_harian'] = $total_produksi;
        $data['title'] = "Dashboard Admin";
        $data['pegawai'] = $pegawai->num_rows();
        $data['admin'] = $admin->num_rows();
        $data['jabatan'] = $jabatan->num_rows();
        $data['kehadiran'] = $kehadiran->num_rows();

        $this->load->view('template_admin/header',$data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('template_admin/footer');
    }
}

?>
