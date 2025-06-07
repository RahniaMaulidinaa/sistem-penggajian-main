<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_Upah extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('ModelPenggajian');
        if ($this->session->userdata('hak_akses') != '3') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
               
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('login');
        }
    }

    public function index() {
        $data['title'] = "Laporan Gaji Pegawai Bulanan";
        $this->load->view('template_hrd/header', $data);
        $this->load->view('template_hrd/sidebar');
        $this->load->view('hrd/upah/laporan_upah', $data);
        $this->load->view('template_hrd/footer');
    }

    public function cetak_laporan_upah() {
        $data['title'] = "Cetak Laporan Gaji Pegawai";

        if((isset($_POST['bulan']) && $_POST['bulan'] != '') && (isset($_POST['tahun']) && $_POST['tahun'] != '')){
			$bulan = $_POST['bulan'];
			$tahun = $_POST['tahun'];
			$bulantahun = $bulan . $tahun;
		} else {
			$bulan = date('m');
			$tahun = date('Y');
			$bulantahun = $bulan . $tahun;
		}

		$data['bulan'] = $bulan;
		$data['tahun'] = $tahun;
		$data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
		$data['cetak_gaji'] = $this->db->query("
			SELECT DISTINCT data_pegawai.nik, data_pegawai.nama_pegawai,
				data_pegawai.jenis_kelamin, data_jabatan.nama_jabatan,
				data_jabatan.gaji_pokok, data_jabatan.tj_transport,
				data_jabatan.uang_makan, data_kehadiran.alpha
			FROM data_pegawai
			INNER JOIN (
				SELECT nik, bulan, MAX(alpha) as alpha
				FROM data_kehadiran
				WHERE bulan = '$bulantahun'
				GROUP BY nik, bulan
			) data_kehadiran ON data_kehadiran.nik = data_pegawai.nik
			INNER JOIN data_jabatan ON data_jabatan.nama_jabatan = data_pegawai.jabatan
			WHERE data_jabatan.jenis_gaji = 'Bulanan'
			ORDER BY data_pegawai.nama_pegawai ASC
		")->result();

		$this->load->view('hrd/upah/cetak_upah', $data);
	}
}