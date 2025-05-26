<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_Gaji extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('hak_akses') != '2') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Anda Belum Login!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('login');
        }
    }

    public function index() {
        $data['title'] = "Data Gaji";
        $nik = $this->session->userdata('nik');
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();

        // Cek jenis gaji pegawai dengan pengecekan lebih aman
        $this->db->select('data_jabatan.jenis_gaji');
        $this->db->from('data_pegawai');
        $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
        $this->db->where('data_pegawai.nik', $nik);
        $row = $this->db->get()->row();
        $data['is_borongan'] = isset($row->jenis_gaji) && $row->jenis_gaji == 'Borongan';

        if ($data['is_borongan']) {
            // Gaji borongan
            $this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_pegawai.id_pegawai, data_jabatan.gaji_pokok, data_jabatan.tj_transport, data_jabatan.uang_makan, data_jabatan.tarif_borongan, target_mingguan.id, target_mingguan.target_mingguan, target_mingguan.bulan_target, target_mingguan.tahun_target, target_mingguan.mingguke, data_kehadiran.alpha');
            $this->db->from('data_pegawai');
            $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
            $this->db->join('target_mingguan', 'target_mingguan.nik_pegawai = data_pegawai.nik', 'left');
            $this->db->join('data_kehadiran', 'data_kehadiran.nik = data_pegawai.nik AND data_kehadiran.bulan = CONCAT(target_mingguan.bulan_target, target_mingguan.tahun_target)', 'left');
            $this->db->where('data_pegawai.nik', $nik);
            $this->db->order_by('target_mingguan.tahun_target ASC, target_mingguan.bulan_target ASC, target_mingguan.mingguke ASC'); // Ubah urutan ke ASC
            $gaji_result = $this->db->get()->result();

            // Tambahkan total_produksi dan filter hanya entri dengan total_produksi > 0
            $data['gaji'] = [];
            foreach ($gaji_result as $g) {
                $bulan = $g->bulan_target;
                $tahun = $g->tahun_target;
                $minggu = $g->mingguke;
                $start_day = ($minggu - 1) * 7 + 1;
                $end_day = $start_day + 6;
                $last_day_of_month = date('t', strtotime("$tahun-$bulan-01"));
                if ($end_day > $last_day_of_month) {
                    $end_day = $last_day_of_month;
                }
                $start_date = "$tahun-$bulan-" . str_pad($start_day, 2, '0', STR_PAD_LEFT);
                $end_date = "$tahun-$bulan-" . str_pad($end_day, 2, '0', STR_PAD_LEFT);

                $this->db->select('COALESCE(SUM(jumlah_unit), 0) as total_produksi');
                $this->db->from('produksi_harian');
                $this->db->where('id_pegawai', $g->id_pegawai);
                $this->db->where("tanggal BETWEEN '$start_date' AND '$end_date'");
                $total_produksi = $this->db->get()->row()->total_produksi;
                $g->total_produksi = $total_produksi;

                // Logging untuk debugging
                log_message('info', "NIK: {$nik}, Periode: $bulan-$tahun Minggu ke-$minggu, Total Produksi: $total_produksi, Date Range: $start_date to $end_date");

                // Hanya tambahkan entri jika total_produksi > 0
                if ($total_produksi > 0) {
                    $data['gaji'][] = $g;
                }
            }
        } else {
            // Gaji bulanan
            $this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_jabatan.gaji_pokok, data_jabatan.tj_transport, data_jabatan.uang_makan, data_kehadiran.alpha, data_kehadiran.bulan, data_kehadiran.id_kehadiran');
            $this->db->from('data_pegawai');
            $this->db->join('data_kehadiran', 'data_kehadiran.nik = data_pegawai.nik');
            $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
            $this->db->where('data_pegawai.nik', $nik);
            $this->db->order_by('data_kehadiran.bulan ASC');
            $data['gaji'] = $this->db->get()->result();
        }

        $this->load->view('template_pegawai/header', $data);
        $this->load->view('template_pegawai/sidebar');
        $this->load->view('pegawai/data_gaji', $data);
        $this->load->view('template_pegawai/footer');
    }

    public function cetak_slip($id) {
        $data['title'] = 'Cetak Slip Gaji';
        $nik = $this->session->userdata('nik');
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();

        // Cek jenis gaji dengan pengecekan lebih aman
        $this->db->select('data_jabatan.jenis_gaji');
        $this->db->from('data_pegawai');
        $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
        $this->db->where('data_pegawai.nik', $nik);
        $row = $this->db->get()->row();
        $data['is_borongan'] = isset($row->jenis_gaji) && $row->jenis_gaji == 'Borongan';

        if ($data['is_borongan']) {
            // Slip borongan
            $this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_pegawai.id_pegawai, data_jabatan.nama_jabatan, data_jabatan.gaji_pokok, data_jabatan.tj_transport, data_jabatan.uang_makan, data_jabatan.tarif_borongan, target_mingguan.target_mingguan, target_mingguan.bulan_target, target_mingguan.tahun_target, target_mingguan.mingguke, data_kehadiran.alpha');
            $this->db->from('data_pegawai');
            $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
            $this->db->join('target_mingguan', 'target_mingguan.nik_pegawai = data_pegawai.nik', 'left');
            $this->db->join('data_kehadiran', 'data_kehadiran.nik = data_pegawai.nik AND data_kehadiran.bulan = CONCAT(LPAD(target_mingguan.bulan_target, 2, "0"), target_mingguan.tahun_target)', 'left');
            $this->db->where('data_pegawai.nik', $nik);
            $this->db->where('target_mingguan.id', $id);
            $data['print_slip'] = $this->db->get()->row();

            if ($data['print_slip']) {
                // Calculate date range for the week
                $bulan = $data['print_slip']->bulan_target;
                $tahun = $data['print_slip']->tahun_target;
                $minggu = $data['print_slip']->mingguke;
                $start_day = ($minggu - 1) * 7 + 1;
                $end_day = $start_day + 6;
                $last_day_of_month = date('t', strtotime("$tahun-$bulan-01"));
                if ($end_day > $last_day_of_month) {
                    $end_day = $last_day_of_month;
                }
                $start_date = "$tahun-$bulan-" . str_pad($start_day, 2, '0', STR_PAD_LEFT);
                $end_date = "$tahun-$bulan-" . str_pad($end_day, 2, '0', STR_PAD_LEFT);

                // Add total_produksi
                $this->db->select('COALESCE(SUM(jumlah_unit), 0) as total_produksi');
                $this->db->from('produksi_harian');
                $this->db->where('id_pegawai', $data['print_slip']->id_pegawai);
                $this->db->where("tanggal BETWEEN '$start_date' AND '$end_date'");
                $total_produksi_result = $this->db->get()->row();
                $data['print_slip']->total_produksi = $total_produksi_result->total_produksi;

                // Debug: Log the data
                log_message('info', "Slip for NIK: {$data['print_slip']->nik}, Total Produksi: {$data['print_slip']->total_produksi}, Tarif Borangan: {$data['print_slip']->tarif_borongan}");
            }

            if (!$data['print_slip']) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Data slip gaji tidak ditemukan!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                    </div>');
                redirect('pegawai/data_gaji');
            }

            $this->load->view('pegawai/cetak_slip_gaji_borongan', $data);
        } else {
            // Slip bulanan
            $this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_jabatan.nama_jabatan, data_jabatan.gaji_pokok, data_jabatan.tj_transport, data_jabatan.uang_makan, data_kehadiran.alpha, data_kehadiran.bulan');
            $this->db->from('data_pegawai');
            $this->db->join('data_kehadiran', 'data_kehadiran.nik = data_pegawai.nik');
            $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
            $this->db->where('data_pegawai.nik', $nik);
            $this->db->where('data_kehadiran.id_kehadiran', $id);
            $data['print_slip'] = $this->db->get()->row();

            if (!$data['print_slip']) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Data slip gaji tidak ditemukan!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                    </div>');
                redirect('pegawai/data_gaji');
            }

            $this->load->view('pegawai/cetak_slip_gaji', $data);
        }
    }
}