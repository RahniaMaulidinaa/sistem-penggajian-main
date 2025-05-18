<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_Penggajian_Borongan_Hrd extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('hak_akses') != '2') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Anda Belum Login sebagai HRD!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('login');
        }
    }

    public function index() {
        $data['title'] = "Data Gaji Pegawai Borongan (HRD)";

        if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
            $bulantahun = $bulan . $tahun;
        } else {
            $bulan = date('m');
            $tahun = date('Y');
            $bulantahun = $bulan . $tahun;
        }

        $data['borongan'] = $this->db->query("
            SELECT DISTINCT data_pegawai.nik, data_pegawai.nama_pegawai,
                data_pegawai.jenis_kelamin, data_jabatan.nama_jabatan, 
                data_jabatan.gaji_pokok AS upah_per_item, COUNT(pesanan.id_pesanan) as jumlah_pesanan,
                (COUNT(pesanan.id_pesanan) * data_jabatan.gaji_pokok) AS total_upah
            FROM data_pegawai
            JOIN data_jabatan ON data_pegawai.jabatan = data_jabatan.nama_jabatan
            LEFT JOIN pesanan ON data_pegawai.nik = pesanan.nik 
                AND DATE_FORMAT(pesanan.tanggal, '%m%Y') = '$bulantahun'
            WHERE data_jabatan.jenis_gaji = 'Borongan'
            GROUP BY data_pegawai.nik
            ORDER BY data_pegawai.nama_pegawai ASC
        ")->result();

        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $this->load->view('template_hrd/header', $data);
        $this->load->view('template_hrd/sidebar');
        $this->load->view('hrd/gaji/data_gaji_borongan', $data);
        $this->load->view('template_hrd/footer');
    }
}
