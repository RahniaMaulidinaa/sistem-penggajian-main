<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_Gaji extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		if ($this->session->userdata('hak_akses') != '3') {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Anda Belum Login!</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
				</div>');
			redirect('login');
		}
	}

	public function index()
	{
		$nik = $this->session->userdata('nik');
		$data['title'] = "Data Gaji";
		$data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();

		// 1. Cek jenis gaji pegawai berdasarkan nik
		$this->db->select('p.nik, p.id_pegawai, p.nama_pegawai, p.jabatan, j.jenis_gaji');
		$this->db->from('data_pegawai p');
		$this->db->join('data_jabatan j', 'j.nama_jabatan = p.jabatan');
		$this->db->where('p.nik', $nik);
		$pegawai = $this->db->get()->row();

		if (!$pegawai) {
			show_error('Data pegawai tidak ditemukan.');
		}

		$jenis_gaji = $pegawai->jenis_gaji;
		$data['jenis_gaji'] = $jenis_gaji;

		if ($jenis_gaji === 'Bulanan') {
			// 2. Query untuk gaji bulanan
			$this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_jabatan.gaji_pokok, data_jabatan.tj_transport, data_jabatan.uang_makan, data_kehadiran.alpha, data_kehadiran.bulan, data_kehadiran.id_kehadiran');
			$this->db->from('data_pegawai');
			$this->db->join('data_kehadiran', 'data_kehadiran.nik = data_pegawai.nik');
			$this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
			$this->db->where('data_pegawai.nik', $nik);
			$this->db->order_by('data_kehadiran.bulan DESC');
			$data['gaji'] = $this->db->get()->result();
		} elseif ($jenis_gaji === 'Borongan') {
			$this->db->select("p.nik, p.nama_pegawai, p.jabatan, j.jenis_gaji,
			CONCAT(YEAR(ph.tanggal), '-', LPAD(MONTH(ph.tanggal), 2, '0'), '-Minggu ', CEIL(DAY(ph.tanggal)/7)) AS periode,
			SUM(ph.jumlah_unit) AS total_produksi,
			j.tarif_borongan AS tarif,
			SUM(ph.jumlah_unit) * j.tarif_borongan AS total_gaji,
			k.alpha AS alpha,
			k.id_kehadiran");
			$this->db->from('data_pegawai p');
			$this->db->join('data_jabatan j', 'j.nama_jabatan = p.jabatan');
			$this->db->join('produksi_harian ph', 'ph.id_pegawai = p.id_pegawai');
			$this->db->join('data_kehadiran k', "k.nik = p.nik AND k.bulan = DATE_FORMAT(ph.tanggal, '%m%Y')", 'left');
			$this->db->where('p.nik', $nik);
			$this->db->group_by(['p.nik', 'periode', 'k.id_kehadiran']);
			$data['gaji'] = $this->db->get()->result();
		} else {
			show_error('Jenis gaji tidak dikenali.');
		}
		$this->load->view('template_hrd/header', $data);
        $this->load->view('template_hrd/sidebar');
        $this->load->view('hrd/data_gaji', $data);
        $this->load->view('template_hrd/footer');
    }

	public function cetak_slip($id = null)
	{
		if (!$id) {
			show_error('Parameter ID kehadiran tidak diberikan.');
		}

		$nik = $this->session->userdata('nik');
		$data['title'] = "Data Gaji";
		$data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();

		// Ambil data pegawai + jenis gaji
		$this->db->select('p.nik, p.id_pegawai, p.nama_pegawai, p.jabatan, j.jenis_gaji');
		$this->db->from('data_pegawai p');
		$this->db->join('data_jabatan j', 'j.nama_jabatan = p.jabatan');
		$this->db->where('p.nik', $nik);
		$pegawai = $this->db->get()->row();

		if (!$pegawai) {
			show_error('Data pegawai tidak ditemukan.');
		}

		$jenis_gaji = $pegawai->jenis_gaji;
		$data['jenis_gaji'] = $jenis_gaji;

		if ($jenis_gaji === 'Bulanan') {
			// Ambil data kehadiran untuk slip bulanan
			$this->db->select('p.nik, p.nama_pegawai, j.nama_jabatan, j.gaji_pokok, j.tj_transport, j.uang_makan, k.alpha, k.bulan');
			$this->db->from('data_pegawai p');
			$this->db->join('data_kehadiran k', 'k.nik = p.nik');
			$this->db->join('data_jabatan j', 'j.nama_jabatan = p.jabatan');
			$this->db->where('p.nik', $nik);
			$this->db->where('k.id_kehadiran', $id);
			$data['print_slip'] = $this->db->get()->row();

			if (!$data['print_slip']) {
				$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data slip gaji tidak ditemukan!</div>');
				redirect('hrd/data_gaji');
			}

			$this->load->view('hrd/cetak_slip_gaji', $data);
		} else {
			// Ambil data kehadiran untuk cari bulan produksi borongan
			$this->db->where('id_kehadiran', $id);
			$kehadiran = $this->db->get('data_kehadiran')->row();

			if (!$kehadiran) {
				show_error('Data kehadiran tidak ditemukan.');
			}

			$bulan = $kehadiran->bulan; // format MMYYYY
			$bulan_sql = substr($bulan, 0, 2); // MM
			$tahun_sql = substr($bulan, 2, 4); // YYYY

			// Slip borongan berdasarkan bulan kehadiran
			$this->db->select("p.nik, p.nama_pegawai, p.jabatan, j.jenis_gaji,
				CONCAT(YEAR(ph.tanggal), '-', LPAD(MONTH(ph.tanggal), 2, '0')) AS periode,
				SUM(ph.jumlah_unit) AS total_produksi,
				j.tarif_borongan AS tarif,
				SUM(ph.jumlah_unit) * j.tarif_borongan AS total_gaji,
				COUNT(k.id_kehadiran) AS total_kehadiran");
			$this->db->from('data_pegawai p');
			$this->db->join('data_jabatan j', 'j.nama_jabatan = p.jabatan');
			$this->db->join('produksi_harian ph', 'ph.id_pegawai = p.id_pegawai');
			$this->db->join('data_kehadiran k', 'k.nik = p.nik');
			$this->db->where('p.nik', $nik);
			$this->db->where('MONTH(ph.tanggal)', $bulan_sql);
			$this->db->where('YEAR(ph.tanggal)', $tahun_sql);
			$this->db->group_by(['p.nik', 'periode']);
			$data['gaji'] = $this->db->get()->row();

			if (!$data['gaji']) {
				$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data slip borongan tidak ditemukan!</div>');
				redirect('hrd/data_gaji');
			}

			$this->load->view('hrd/cetak_slip_gaji_borongan', $data);
		}
	}
}

